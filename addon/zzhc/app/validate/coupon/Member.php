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

namespace addon\zzhc\app\validate\coupon;
use core\base\BaseValidate;
/**
 * 领券记录验证器
 * Class Member
 * @package addon\zzhc\app\validate\coupon
 */
class Member extends BaseValidate
{

       protected $rule = [
            'coupon_id' => 'require',
            'name' => 'require',
            'member_id' => 'require',
            'expire_time' => 'require',
            'use_time' => 'require',
            'money' => 'require',
            'atleast' => 'require',
            'receive_type' => 'require',
            'status' => 'require'
        ];

       protected $message = [
            'coupon_id.require' => ['common_validate.require', ['coupon_id']],
            'name.require' => ['common_validate.require', ['name']],
            'member_id.require' => ['common_validate.require', ['member_id']],
            'expire_time.require' => ['common_validate.require', ['expire_time']],
            'use_time.require' => ['common_validate.require', ['use_time']],
            'money.require' => ['common_validate.require', ['money']],
            'atleast.require' => ['common_validate.require', ['atleast']],
            'receive_type.require' => ['common_validate.require', ['receive_type']],
            'status.require' => ['common_validate.require', ['status']]
        ];

       protected $scene = [
            "add" => ['coupon_id', 'name', 'member_id', 'expire_time', 'use_time', 'money', 'atleast', 'receive_type', 'status'],
            "edit" => ['coupon_id', 'name', 'member_id', 'expire_time', 'use_time', 'money', 'atleast', 'receive_type', 'status']
        ];

}
