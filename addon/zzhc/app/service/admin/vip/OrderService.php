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

namespace addon\zzhc\app\service\admin\vip;

use addon\zzhc\app\model\vip\Order;

use core\base\BaseAdminService;


/**
 * 办卡订单服务层
 * Class OrderService
 * @package addon\zzhc\app\service\admin\vip
 */
class OrderService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Order();
    }

    /**
     * 获取办卡订单列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'order_id,site_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,vip_id,vip_name,days,vip_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';
        $order = 'order_id desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["order_no","nickname","mobile","status","create_time"], $where)->append(['status_name','order_from_name'])->field($field)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取办卡订单信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'order_id,site_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,vip_id,vip_name,days,vip_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';

        $info = $this->model->field($field)->where([['order_id', "=", $id]])->findOrEmpty()->toArray();
        return $info;
    }

    /**
     * 添加办卡订单
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['site_id'] = $this->site_id;
        $res = $this->model->create($data);
        return $res->order_id;

    }

    /**
     * 办卡订单编辑
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data)
    {

        $this->model->where([['order_id', '=', $id],['site_id', '=', $this->site_id]])->update($data);
        return true;
    }

    /**
     * 删除办卡订单
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['order_id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }
    
}
