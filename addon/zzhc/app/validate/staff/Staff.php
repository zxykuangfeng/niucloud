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
 * 员工验证器
 * Class Staff
 * @package addon\zzhc\app\validate\staff
 */
class Staff extends BaseValidate
{

       protected $rule = [
            'store_id' => 'require',
            'staff_headimg' => 'require',
            'staff_name' => 'require',
            'staff_mobile' => 'require|mobile',
            'staff_position' => 'require',
            'staff_experience' => 'require',
            'staff_image' => 'require',
            'staff_content' => 'require',
            'sort' => 'require',
            'status' => 'require'
        ];

       protected $message = [
            'store_id.require' => ['common_validate.require', ['store_id']],
            'staff_headimg.require' => ['common_validate.require', ['staff_headimg']],
            'staff_name.require' => ['common_validate.require', ['staff_name']],
            'staff_mobile.require' => ['common_validate.require', ['staff_mobile']],
            'staff_mobile.mobile' => ['common_validate.mobile', ['staff_mobile']],
            'staff_position.require' => ['common_validate.require', ['staff_position']],
            'staff_experience.require' => ['common_validate.require', ['staff_experience']],
            'staff_image.require' => ['common_validate.require', ['staff_image']],
            'staff_content.require' => ['common_validate.require', ['staff_content']],
            'sort.require' => ['common_validate.require', ['sort']],
            'status.require' => ['common_validate.require', ['status']],
        ];

       protected $scene = [
            "add" => ['store_id', 'staff_headimg', 'staff_name', 'staff_mobile', 'staff_position', 'staff_experience', 'staff_image', 'staff_content', 'sort', 'status'],
            "edit" => ['store_id', 'staff_headimg', 'staff_name', 'staff_mobile', 'staff_position', 'staff_experience', 'staff_image', 'staff_content', 'sort', 'status']
        ];

}
