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
 * 优惠券验证器
 * Class Coupon
 * @package addon\zzhc\app\validate\coupon
 */
class Coupon extends BaseValidate
{

       protected $rule = [
            'name' => 'require',
            'count' => 'require',
            'money' => 'require',
            'atleast' => 'require',
            'is_show' => 'require',
            'validity_type' => 'require',
            'end_usetime' => 'require',
            'fixed_term' => 'require',
            'max_fetch' => 'require',
            'sort' => 'require'
        ];

       protected $message = [
            'name.require' => ['common_validate.require', ['name']],
            'count.require' => ['common_validate.require', ['count']],
            'money.require' => ['common_validate.require', ['money']],
            'atleast.require' => ['common_validate.require', ['atleast']],
            'is_show.require' => ['common_validate.require', ['is_show']],
            'validity_type.require' => ['common_validate.require', ['validity_type']],
            'end_usetime.require' => ['common_validate.require', ['end_usetime']],
            'fixed_term.require' => ['common_validate.require', ['fixed_term']],
            'max_fetch.require' => ['common_validate.require', ['max_fetch']],
            'sort.require' => ['common_validate.require', ['sort']]
        ];

       protected $scene = [
            "add" => ['name', 'count', 'money', 'atleast', 'is_show', 'validity_type', 'end_usetime', 'fixed_term', 'max_fetch', 'sort'],
            "edit" => ['name', 'count', 'money', 'atleast', 'is_show', 'validity_type', 'end_usetime', 'fixed_term', 'max_fetch', 'sort']
        ];

}
