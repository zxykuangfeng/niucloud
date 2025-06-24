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

namespace addon\zzhc\app\service\core\order;

use addon\zzhc\app\dict\order\OrderDict;
use addon\zzhc\app\model\order\Order;
use core\base\BaseCoreService;
use core\exception\CommonException;

/**
 * 订单取消服务层
 */
class CoreOrderService extends BaseCoreService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Order();
    }

    /**
     * 订单取消
     * @param array $data
     * @return void
     */
    public function cancel(array $data)
    {
        $orderData = $this->model->where([
            ['order_id', '=', $data['order_id']],
            ['site_id', '=', $data['site_id']]
        ])->findOrEmpty()->toArray();

        if (empty($orderData)) throw new CommonException('订单不存在');

        if ($orderData['status'] == OrderDict::CANCEL) throw new CommonException('订单已经取消');

        //取消订单
        $this->model->where([ ['order_id', '=', $orderData['order_id']] ])->update([
            'status' => OrderDict::CANCEL,
        ]);

        //订单取消后操作
        $data['order_data'] = $orderData;
        event('AfterOrderCancel', $data);

        return true;
    }

    

}
