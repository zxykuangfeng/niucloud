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

namespace addon\zzhc\app\validate\order;
use core\base\BaseValidate;
/**
 * 预约订单验证器
 * Class Order
 * @package addon\zzhc\app\validate\order
 */
class Order extends BaseValidate
{

       protected $rule = [
            'store_id' => 'require',
            'order_no' => 'require',
            'order_type' => 'require',
            'order_from' => 'require',
            'member_id' => 'require',
            'nickname' => 'require',
            'headimg' => 'require',
            'staff_id' => 'require',
            'staff_headimg' => 'require',
            'staff_name' => 'require',
            'goods_id' => 'require',
            'goods_name' => 'require',
            'duration' => 'require',
            'goods_money' => 'require',
            'goods_vip_money' => 'require',
            'discount_money' => 'require',
            'order_money' => 'require',
            'pay_time' => 'require',
            'out_trade_no' => 'require',
            'status' => 'require',
            'ip' => 'require'
        ];

       protected $message = [
            'store_id.require' => ['common_validate.require', ['store_id']],
            'order_no.require' => ['common_validate.require', ['order_no']],
            'order_type.require' => ['common_validate.require', ['order_type']],
            'order_from.require' => ['common_validate.require', ['order_from']],
            'member_id.require' => ['common_validate.require', ['member_id']],
            'nickname.require' => ['common_validate.require', ['nickname']],
            'headimg.require' => ['common_validate.require', ['headimg']],
            'staff_id.require' => ['common_validate.require', ['staff_id']],
            'staff_headimg.require' => ['common_validate.require', ['staff_headimg']],
            'staff_name.require' => ['common_validate.require', ['staff_name']],
            'goods_id.require' => ['common_validate.require', ['goods_id']],
            'goods_name.require' => ['common_validate.require', ['goods_name']],
            'duration.require' => ['common_validate.require', ['duration']],
            'goods_money.require' => ['common_validate.require', ['goods_money']],
            'goods_vip_money.require' => ['common_validate.require', ['goods_vip_money']],
            'discount_money.require' => ['common_validate.require', ['discount_money']],
            'order_money.require' => ['common_validate.require', ['order_money']],
            'pay_time.require' => ['common_validate.require', ['pay_time']],
            'out_trade_no.require' => ['common_validate.require', ['out_trade_no']],
            'status.require' => ['common_validate.require', ['status']],
            'ip.require' => ['common_validate.require', ['ip']]
        ];

       protected $scene = [
            "add" => ['store_id', 'order_no', 'order_type', 'order_from', 'member_id', 'nickname', 'headimg', 'mobile', 'staff_id', 'staff_headimg', 'staff_name', 'goods_id', 'goods_name', 'duration', 'goods_money', 'goods_vip_money', 'discount_money', 'order_money', 'pay_time', 'out_trade_no', 'status', 'ip'],
            "edit" => ['store_id', 'order_no', 'order_type', 'order_from', 'member_id', 'nickname', 'headimg', 'mobile', 'staff_id', 'staff_headimg', 'staff_name', 'goods_id', 'goods_name', 'duration', 'goods_money', 'goods_vip_money', 'discount_money', 'order_money', 'pay_time', 'out_trade_no', 'status', 'ip']
        ];

}
