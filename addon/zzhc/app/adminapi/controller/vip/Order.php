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

namespace addon\zzhc\app\adminapi\controller\vip;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\vip\OrderService;


/**
 * 办卡订单控制器
 * Class Order
 * @package addon\zzhc\app\adminapi\controller\vip
 */
class Order extends BaseAdminController
{
   /**
    * 获取办卡订单列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["order_no",""],
             ["nickname",""],
             ["mobile",""],
             ["out_trade_no",""],
             ["status",""],
             ["create_time",["",""]]
        ]);
        return success((new OrderService())->getPage($data));
    }

    /**
     * 办卡订单详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new OrderService())->getInfo($id));
    }

    /**
     * 添加办卡订单
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["order_no",""],
             ["order_type",""],
             ["order_from",""],
             ["member_id",0],
             ["nickname",""],
             ["headimg",""],
             ["mobile",""],
             ["vip_id",0],
             ["vip_name",""],
             ["days",0],
             ["vip_money",0.00],
             ["order_money",0.00],
             ["pay_time",0],
             ["out_trade_no",""],
             ["status",0],
             ["ip",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\vip\Order.add');
        $id = (new OrderService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 办卡订单编辑
     * @param $id  办卡订单id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["order_no",""],
             ["order_type",""],
             ["order_from",""],
             ["member_id",0],
             ["nickname",""],
             ["headimg",""],
             ["mobile",""],
             ["vip_id",0],
             ["vip_name",""],
             ["days",0],
             ["vip_money",0.00],
             ["order_money",0.00],
             ["pay_time",0],
             ["out_trade_no",""],
             ["status",0],
             ["ip",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\vip\Order.edit');
        (new OrderService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 办卡订单删除
     * @param $id  办卡订单id
     * @return \think\Response
     */
    public function del(int $id){
        (new OrderService())->del($id);
        return success('DELETE_SUCCESS');
    }

    
}
