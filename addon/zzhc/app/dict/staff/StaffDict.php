<?php

namespace addon\zzhc\app\dict\staff;

/**
 *员工相关枚举类
 */
class StaffDict
{

    // 角色
    const CLERK = 'clerk'; // 职员
    const BARBER = 'barber'; //发型师
    const MANAGER = 'manager'; //店长

    /**
     * 获取角色
     * @param string $role
     * @return array|array[]|string
     */
    public static function getRole(string $role = '')
    {
        $data = [
            self::CLERK => get_lang('dict_staff_role.clerk'),
            self::BARBER => get_lang('dict_staff_role.barber'),
            self::MANAGER => get_lang('dict_staff_role.manager'),
        ];
        if ($role == '') {
            return $data;
        }
        return $data[$role] ?? '';
    }
}
