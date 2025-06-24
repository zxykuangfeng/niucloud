<?php

return [
    'ZZHC_LINK' => [
        'key' => 'zzhc',
        'addon_title' => get_lang('dict_diy.zzhc_title'),
        'title' => get_lang('dict_diy.page_link'),
        'child_list' => [
            [
                'name' => 'ZZHC_INDEX',
                'title' => get_lang('dict_diy.page_link_index'),
                'url' => '/addon/zzhc/pages/index',
                'is_share' => 1,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_STAFF_LIST',
                'title' => get_lang('dict_diy.page_link_staff_list'),
                'url' => '/addon/zzhc/pages/staff/list',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_ORDER_LIST',
                'title' => get_lang('dict_diy.page_link_order_list'),
                'url' => '/addon/zzhc/pages/order/list',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_MEMBER_INDEX',
                'title' => get_lang('dict_diy.page_link_member_index'),
                'url' => '/addon/zzhc/pages/member/index',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_MEMBER_COUPON',
                'title' => get_lang('dict_diy.page_link_member_coupon'),
                'url' => '/addon/zzhc/pages/member/coupon',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_MEMBER_VIP',
                'title' => get_lang('dict_diy.page_link_member_vip'),
                'url' => '/addon/zzhc/pages/member/vip',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_COUPON_LIST',
                'title' => get_lang('dict_diy.page_link_coupon_list'),
                'url' => '/addon/zzhc/pages/coupon/list',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_VIP_BUY',
                'title' => get_lang('dict_diy.page_link_vip_buy'),
                'url' => '/addon/zzhc/pages/vip/buy',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_VIP_BUY',
                'title' => get_lang('dict_diy.page_link_vip_buy'),
                'url' => '/addon/zzhc/pages/vip/buy',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_MERCHANT_MANAGE_INDEX',
                'title' => get_lang('dict_diy.page_link_merchant_manage_index'),
                'url' => '/addon/zzhc/pages/merchant/manage/index',
                'is_share' => 0,
                'action' => 'decorate'
            ],
            [
                'name' => 'ZZHC_MERCHANT_BARBER_INDEX',
                'title' => get_lang('dict_diy.page_link_merchant_barber_index'),
                'url' => '/addon/zzhc/pages/merchant/barber/index',
                'is_share' => 0,
                'action' => 'decorate'
            ],
        ]
    ],
];
