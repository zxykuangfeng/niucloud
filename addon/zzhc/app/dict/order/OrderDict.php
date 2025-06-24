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

namespace addon\zzhc\app\dict\order;

use app\dict\pay\PayDict;

/**
 *订单相关枚举类
 */
class OrderDict
{
    //订单状态
    const CANCEL = -1; //已取消
    const WAIT_SERVICE = 1; //待服务
    const IN_SERVICE = 2; //服务中
    const WAIT_PAY = 3; //待支付
    const FINISH = 5; //已完成

    //当前订单支持的支付方式
    const ALLOW_PAY = [
        PayDict::WECHATPAY,
        PayDict::ALIPAY,
        PayDict::OFFLINEPAY,
    ];

    //订单类型
    const TYPE = 'zzhc_order';   

    //订单操作类型
    const ORDER_CANCEL_ACTION = 'cancel';//取消
    const ORDER_SERVICE_ACTION = 'service';//开始服务
    const ORDER_REVERT_ACTION = 'revert';//退回排队
    const ORDER_FINISH_ACTION = 'finish'; //完成服务
    const ORDER_PAY_ACTION = 'pay'; //支付

    /**
     * 订单类型以及名称
     * @return array
     */
    public static function getOrderType()
    {
        return [
            'type' => self::TYPE,
            'name' => '预约订单'
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
            self::CANCEL => [
                'name' => get_lang('dict_order_status.cancel'), // 已取消
                'status' => self::CANCEL,
            ],
            self::WAIT_SERVICE => [
                'name' => get_lang('dict_order_status.wait_service'), //待服务
                'status' => self::WAIT_SERVICE,
            ],
            self::IN_SERVICE => [
                'name' => get_lang('dict_order_status.in_service'), //服务中
                'status' => self::IN_SERVICE,
            ],
            self::WAIT_PAY => [
                'name' => get_lang('dict_order_status.wait_pay'), //待支付,
                'status' => self::WAIT_PAY,
            ],
            self::FINISH => [
                'name' => get_lang('dict_order_status.finish'), //已完成,
                'status' => self::FINISH,
            ]

        ];
        if ($status == '') {
            return $data;
        }
        return $data[$status] ?? '';
    }

    /**
     * 订单操作类型
     * @param string $type
     * @return array|mixed|string
     */
    public static function getActionType(string $type = '')
    {
        $data = [
            self::ORDER_CANCEL_ACTION => get_lang('dict_order_action.cancel'), //订单取消
            self::ORDER_SERVICE_ACTION => get_lang('dict_order_action.service'), //开始服务
            self::ORDER_REVERT_ACTION => get_lang('dict_order_action.revert'), //退回排队
            self::ORDER_FINISH_ACTION => get_lang('dict_order_action.finish'), //完成服务
            self::ORDER_PAY_ACTION => get_lang('dict_order_action.pay'), //订单支付
        ];
        
        if (!$type) {
            return $data;
        }
        return $data[$type] ?? '';
    }

}
