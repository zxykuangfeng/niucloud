<?php

return [
    'dict_diy' => [
        'zzhc_title'    => '理发店预约系统',
        'zzhc_component_type_basic'     => '理发店预约组件',
        'page_link'     => '理发店预约链接',
        'page_link_index'   => '首页',
        'page_link_staff_list' => '取号',
        'page_link_order_list' => '订单',
        'page_link_member_index'    => '我的',
        'page_link_member_coupon'    => '我的优惠券',
        'page_link_member_vip'    => '我的会员卡',
        'page_link_vip_buy'    => '开通会员卡',
        'page_link_coupon_list'    => '优惠券列表',
        'page_link_merchant_barber_index' => '发型师端',
        'page_link_merchant_manage_index' => '店长端'
    ],
    'dict_staff_role' => [
        'clerk'    => '职员',
        'barber'    => '发型师',
        'manager'    => '店长',
    ],
    'dict_order_log' => [
        'member' => '会员',
        'barber' => '发型师',
        'manage' => '店长',
        'system' => '系统'
    ],
    'dict_order_status' => [
        'cancel' => '已取消',
        'wait_service' => '待服务',
        'in_service' => '服务中',
        'wait_pay' => '待支付',
        'finish' => '已完成'
    ],
    'dict_vip_order_status' => [
        'wait_pay' => '待支付',
        'finish' => '已支付'
    ],
    'dict_vip_order_action' => [
        'add' => '办卡',
        'change' => '延期'
    ],
    'dict_order_action' => [
        'cancel'  => '订单取消',
        'service' => '开始服务',
        'revert'  => '退回排队',
        'finish'  => '完成服务',
        'pay'     => '订单支付',
    ],
    'dict_staff_work' => [
        'working' => '上班',
        'meal'    => '用餐',
        'thing'    => '有事',
        'stop'    => '停止',
        'rest'    => '下班',
    ],

];