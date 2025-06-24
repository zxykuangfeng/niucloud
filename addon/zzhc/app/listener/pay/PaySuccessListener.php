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

namespace addon\zzhc\app\listener\pay;
use addon\zzhc\app\service\api\order\OrderService;
use addon\zzhc\app\service\api\vip\OrderService as VipOrderService;

/**
 * 支付异步回调事件
 */
class PaySuccessListener
{
    public function handle(array $pay_info)
    {
        $trade_type = $pay_info['trade_type'] ?? '';
        if ($trade_type == 'zzhc_order') {
            (new OrderService())->pay($pay_info);
        }
        if ($trade_type == 'zzhc_vip') {
            (new VipOrderService())->pay($pay_info);
        }
    }
}
