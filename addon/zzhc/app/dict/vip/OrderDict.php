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

namespace addon\zzhc\app\dict\vip;

use app\dict\pay\PayDict;

/**
 *订单相关枚举类
 */
class OrderDict
{
    //订单状态
    const WAIT_PAY = 1; //待支付
    const FINISH = 2; //已支付

    //当前订单支持的支付方式
    const ALLOW_PAY = [
        PayDict::WECHATPAY,
        PayDict::ALIPAY,
        PayDict::OFFLINEPAY,
    ];

    //订单类型
    const TYPE = 'zzhc_vip';   

    /**
     * 订单类型以及名称
     * @return array
     */
    public static function getOrderType()
    {
        return [
            'type' => self::TYPE,
            'name' => '开通会员卡'
        ];
    }

    /**
     * 订单状态
     * @param string $type
     * @return array|mixed|string
     */
    public static function getStatus($status = '')
    {
        $data = [
            self::WAIT_PAY => [
                'name' => get_lang('dict_vip_order_status.wait_pay'), //待支付,
                'status' => self::WAIT_PAY,
            ],
            self::FINISH => [
                'name' => get_lang('dict_vip_order_status.finish'), //已支付,
                'status' => self::FINISH,
            ]

        ];
        if ($status == '') {
            return $data;
        }
        return $data[$status] ?? '';
    }

}
