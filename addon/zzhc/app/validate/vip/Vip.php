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
 * VIP套餐验证器
 * Class Vip
 * @package addon\zzhc\app\validate\vip
 */
class Vip extends BaseValidate
{

       protected $rule = [
            'vip_name' => 'require',
            'days' => 'require',
            'price' => 'require',
            'sort' => 'require',
            'status' => 'require'
        ];

       protected $message = [
            'vip_name.require' => ['common_validate.require', ['vip_name']],
            'days.require' => ['common_validate.require', ['days']],
            'price.require' => ['common_validate.require', ['price']],
            'sort.require' => ['common_validate.require', ['sort']],
            'status.require' => ['common_validate.require', ['status']]
        ];

       protected $scene = [
            "add" => ['vip_name', 'days', 'price', 'sort', 'status'],
            "edit" => ['vip_name', 'days', 'price', 'sort', 'status']
        ];

}
