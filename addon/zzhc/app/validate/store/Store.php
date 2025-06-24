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

namespace addon\zzhc\app\validate\store;
use core\base\BaseValidate;
/**
 * 门店验证器
 * Class Store
 * @package addon\zzhc\app\validate\store
 */
class Store extends BaseValidate
{

       protected $rule = [
            'store_logo' => 'require',
            'store_name' => 'require',
            'trade_time' => 'require',
            'store_contacts' => 'require',
            'store_mobile' => 'require',
            'store_image' => 'require',
            'store_content' => 'require',
            'province_name' => 'require',
            'city_name' => 'require',
            'district_name' => 'require',
            'address' => 'require',
            'full_address' => 'require',
            'longitude' => 'require',
            'latitude' => 'require'
        ];

       protected $message = [
            'store_logo.require' => ['common_validate.require', ['store_logo']],
            'store_name.require' => ['common_validate.require', ['store_name']],
            'trade_time.require' => ['common_validate.require', ['trade_time']],
            'store_contacts.require' => ['common_validate.require', ['store_contacts']],
            'store_mobile.require' => ['common_validate.require', ['store_mobile']],
            'store_image.require' => ['common_validate.require', ['store_image']],
            'store_content.require' => ['common_validate.require', ['store_content']],
            'province_name.require' => ['common_validate.require', ['province_name']],
            'city_name.require' => ['common_validate.require', ['city_name']],
            'district_name.require' => ['common_validate.require', ['district_name']],
            'address.require' => ['common_validate.require', ['address']],
            'full_address.require' => ['common_validate.location', ['full_address']],
            'longitude.require' => ['common_validate.location', ['longitude']],
            'latitude.require' => ['common_validate.location', ['latitude']]
        ];

       protected $scene = [
            "add" => ['store_logo', 'store_name', 'trade_time', 'store_contacts', 'store_mobile', 'store_image', 'store_content', 'province_name', 'city_name', 'district_name', 'address', 'full_address', 'longitude', 'latitude'],
            "edit" => ['store_logo', 'store_name', 'trade_time', 'store_contacts', 'store_mobile', 'store_image', 'store_content']
        ];

}
