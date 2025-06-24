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
 * VIP会员操作日志验证器
 * Class Log
 * @package addon\zzhc\app\validate\vip
 */
class Log extends BaseValidate
{

       protected $rule = [
            'vip_member_id' => 'require',
            'main_type' => 'require',
            'main_id' => 'require',
            'type' => 'require'
        ];

       protected $message = [
            'vip_member_id.require' => ['common_validate.require', ['vip_member_id']],
            'main_type.require' => ['common_validate.require', ['main_type']],
            'main_id.require' => ['common_validate.require', ['main_id']],
            'type.require' => ['common_validate.require', ['type']]
        ];

       protected $scene = [
            "add" => ['vip_member_id', 'main_type', 'main_id', 'days', 'type', 'content'],
            "edit" => ['vip_member_id', 'main_type', 'main_id', 'days', 'type', 'content']
        ];

}
