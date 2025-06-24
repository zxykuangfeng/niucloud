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
 * 项目验证器
 * Class Goods
 * @package addon\zzhc\app\validate\goods
 */
class Goods extends BaseValidate
{

       protected $rule = [
            'category_id' => 'require',
            'goods_name' => 'require',
            'goods_image' => 'require',
            'duration' => 'require',
            'price' => 'require',
            'sort' => 'require',
            'status' => 'require'
        ];

       protected $message = [
            'category_id.require' => ['common_validate.require', ['category_id']],
            'goods_name.require' => ['common_validate.require', ['goods_name']],
            'goods_image.require' => ['common_validate.require', ['goods_image']],
            'duration.require' => ['common_validate.require', ['duration']],
            'price.require' => ['common_validate.require', ['price']],
            'sort.require' => ['common_validate.require', ['sort']],
            'status.require' => ['common_validate.require', ['status']]
        ];

       protected $scene = [
            "add" => ['category_id', 'goods_name', 'goods_image', 'duration', 'price', 'sort', 'status'],
            "edit" => ['category_id', 'goods_name', 'goods_image', 'duration', 'price', 'sort', 'status']
        ];

}
