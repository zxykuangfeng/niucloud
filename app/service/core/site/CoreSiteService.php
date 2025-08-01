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

namespace app\service\core\site;

use app\dict\addon\AddonDict;
use app\dict\site\SiteDict;
use app\dict\sys\AppTypeDict;
use app\model\addon\Addon;
use app\model\site\Site;
use app\model\site\SiteGroup;
use core\base\BaseCoreService;
use core\exception\CommonException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Cache;
use think\Model;

/**
 * 站点服务层
 * Class Site
 * @package app\service\admin\site
 */
class CoreSiteService extends BaseCoreService
{
    public static $cache_tag_name = 'site_cash';

    public function __construct()
    {
        parent::__construct();
        $this->model = new Site();
    }

    /**
     * 获取授权当前站点信息(用做缓存)
     * @param $site_id
     * @return mixed|string
     */
    public function getSiteCache($site_id)
    {
        $cache_name = 'site_info_cache';
        return cache_remember(
            $cache_name . $site_id,
            function() use ($site_id) {
                $where = [
                    [ 'site_id', '=', $site_id ],
                ];
                $info = $this->model->where($where)->field('site_id, site_name, front_end_name, front_end_logo,front_end_icon, app_type, keywords, logo, icon, `desc`, status, latitude, longitude, province_id, city_id, district_id, address, full_address, phone, business_hours, create_time, expire_time, group_id, app, addons, site_domain')->append([ 'status_name' ])->findOrEmpty()->toArray();
                if (!empty($info)) {
                    $site_addons = ( new CoreSiteService() )->getAddonKeysBySiteId((int) $site_id);
                    $info[ 'apps' ] = ( new Addon() )->where([ [ 'key', 'in', $site_addons ], [ 'type', '=', AddonDict::APP ] ])->field('key,title,desc,icon,type,support_app')->select()->toArray();
                    $info[ 'site_addons' ] = ( new Addon() )->where([ [ 'key', 'in', $site_addons ], [ 'type', '=', AddonDict::ADDON ] ])->field('key,title,desc,icon,type,support_app')->select()->toArray();
                }
                return $info;
            },
            self::$cache_tag_name . $site_id
        );
    }

    /**
     * 根据域名获取站点id
     * @param string $domain
     * @return mixed|string
     */
    public function getSiteIdByDomain(string $domain)
    {
        if (empty($domain)) return;

        $cache_name = 'site_domain';
        return cache_remember(
            $cache_name . $domain,
            function() use ($domain) {
                return $this->model->where([ [ 'site_domain', '=', $domain ] ])->value('site_id');
            },
            self::$cache_tag_name
        );
    }

    /**
     * 模型实例
     * @param $site_id
     * @return Site|array|mixed|Model
     */
    public function find($site_id)
    {
        return $this->model->findOrEmpty($site_id);
    }

    /**
     * 获取过期的站点
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getExpireSiteList()
    {
        return $this->model->where([
            [ 'status', '<>', SiteDict::EXPIRE ],
            [ 'expire_time', 'between', [ 1, time() ] ],
        ])->field('site_id,status,site_name')->select()->toArray();
    }

    /**
     * 站点到期(计划任务专用,切勿调用)
     * @param int $site_id
     * @return bool
     */
    public function expire(int $site_id)
    {
        $site = $this->find($site_id);
        if ($site->isEmpty()) throw new CommonException('SITE_NOT_EXIST');
        if ($site->status == SiteDict::EXPIRE) throw new CommonException('SITE_EXPIRE');
        $this->model->where([ [
            'site_id', '=', $site_id
        ] ])->update(
            [
                'status' => SiteDict::EXPIRE,
            ]
        );
        Cache::delete('site_info_cache' . $site_id);
        Cache::tag(self::$cache_tag_name . $site_id)->clear();
        return true;
    }

    /**
     * 获取站点支持的应用插件
     * @param int $site_id
     * @return array
     */
    public function getAddonKeysBySiteId(int $site_id)
    {
        $cache_name = 'site_addon_key_cache';
        return cache_remember(
            $cache_name . $site_id,
            function() use ($site_id) {
                $site_info = ( new Site() )->where([ [ 'site_id', '=', $site_id ] ])->field('group_id,app_type,addons')->findOrEmpty();
                if ($site_info->isEmpty()) return [];

                $app_type = $site_info[ 'app_type' ];
                $group_addon_keys = [];

                if ($app_type == AppTypeDict::SITE) {
                    $group_id = $site_info[ 'group_id' ] ?? 0;
                    if ($group_id > 0) {
                        $group = ( new SiteGroup() )->field('app,addon')->findOrEmpty($group_id);
                        if (!$group->isEmpty()) {
                            $group_addon_keys = array_merge($group[ 'app' ], $group[ 'addon' ]);
                        }
                    }
                }
                //在查询站点所拥有的应用插件,两者结合
                $site_addon_keys = is_array($site_info[ 'addons' ]) ? $site_info[ 'addons' ] : [];
                return array_unique(array_merge($group_addon_keys ?? [], $site_addon_keys));
            },
            self::$cache_tag_name . $site_id
        );
    }
}
