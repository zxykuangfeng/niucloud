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

use addon\zzhc\app\model\staff\Work;

use core\base\BaseAdminService;


/**
 * 考勤管理服务层
 * Class WorkService
 * @package addon\zzhc\app\service\admin\staff
 */
class WorkService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Work();
    }

    /**
     * 获取考勤管理列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'id,site_id,store_id,staff_id,status,duration,full_address,create_time,update_time';
        $order = 'id desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["store_id","staff_id","status","create_time"], $where)->with(['store','staff'])->field($field)->append(['status_name'])->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取考勤管理信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'id,site_id,store_id,staff_id,status,duration,create_time,update_time';

        $info = $this->model->field($field)->where([['id', "=", $id]])->findOrEmpty()->toArray();
        return $info;
    }

    /**
     * 添加考勤管理
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['site_id'] = $this->site_id;
        $res = $this->model->create($data);
        return $res->id;

    }

    /**
     * 考勤管理编辑
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data)
    {

        $this->model->where([['id', '=', $id],['site_id', '=', $this->site_id]])->update($data);
        return true;
    }

    /**
     * 删除考勤管理
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }
    
}
