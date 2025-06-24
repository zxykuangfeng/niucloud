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

namespace addon\zzhc\app\service\admin\order;

use addon\zzhc\app\model\order\Order;
use addon\zzhc\app\dict\order\OrderLogDict;
use core\base\BaseAdminService;
use app\model\pay\Pay;
use addon\zzhc\app\service\core\order\CoreOrderService;



/**
 * 预约订单服务层
 * Class OrderService
 * @package addon\zzhc\app\service\admin\order
 */
class OrderService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Order();
    }

    /**
     * 获取预约订单列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'order_id,site_id,store_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,staff_id,staff_headimg,staff_name,goods_id,goods_name,duration,goods_money,goods_vip_money,discount_money,order_money,pay_time,out_trade_no,status,ip,is_vip,create_time,update_time';
        $order = 'order_id desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["store_id","order_no","nickname","mobile","staff_name","goods_name","status","create_time"], $where)->with(['store'])->append(['status_name','order_from_name'])->field($field)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取预约订单信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'order_id,site_id,store_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,staff_id,staff_headimg,staff_name,goods_id,goods_name,duration,goods_money,goods_vip_money,discount_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';

        $info = $this->model->field($field)->where([['order_id', "=", $id]])->append(['status_name','order_from_name'])->with(['store'])->findOrEmpty()->toArray();

        if (!empty($info)) {
            
            if ($info[ 'out_trade_no' ]) {
                $info[ 'pay' ] = ( new Pay() )->where([ [ 'out_trade_no', '=', $info[ 'out_trade_no' ] ] ])
                    ->field('out_trade_no, type, pay_time')->append([ 'type_name' ])
                    ->findOrEmpty()->toArray();
            }
            
        }

        return $info;
    }

    /**
     * 添加预约订单
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
     * 预约订单编辑
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
     * 删除预约订单
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['order_id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }

    /**
     * 订单取消
     * @param array $data
     * @return void
     */
    public function cancel(int $order_id)
    {
        $data[ 'main_type' ] = OrderLogDict::SYSTEM;
        $data[ 'main_id' ] = $this->uid;
        $data[ 'order_id' ] = $order_id;
        $data[ 'site_id' ] = $this->site_id;
        ( new CoreOrderService() )->cancel($data);
        return true;
    }
    
}
