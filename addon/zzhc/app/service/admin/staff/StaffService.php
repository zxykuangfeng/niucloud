<?php
// +----------------------------------------------------------------------
// | Niucloud-admin 企业快速开发的多应用管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace addon\zzhc\app\service\admin\staff;

use addon\zzhc\app\model\staff\Staff;
use addon\zzhc\app\model\store\Store;
use core\exception\AdminException;
use core\base\BaseAdminService;


/**
 * 员工服务层
 * Class StaffService
 * @package addon\zzhc\app\service\admin\staff
 */
class StaffService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Staff();
    }

    /**
     * 获取员工列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'staff_id,site_id,store_id,member_id,staff_role,staff_headimg,staff_name,staff_mobile,staff_position,staff_experience,staff_image,staff_content,sort,status,create_time,update_time';
        $order = 'sort desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["store_id","staff_name","staff_mobile"], $where)->with(['store','member'])->field($field)->append(['staff_role_name'])->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取员工信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'staff_id,site_id,store_id,member_id,staff_role,staff_headimg,staff_name,staff_mobile,staff_position,staff_experience,staff_image,staff_content,sort,status,create_time,update_time';

        $info = $this->model->field($field)->where([['staff_id', "=", $id]])->with(['store','member'])->findOrEmpty()->toArray();
        return $info;
    }

    /**
     * 添加员工
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {

        //检测会员以否已绑定当前门店员工
        if(!empty($data['member_id']) && !$this->model->where([['site_id', '=', $this->site_id], ['store_id', '=', $data['store_id']],['member_id', '=', $data['member_id']]])->findOrEmpty()->isEmpty()){
            throw new AdminException('会员已绑定当前门店其他员工');
        }

        $data['site_id'] = $this->site_id;
        $res = $this->model->create($data);
        return $res->staff_id;

    }

    /**
     * 员工编辑
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data)
    {
        //检测会员以否已绑定
        if(!empty($data['member_id']) && !$this->model->where([['site_id', '=', $this->site_id],['staff_id', '<>', $id],['store_id', '=', $data['store_id']],['member_id', '=', $data['member_id']]])->findOrEmpty()->isEmpty()){
            throw new AdminException('会员已绑定当前门店其他员工');
        }
        $this->model->where([['staff_id', '=', $id],['site_id', '=', $this->site_id]])->update($data);
        return true;
    }

    /**
     * 删除员工
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['staff_id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }

    public function getStaffAll(){
        return $this->model->where([["site_id","=",$this->site_id]])->select()->toArray();
    }
    
    
}
