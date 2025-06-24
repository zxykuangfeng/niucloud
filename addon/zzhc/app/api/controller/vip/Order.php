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

namespace addon\zzhc\app\api\controller\vip;

use core\base\BaseApiController;
use addon\zzhc\app\service\api\vip\OrderService;
use addon\zzhc\app\dict\vip\OrderDict;


/**
 * VIP订单控制器
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
     * VIP订单详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $order_id){
        return success((new OrderService())->getInfo($order_id));
    }

    /**
     * 创建VIP订单
     * @return \think\Response
     */
    public function create(){
        $data = $this->request->params([
             ["vip_id",0],
        ]);
        return success(( new OrderService() )->createOrder($data));
    }
    
}
