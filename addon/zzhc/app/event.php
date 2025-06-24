<?php

return [
    'bind' => [

    ],
    'listen' => [

        //支付
        'PayCreate' => [ 'addon\zzhc\app\listener\pay\PayCreateListener' ],
        'PaySuccess' => [ 'addon\zzhc\app\listener\pay\PaySuccessListener' ],

        //订单取消后
        'AfterOrderCancel' => [ 'addon\zzhc\app\listener\order\AfterOrderCancel' ],

        //底部导航
        'BottomNavigation' => [ 'addon\zzhc\app\listener\BottomNavigationListener' ],

        //主题色
        'ThemeColor' => [ 'addon\zzhc\app\listener\diy\ThemeColorListener' ],
    ],
    'subscribe' => [
    ],
];