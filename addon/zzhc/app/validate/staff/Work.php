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

namespace addon\zzhc\app\validate\staff;
use core\base\BaseValidate;
/**
 * 考勤管理验证器
 * Class Work
 * @package addon\zzhc\app\validate\staff
 */
class Work extends BaseValidate
{

       protected $rule = [
            'store_id' => 'require',
            'staff_id' => 'require',
            'work_status' => 'require'
        ];

       protected $message = [
            'store_id.require' => ['common_validate.require', ['store_id']],
            'staff_id.require' => ['common_validate.require', ['staff_id']],
            'work_status.require' => ['common_validate.require', ['work_status']]
        ];

       protected $scene = [
            "add" => ['store_id', 'staff_id', 'work_status'],
            "edit" => ['store_id', 'staff_id', 'work_status', 'exp_duration']
        ];

}
