<?php

return [
    'ZZHC_COMPONENT' => [
        'title' => get_lang('dict_diy.zzhc_component_type_basic'),
        'list' => [
            'ZzhcSwiper' => [
                'title' => '轮播广告',
                'icon' => 'zzhc icon-tupianlunbo',
                'path' => 'edit-zzhc-swiper',
                'support_page' => [],
                'uses' => 1,
                'sort' => 100,
                'value' => [
                    // 组件属性
                    'template' => [
                        "margin" => [
                            "top" => 15, // 上边距
                            "bottom" => 15, // 下边距
                            "both" => 15 // 左右边距
                        ],
                    ],
                    // 轮播图设置
                    'swiper' => [
                        "interval" => 5,
                        'indicatorColor' => 'rgba(255, 255, 255, 0.5)', // 未选中颜色
                        "indicatorActiveColor" => '#ffffff',
                        'indicatorStyle' => 'style-1',
                        'indicatorAlign' => 'center',
                        'swiperStyle' => 'style-2',
                        'imageHeight' => 147,
                        'topRounded' => 10,
                        'bottomRounded' => 10,
                        'list' => [
                            [
                                "imageUrl" => "",
                                "imgWidth" => 690,
                                "imgHeight" => 294,
                                "link" => [
                                    "name" => ""
                                ]
                            ]
                        ]
                    ]
                ],
            ],
            'ZzhcStoreStaff' => [
                'title' => '门店发型师',
                'icon' => 'zzhc icon-yuangongguanli',
                'path' => 'edit-zzhc-store-staff',
                'support_page' => [],
                'uses' => 1,
                'sort' => 101,
                'value' => [
                    // 组件属性
                    'template' => [
                        "margin" => [
                            "top" => 15, // 上边距
                            "bottom" => 15, // 下边距
                            "both" => 15 // 左右边距
                        ],
                    ],
                    // 门店设置
                    'store' => [
                        'source' => 'distance',
                    ],
                    // 发型师
                    'staff' => [
                        'num' => 10,
                    ]
                ],
            ],
        ]
    ],

];