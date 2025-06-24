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
use addon\zzhc\app\service\api\merchant\ManageService;

/**
 * 店长端控制器
 */
class Manage extends BaseApiController
{

    protected $manageService = null;

    public function initialize()
	{
        $data = $this->request->params([
            ["store_id",""],
        ]);
        $this->manageService = new ManageService($data['store_id']);
        $this->manageService->checkAuth();
	}

    /**
     * 店长中心
     */
    public function info(){
        return success($this->manageService->getInfo());
    }

    /**
     * 预约订单列表
     */
    public function order(){
        $data = $this->request->params([
            ["status",""],
        ]);
       return success($this->manageService->getOrderPage($data));
    }

    /**
     * 取消订单
     */
    public function orderCancel($order_id){
        return success($this->manageService->orderCancel($order_id));
    }

    /**
     * 预约订单详情
     */
    public function orderInfo(int $order_id){
        return success($this->manageService->getOrderInfo($order_id));
    }

    /**
     *打卡数据
     */
    public function getWrokList($date)
    {
        return success($this->manageService->getWorkList($date));
    }
    

}
