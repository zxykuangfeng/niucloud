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

namespace addon\zzhc\app\validate\order;
use core\base\BaseValidate;
/**
 * 订单操作日志验证器
 * Class OrderLog
 * @package addon\zzhc\app\validate\order
 */
class OrderLog extends BaseValidate
{

       protected $rule = [
            'main_type' => 'require',
            'main_id' => 'require',
            'type' => 'require'
        ];

       protected $message = [
            'main_type.require' => ['common_validate.require', ['main_type']],
            'main_id.require' => ['common_validate.require', ['main_id']],
            'type.require' => ['common_validate.require', ['type']]
        ];

       protected $scene = [
            "add" => ['order_id', 'main_type', 'main_id', 'status', 'type', 'content'],
            "edit" => ['main_type', 'main_id', 'status', 'type', 'content']
        ];

}
