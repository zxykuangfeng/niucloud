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

namespace addon\zzhc\app\adminapi\controller\order;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\order\OrderService;
use addon\zzhc\app\dict\order\OrderDict;


/**
 * 预约订单控制器
 * Class Order
 * @package addon\zzhc\app\adminapi\controller\order
 */
class Order extends BaseAdminController
{
   /**
    * 获取预约订单列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["store_id",""],
             ["order_no",""],
             ["nickname",""],
             ["mobile",""],
             ["staff_name",""],
             ["goods_name",""],
             ["status",""],
             ["create_time",["",""]]
        ]);
        return success((new OrderService())->getPage($data));
    }

    /**
     * 预约订单详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new OrderService())->getInfo($id));
    }

    /**
     * 添加预约订单
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["store_id",0],
             ["order_no",""],
             ["order_type",""],
             ["order_from",""],
             ["member_id",0],
             ["nickname",""],
             ["headimg",""],
             ["mobile",""],
             ["staff_id",0],
             ["staff_headimg",""],
             ["staff_name",""],
             ["goods_id",0],
             ["goods_name",""],
             ["duration",0],
             ["goods_money",0.00],
             ["goods_vip_money",0.00],
             ["discount_money",0.00],
             ["order_money",0.00],
             ["pay_time",0],
             ["out_trade_no",""],
             ["status",0],
             ["ip",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\order\Order.add');
        $id = (new OrderService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 预约订单编辑
     * @param $id  预约订单id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["store_id",0],
             ["order_no",""],
             ["order_type",""],
             ["order_from",""],
             ["member_id",0],
             ["nickname",""],
             ["headimg",""],
             ["mobile",""],
             ["staff_id",0],
             ["staff_headimg",""],
             ["staff_name",""],
             ["goods_id",0],
             ["goods_name",""],
             ["duration",0],
             ["goods_money",0.00],
             ["goods_vip_money",0.00],
             ["discount_money",0.00],
             ["order_money",0.00],
             ["pay_time",0],
             ["out_trade_no",""],
             ["status",0],
             ["ip",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\order\Order.edit');
        (new OrderService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 预约订单删除
     * @param $id  预约订单id
     * @return \think\Response
     */
    public function del(int $id){
        (new OrderService())->del($id);
        return success('DELETE_SUCCESS');
    }

    /**
     * 获取状态
     * @return \think\Response
     */
    public function getStatus()
    {
        return success(OrderDict::getStatus());
    }

    /**
     * 订单取消
     * @param $order_id
     * @return Response
     */
    public function cancel($id){
        ( new OrderService() )->cancel($id);
        return success('取消成功');
    }

    
}
