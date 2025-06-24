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

namespace addon\zzhc\app\validate\vip;
use core\base\BaseValidate;
/**
 * 办卡订单验证器
 * Class Order
 * @package addon\zzhc\app\validate\vip
 */
class Order extends BaseValidate
{

       protected $rule = [
            'order_no' => 'require',
            'order_type' => 'require',
            'order_from' => 'require',
            'member_id' => 'require',
            'nickname' => 'require',
            'headimg' => 'require',
            'vip_id' => 'require',
            'vip_name' => 'require',
            'days' => 'require',
            'vip_money' => 'require',
            'order_money' => 'require',
            'pay_time' => 'require',
            'out_trade_no' => 'require',
            'status' => 'require',
            'ip' => 'require'
        ];

       protected $message = [
            'order_no.require' => ['common_validate.require', ['order_no']],
            'order_type.require' => ['common_validate.require', ['order_type']],
            'order_from.require' => ['common_validate.require', ['order_from']],
            'member_id.require' => ['common_validate.require', ['member_id']],
            'nickname.require' => ['common_validate.require', ['nickname']],
            'headimg.require' => ['common_validate.require', ['headimg']],
            'vip_id.require' => ['common_validate.require', ['vip_id']],
            'vip_name.require' => ['common_validate.require', ['vip_name']],
            'days.require' => ['common_validate.require', ['days']],
            'vip_money.require' => ['common_validate.require', ['vip_money']],
            'order_money.require' => ['common_validate.require', ['order_money']],
            'pay_time.require' => ['common_validate.require', ['pay_time']],
            'out_trade_no.require' => ['common_validate.require', ['out_trade_no']],
            'status.require' => ['common_validate.require', ['status']],
            'ip.require' => ['common_validate.require', ['ip']]
        ];

       protected $scene = [
            "add" => ['order_no', 'order_type', 'order_from', 'member_id', 'nickname', 'headimg', 'mobile', 'vip_id', 'vip_name', 'days', 'vip_money', 'order_money', 'pay_time', 'out_trade_no', 'status', 'ip'],
            "edit" => ['order_no', 'order_type', 'order_from', 'member_id', 'nickname', 'headimg', 'mobile', 'vip_id', 'vip_name', 'days', 'vip_money', 'order_money', 'pay_time', 'out_trade_no', 'status', 'ip']
        ];

}
