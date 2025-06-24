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

namespace addon\zzhc\app\dict\vip;


/**
 * VIP会员日志枚举类
 */
class LogDict
{

    //操作人类型
    const MEMBER = 'member'; //会员
    const SYSTEM = 'system';//系统


    //操作类型
    const ADD = 'add';//办卡
    const CHANGE = 'change';//延期

    /**
     * 获取操作人类型
     * @param string $status
     * @return array|array[]|string
     */
    public static function getMainType(string $type = '')
    {
        $data = [
            self::MEMBER => get_lang('dict_order_log.member'),
            self::SYSTEM => get_lang('dict_order_log.system'),
        ];
        if (!$type) {
            return $data;
        }
        return $data[$type] ?? '';
    }

    /**
     * 操作类型
     * @param string $type
     * @return array|mixed|string
     */
    public static function getActionType(string $type = '')
    {
        $data = [
            self::ADD => get_lang('dict_vip_order_action.ADD'),
            self::CHANGE => get_lang('dict_vip_order_action.CHANGE'),
        ];
        
        if (!$type) {
            return $data;
        }
        return $data[$type] ?? '';
    }

}
