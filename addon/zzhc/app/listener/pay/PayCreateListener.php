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

use addon\zzhc\app\dict\order\OrderDict;
use app\dict\pay\PayDict;
use core\exception\CommonException;
use addon\zzhc\app\service\api\order\OrderService;
use addon\zzhc\app\dict\vip\OrderDict as VipOrderDict;
use addon\zzhc\app\service\api\vip\OrderService as VipOrderService;

/**
 * 支付单据创建事件
 */
class PayCreateListener
{
    public function handle(array $params)
    {
        $trade_type = $params['trade_type'] ?? '';
        $trade_id = $params['trade_id'] ?? 0;
        if ($trade_type == 'zzhc_order') {
            $trade_id = $params['trade_id'];
            $orderDetail = (new OrderService())->getInfo($trade_id);
            if ($orderDetail['status'] != OrderDict::WAIT_PAY) throw new CommonException('只有待支付的订单可以支付');
            return [
                'main_type' => PayDict::MEMBER,
                'main_id' => $orderDetail['member_id'],
                'money' => $orderDetail['order_money'],
                'trade_type' => $trade_type,
                'trade_id' => $trade_id,
                'body' => get_lang("预约订单")
            ];
        }

        if ($trade_type == 'zzhc_vip') {
            $orderDetail = (new VipOrderService())->getInfo($trade_id);
            if ($orderDetail['status'] != VipOrderDict::WAIT_PAY) throw new CommonException('只有待支付的订单可以支付');
            return [
                'main_type' => PayDict::MEMBER,
                'main_id' => $orderDetail['member_id'],
                'money' => $orderDetail['order_money'],
                'trade_type' => $trade_type,
                'trade_id' => $trade_id,
                'body' => get_lang("会员卡订单")
            ];
        }

    }
}
