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

namespace addon\zzhc\app\validate\goods;
use core\base\BaseValidate;
/**
 * 项目分类验证器
 * Class Category
 * @package addon\zzhc\app\validate\goods
 */
class Category extends BaseValidate
{

       protected $rule = [
            'category_name' => 'require',
            'sort' => 'require',
            'status' => 'require'
        ];

       protected $message = [
            'category_name.require' => ['common_validate.require', ['category_name']],
            'sort.require' => ['common_validate.require', ['sort']],
            'status.require' => ['common_validate.require', ['status']]
        ];

       protected $scene = [
            "add" => ['category_name', 'sort', 'status'],
            "edit" => ['category_name', 'sort', 'status']
        ];

}
