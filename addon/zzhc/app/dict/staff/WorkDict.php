<?php

namespace addon\zzhc\app\dict\staff;

/**
 *员工考勤相关枚举类
 */
class WorkDict
{

    // 考勤状态
    const WORKING = 'working'; // 上班中
    const MEAL = 'meal'; // 用餐中
    const THING = 'thing'; // 处理事情中
    const STOP = 'stop'; // 停止接单
    const REST = 'rest'; // 下班休息

    /**
     * 获取考勤状态
     * @param string $role
     * @return array|array[]|string
     */
    public static function getStatus(string $status = '')
    {
        $data = [
            self::WORKING => get_lang('dict_staff_work.working'),
            self::MEAL => get_lang('dict_staff_work.meal'),
            self::THING => get_lang('dict_staff_work.thing'),
            self::STOP => get_lang('dict_staff_work.stop'),
            self::REST => get_lang('dict_staff_work.rest'),
        ];
        if ($status == '') {
            return $data;
        }
        return $data[$status] ?? '';
    }
}
