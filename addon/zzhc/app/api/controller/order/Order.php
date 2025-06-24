<?php
// +----------------------------------------------------------------------
// | Niucloud-api 企业快速开发的多应用管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace addon\zzhc\app\api\controller\order;

use core\base\BaseApiController;
use addon\zzhc\app\service\api\order\OrderService;
use addon\zzhc\app\dict\order\OrderDict;


/**
 * 预约订单控制器
 * Class Order
 * @package addon\zzhc\app\api\controller\order
 */
class Order extends BaseApiController
{
    /**
     * 订单列表
     * @return Response
     */
    public function lists()
    {
        $data = $this->request->params([
            [ 'status', '' ],
        ]);
        return success(( new OrderService() )->getPage($data));
    }
   

    /**
     * 获取订单状态
     * @return Response
     */
    public function getStatus()
    {
        return success(OrderDict::getStatus());
    }


    /**
     * 预约订单详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $order_id){
        return success((new OrderService())->getInfo($order_id));
    }

    /**
     * 创建预约订单
     * @return \think\Response
     */
    public function create(){
        $data = $this->request->params([
             ["store_id",0],
             ["staff_id",0],
             ["goods_id",0],
        ]);
        $orderId = (new OrderService())->createOrder($data);
        return success('ADD_SUCCESS', ['order_id' => $orderId]);
    }

    /**
     * 订单取消
     * @param $order_id
     * @return Response
     */
    public function cancel($order_id){
        return success(( new OrderService() )->cancel($order_id));
    }

    /**
     * 添加或更新订单支付表
     * @param $order_id
     * @return Response
     */
    public function addPay($order_id){
        return success(( new OrderService() )->addPay($order_id));
    }

    /**
     * 查询优惠券
     * @return Response
     */
    public function getCoupon(){
        $data = $this->request->params([
            ['order_id', []],
        ]);
        return success('SUCCESS', (new OrderService())->getCoupon($data));
    }

    /**
     * 使用优惠券
     * @return Response
     */
    public function useCoupon(){
        $data = $this->request->params([
            ['order_id', ''],
            ['coupon_member_id', ''],
        ]);
        return success('SUCCESS', (new OrderService())->useCoupon($data));
    }

    
    
}
