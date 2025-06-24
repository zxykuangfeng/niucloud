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
 * 办卡会员验证器
 * Class Member
 * @package addon\zzhc\app\validate\vip
 */
class Member extends BaseValidate
{

       protected $rule = [
            'member_id' => 'require',
            'headimg' => 'require',
            'nickname' => 'require',
            'mobile' => 'require',
            'overdue_time' => 'require'
        ];

       protected $message = [
            'member_id.require' => ['common_validate.require', ['member_id']],
            'headimg.require' => ['common_validate.require', ['headimg']],
            'nickname.require' => ['common_validate.require', ['nickname']],
            'mobile.require' => ['common_validate.require', ['mobile']],
            'overdue_time.require' => ['common_validate.require', ['overdue_time']]
        ];

       protected $scene = [
            "add" => ['member_id', 'headimg', 'nickname', 'mobile', 'overdue_time'],
            "edit" => ['member_id', 'headimg', 'nickname', 'mobile', 'overdue_time']
        ];

}
