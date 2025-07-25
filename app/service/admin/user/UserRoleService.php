<?php
// +----------------------------------------------------------------------
// | Niucloud-admin 企业快速开发的saas管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace app\service\admin\user;


use app\dict\sys\RoleStatusDict;
use app\dict\sys\UserDict;
use app\model\sys\SysRole;
use app\model\sys\SysUserRole;
use app\service\admin\sys\RoleService;
use core\base\BaseAdminService;
use core\exception\AdminException;
use core\exception\CommonException;
use think\facade\Cache;
use think\Model;

/**
 * 用户服务层
 * Class BaseService
 * @package app\service
 */
class UserRoleService extends BaseAdminService
{
    public static $role_cache_name = 'user_role_cache';

    public function __construct()
    {
        parent::__construct();
       $this->model = new SysUserRole();
    }

    /**
     * 添加用户权限(添加站点用户)
     * @param int $uid
     * @param array $data
     * @param int $site_id
     * @return true
     */
    public function add(int $uid, array $data, int $site_id = 0){
        $user_role_model = new SysUserRole();
        $is_exist = $user_role_model->where([ ['uid', '=', $uid], ['site_id', '=', $site_id] ])->count();
        if ($is_exist) throw new CommonException('SITE_USER_EXIST');

        $is_admin = $data['is_admin'] ?? 0;
        $role_data = array(
            'uid' => $uid,
            'is_admin' => $is_admin,
            'site_id' => $site_id == 0 ? $this->site_id : $site_id,
            'status' => $data['status'] ?? UserDict::ON
        );
        if(!$is_admin){
            //校验权限越界
            $role_data['role_ids'] = $data['role_ids'] ?? [];
        }
        $user_role_model->save($role_data);
        Cache::delete('user_role_'.$uid.'_'.$site_id);
        Cache::delete('user_role_list_' .$uid);
        return true;
    }

    /**
     * 更新用户权限(编辑站点用户)
     * @param int $site_id
     * @param int $uid
     * @param array $role_ids
     * @return bool
     */
    public function edit(int $site_id, int $uid, array $role_ids, $status = UserDict::ON){
        $user_role = $this->model->where([['uid', '=', $uid], ['site_id', '=', $site_id]])->findOrEmpty();
        if ($user_role->isEmpty())
            throw new AdminException('NO_SITE_USER_ROLE');

        $is_admin = $user_role->is_admin;
        if($is_admin)//超级管理员不允许改动权限
            throw new AdminException('ADMIN_NOT_ALLOW_EDIT_ROLE');
        $user_role->status = $status;
        if (!empty(array_diff_assoc($role_ids, $user_role->role_ids))) {
            //校验权限越界
            $user_role->role_ids = $role_ids;
        }
        Cache::delete('user_role_'.$uid.'_'.$site_id);
        Cache::delete('user_role_list_' .$uid);
        $user_role->save();
        return true;
    }

    /**
     * 用户权限信息(获取用户对应站点权限)
     * @param int $site_id
     * @param int $uid
     * @return mixed
     */
    public function getUserRole(int $site_id, int $uid){
        $cache_name = 'user_role_'.$uid.'_'.$site_id;
        return cache_remember(
            $cache_name,
            function() use($uid, $site_id) {
                $user_role_model = new SysUserRole();
                $where = array(
                    ['uid', '=', $uid],
                    ['site_id', '=', $site_id]
                );
                return $user_role_model->where($where)->findOrEmpty()->toArray();
            },
            [self::$role_cache_name, RoleService::$cache_tag_name.$this->site_id]
        );
    }

    /**
     * 获取用户默认站点(切勿用于平台管理端)
     * @param int $uid
     * @return SysUserRole|array|mixed|Model
     */
    public function getUserDefaultSiteId(int $uid){
        $user_role_model = new SysUserRole();
        $default_site_id = $this->request->defaultSiteId();
        return $user_role_model->where([['uid', '=', $uid], ['site_id', '<>', $default_site_id]])->findOrEmpty()?->site_id;
    }

    /**
     * 通过角色id组获取角色
     * @param array $role_ids
     * @param int $site_id
     * @return mixed
     */
    public function getRoleByUserRoleIds(array $role_ids, int $site_id){
        sort($role_ids);
        $cache_name = 'role_by_ids_'.md5(implode(',', $role_ids)).'_'.$site_id;
        return cache_remember(
            $cache_name,
            function() use($role_ids, $site_id) {
                $where = array(
                    ['role_id', 'in', $role_ids],
                    ['site_id', '=', $site_id],
                    ['status', '=', RoleStatusDict::ON ]
                );
                return SysRole::where($where)->column('role_name');
            },
            [self::$role_cache_name, RoleService::$cache_tag_name.$site_id]
        );
    }

    /**
     * 更改站点管理员
     * @param int $site_id
     * @param int $uid
     * @return bool
     */
    public function editAdmin(int $site_id, int $uid){
        $user_role = $this->model->where([['is_admin', '=', 1], ['site_id', '=', $site_id]])->findOrEmpty();
        if ($user_role->isEmpty())
            throw new AdminException('USER_NOT_EXIST');
        $user_role->uid = $uid;
        $user_role->save();
        Cache::delete('user_role_'.$uid.'_'.$site_id);
        Cache::delete('user_role_list_' .$uid);
        return true;
    }


}
