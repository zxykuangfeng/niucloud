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

namespace addon\zzhc\app\dict\order;


/**
 * 订单日志枚举类
 */
class OrderLogDict
{

    //操作人类型
    const MEMBER = 'member'; //会员
    const BARBER = 'barber'; //发型师
    const MANAGE = 'manage'; //店长
    const SYSTEM = 'system';//系统


    /**
     * 获取操作人类型
     * @param string $status
     * @return array|array[]|string
     */
    public static function getMainType(string $type = '')
    {
        $data = [
            self::MEMBER => get_lang('dict_order_log.member'),
            self::BARBER => get_lang('dict_order_log.barber'),
            self::MANAGE => get_lang('dict_order_log.manage'),
            self::SYSTEM => get_lang('dict_order_log.system'),
        ];
        if (!$type) {
            return $data;
        }
        return $data[$type] ?? '';
    }

}
