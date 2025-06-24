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

namespace addon\zzhc\app\api\controller\merchant;

use core\base\BaseApiController;
use addon\zzhc\app\service\api\merchant\BarberService;

/**
 * 发型师端控制器
 */
class Barber extends BaseApiController
{

    protected $barberService = null;

    public function initialize()
	{
        $data = $this->request->params([
            ["store_id",""],
        ]);
        $this->barberService = new BarberService($data['store_id']);
        $this->barberService->checkAuth();
	}

    /**
     * 发型师中心
     */
    public function info(){
        return success($this->barberService->getInfo());
    }

    /**
     * 预约订单列表
     */
    public function order(){
        $data = $this->request->params([
            ["status",""],
        ]);
       return success($this->barberService->getOrderPage($data));
    }
   
    /**
     * 预约订单详情
     */
    public function orderInfo(int $order_id){
        return success($this->barberService->getOrderInfo($order_id));
    }

    /**
     * 取消订单
     */
    public function orderCancel($order_id){
        return success($this->barberService->orderCancel($order_id));
    }

    /**
     * 开始服务
     */
    public function orderService($order_id){
        return success($this->barberService->orderService($order_id));
    }

    /**
     * 退回排队
     */
    public function orderRevert($order_id){
        return success($this->barberService->orderRevert($order_id));
    }
    
    /**
     * 完成服务
     */
    public function orderFinish($order_id){
        return success($this->barberService->orderFinish($order_id));
    }

    /**
     * 添加考勤打卡
     */
    public function addWork(){
        $data = $this->request->params([
            ["status",""],
            ["duration",""],
            ["full_address",""],
            ["longitude",""],
            ["latitude",""],
        ]);
        return success($this->barberService->addWork($data));
    }


    /**
     * 月打卡数据
     */
    public function getWorkInfo($year, $month)
    {
        return success($this->barberService->getWorkInfo($year, $month));
    }
    
    /**
     *日打卡数据
     */
    public function getWorkDate($date)
    {
        return success($this->barberService->getWorkDate($date));
    }

}
