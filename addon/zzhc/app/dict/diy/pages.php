<?php

return [
    'DIY_ZZHC_INDEX' => [
        'zzhc_index' => [ // 页面标识
            "title" => "理发店预约系统首页", // 页面名称
            'cover' => 'addon/zzhc/diy/index/cover.jpg', // 页面封面图
            'preview' => '', // 页面预览图
            'desc' => '', // 页面描述
            'mode' => 'diy', // 页面模式：diy：自定义，fixed：固定
            // 页面数据源
            "data" => [
                "global"=>[
                    "title"=>"首页",
                    "pageStartBgColor"=>"rgba(255, 0, 0, 0.03)",
                    "pageEndBgColor"=>null,
                    "pageGradientAngle"=>"to bottom",
                    "bgUrl"=>"addon/zzhc/diy/index/bg.png",
                    "bgHeightScale"=>0,
                    "imgWidth"=>750,
                    "imgHeight"=>348,
                    "topStatusBar"=>[
                        "isShow"=>true,
                        "bgColor"=>null,
                        "rollBgColor"=>"rgba(255, 255, 255, 1)",
                        "style"=>"style-1",
                        "styleName"=>"风格1",
                        "textColor"=>"rgba(255, 255, 255, 1)",
                        "rollTextColor"=>"#333333",
                        "textAlign"=>"center",
                        "inputPlaceholder"=>"请输入搜索关键词",
                        "imgUrl"=>"",
                        "link"=>[
                            "name"=>""
                        ]
                    ],
                    "bottomTabBarSwitch"=>true,
                    "popWindow"=>[
                        "imgUrl"=>"",
                        "imgWidth"=>"",
                        "imgHeight"=>"",
                        "count"=>-1,
                        "show"=>0,
                        "link"=>[
                            "name"=>""
                        ]
                    ],
                    "template"=>[
                        "textColor"=>"#303133",
                        "pageStartBgColor"=>"",
                        "pageEndBgColor"=>"",
                        "pageGradientAngle"=>"to bottom",
                        "componentBgUrl"=>"",
                        "componentBgAlpha"=>2,
                        "componentStartBgColor"=>"",
                        "componentEndBgColor"=>"",
                        "componentGradientAngle"=>"to bottom",
                        "topRounded"=>0,
                        "bottomRounded"=>0,
                        "elementBgColor"=>"",
                        "topElementRounded"=>0,
                        "bottomElementRounded"=>0,
                        "margin"=>[
                            "top"=>0,
                            "bottom"=>0,
                            "both"=>0
                        ]
                    ]
                ],
                "value"=>[
                    [
                        "path"=>"edit-zzhc-swiper",
                        "uses"=>1,
                        "id"=>"q8vlef53bsw",
                        "componentName"=>"ZzhcSwiper",
                        "componentTitle"=>"轮播广告",
                        "ignore"=>[
                            "componentBgColor",
                            "componentBgUrl",
                            "topRounded",
                            "bottomRounded",
                            "pageBgColor"
                        ],
                        "swiper"=>[
                            "interval"=>5,
                            "indicatorColor"=>"rgba(255, 255, 255, 0.5)",
                            "indicatorActiveColor"=>"#ffffff",
                            "indicatorStyle"=>"style-1",
                            "indicatorAlign"=>"center",
                            "swiperStyle"=>"style-2",
                            "imageHeight"=>151,
                            "topRounded"=>10,
                            "bottomRounded"=>10,
                            "list"=>[
                                [
                                    "imageUrl"=>"addon/zzhc/diy/index/banner.png",
                                    "imgWidth"=>690,
                                    "imgHeight"=>294,
                                    "link"=>[
                                        "name"=>""
                                    ],
                                    "id"=>"73xho4nseys0",
                                    "width"=>355,
                                    "height"=>151
                                ],
                                [
                                    "id"=>"3mi12z1fclc0",
                                    "imageUrl"=>"addon/zzhc/diy/index/banner.png",
                                    "imgWidth"=>690,
                                    "imgHeight"=>294,
                                    "link"=>[
                                        "name"=>""
                                    ]
                                ]
                            ]
                        ],
                        "textColor"=>"#303133",
                        "pageStartBgColor"=>"",
                        "pageEndBgColor"=>"",
                        "pageGradientAngle"=>"to bottom",
                        "componentBgUrl"=>"",
                        "componentBgAlpha"=>2,
                        "componentStartBgColor"=>"",
                        "componentEndBgColor"=>"",
                        "componentGradientAngle"=>"to bottom",
                        "topRounded"=>0,
                        "bottomRounded"=>0,
                        "elementBgColor"=>"",
                        "topElementRounded"=>0,
                        "bottomElementRounded"=>0,
                        "margin"=>[
                            "top"=>15,
                            "bottom"=>15,
                            "both"=>15
                        ],
                        "pageStyle"=>"padding-top:30rpx;padding-bottom:30rpx;padding-right:30rpx;padding-left:30rpx;"
                    ],
                    [
                        "path"=>"edit-zzhc-store-staff",
                        "uses"=>1,
                        "id"=>"65z5pnzslps0",
                        "componentName"=>"ZzhcStoreStaff",
                        "componentTitle"=>"门店发型师",
                        "ignore"=>[],
                        "store"=>[
                            "source"=>"distance"
                        ],
                        "staff"=>[
                            "num"=>10
                        ],
                        "textColor"=>"#303133",
                        "pageStartBgColor"=>"",
                        "pageEndBgColor"=>"",
                        "pageGradientAngle"=>"to bottom",
                        "componentBgUrl"=>"",
                        "componentBgAlpha"=>2,
                        "componentStartBgColor"=>"",
                        "componentEndBgColor"=>"",
                        "componentGradientAngle"=>"to bottom",
                        "topRounded"=>0,
                        "bottomRounded"=>0,
                        "elementBgColor"=>"",
                        "topElementRounded"=>0,
                        "bottomElementRounded"=>0,
                        "margin"=>[
                            "top"=>0,
                            "bottom"=>15,
                            "both"=>15
                        ],
                        "pageStyle"=>"padding-top:30rpx;padding-bottom:30rpx;padding-right:30rpx;padding-left:30rpx;"
                    ],
                    [
                        "path"=>"edit-hot-area",
                        "uses"=>0,
                        "id"=>"74o6a9ienqs0",
                        "componentName"=>"HotArea",
                        "componentTitle"=>"热区",
                        "ignore"=>[],
                        "imageUrl"=>"addon/zzhc/diy/index/vip_coupon.png",
                        "imgWidth"=>345,
                        "imgHeight"=>96,
                        "heatMapData"=>[
                            [
                                "left"=>"0.00",
                                "top"=>"0.00",
                                "width"=>"68.75",
                                "height"=>"99.10",
                                "unit"=>"%",
                                "link"=>[
                                    "parent"=>"ZZHC_LINK",
                                    "name"=>"ZZHC_VIP_BUY",
                                    "title"=>"VIP会员卡",
                                    "url"=>"/addon/zzhc/pages/vip/buy",
                                    "action"=>"decorate"
                                ]
                            ],
                            [
                                "left"=>"70.75",
                                "top"=>"0.00",
                                "width"=>"29.25",
                                "height"=>"99.10",
                                "unit"=>"%",
                                "link"=>[
                                    "parent"=>"ZZHC_LINK",
                                    "name"=>"ZZHC_COUPON_LIST",
                                    "title"=>"优惠券列表",
                                    "url"=>"/addon/zzhc/pages/coupon/list",
                                    "action"=>"decorate"
                                ]
                            ]
                        ],
                        "textColor"=>"#303133",
                        "pageStartBgColor"=>"",
                        "pageEndBgColor"=>"",
                        "pageGradientAngle"=>"to bottom",
                        "componentBgUrl"=>"",
                        "componentBgAlpha"=>2,
                        "componentStartBgColor"=>"",
                        "componentEndBgColor"=>"",
                        "componentGradientAngle"=>"to bottom",
                        "topRounded"=>10,
                        "bottomRounded"=>10,
                        "elementBgColor"=>"",
                        "topElementRounded"=>0,
                        "bottomElementRounded"=>0,
                        "margin"=>[
                            "top"=>0,
                            "bottom"=>0,
                            "both"=>15
                        ],
                        "pageStyle"=>"padding-top:2rpx;padding-bottom:0rpx;padding-right:30rpx;padding-left:30rpx;"
                    ]
                ]
            ]
        ]
    ],
    'DIY_ZZHC_MEMBER_INDEX' => [
        'zzhc_member_index' => [
            "title" => "理发店预约系统个人中心", // 页面名称
            'cover' => 'addon/zzhc/diy/template/member/cover.jpg', // 页面封面图
            'preview' => '', // 页面预览图
            'desc' => '理发店预约系统个人中心', // 页面描述
            'mode' => 'diy', // 页面模式：diy：自定义，fixed：固定
            'data' => [
                "global"=> [
                    "title"=> "个人中心",
                    "pageStartBgColor"=> "#F8F8F8",
                    "pageEndBgColor"=> "",
                    "pageGradientAngle"=> "to bottom",
                    "bgUrl"=> "",
                    "bgHeightScale"=> 0,
                    "imgWidth"=> "",
                    "imgHeight"=> "",
                    "bottomTabBarSwitch"=> true,
                    "template"=> [
                        "textColor"=> "#303133",
                        "pageStartBgColor"=> "",
                        "pageEndBgColor"=> "",
                        "pageGradientAngle"=> "to bottom",
                        "componentBgUrl"=> "",
                        "componentBgAlpha"=> 2,
                        "componentStartBgColor"=> "",
                        "componentEndBgColor"=> "",
                        "componentGradientAngle"=> "to bottom",
                        "topRounded"=> 0,
                        "bottomRounded"=> 0,
                        "elementBgColor"=> "",
                        "topElementRounded"=> 0,
                        "bottomElementRounded"=> 0,
                        "margin"=> [
                            "top"=> 0,
                            "bottom"=> 0,
                            "both"=> 10
                        ]
                    ],
                    "topStatusBar"=> [
                        "isShow"=> true,
                        "bgColor"=> "#ffffff",
                        "rollBgColor"=> "#ffffff",
                        "style"=> "style-1",
                        "styleName"=> "风格1",
                        "textColor"=> "#333333",
                        "rollTextColor"=> "#333333",
                        "textAlign"=> "center",
                        "inputPlaceholder"=> "请输入搜索关键词",
                        "imgUrl"=> "",
                        "link"=> [
                            "name"=> ""
                        ]
                    ],
                    "popWindow"=> [
                        "imgUrl"=> "",
                        "imgWidth"=> "",
                        "imgHeight"=> "",
                        "count"=> -1,
                        "show"=> 0,
                        "link"=> [
                            "name"=> ""
                        ]
                    ]
                ],
                "value"=> [
                    [
                        "path"=> "edit-member-info",
                        "id"=> "67qv49qgxp00",
                        "componentName"=> "MemberInfo",
                        "componentTitle"=> "会员信息",
                        "uses"=> 0,
                        "ignore"=> [
                            "componentBgUrl"
                        ],
                        "pageStartBgColor"=> "",
                        "pageEndBgColor"=> "",
                        "pageGradientAngle"=> "to bottom",
                        "componentBgUrl"=> "",
                        "componentBgAlpha"=> 2,
                        "componentStartBgColor"=> "",
                        "componentEndBgColor"=> "",
                        "componentGradientAngle"=> "to bottom",
                        "topRounded"=> 10,
                        "bottomRounded"=> 10,
                        "elementBgColor"=> "",
                        "topElementRounded"=> 0,
                        "bottomElementRounded"=> 0,
                        "margin"=> [
                            "top"=> 15,
                            "bottom"=> 15,
                            "both"=> 15
                        ],
                        "style"=> "style-1",
                        "styleName"=> "风格1",
                        "textColor"=> "#FFFFFF",
                        "bgUrl"=> "static/resource/images/diy/member_style1_bg.png",
                        "bgColorStart"=> "",
                        "bgColorEnd"=> "",
                        "pageStyle"=> "padding-top=>30rpx;padding-bottom=>30rpx;padding-right=>30rpx;padding-left=>30rpx;"
                    ],
                    [
                        "path"=> "edit-horz-blank",
                        "uses"=> 0,
                        "id"=> "2da0xqyo8zms",
                        "componentName"=> "HorzBlank",
                        "componentTitle"=> "辅助空白",
                        "ignore"=> [
                            "pageBgColor",
                            "componentBgUrl"
                        ],
                        "height"=> 10,
                        "textColor"=> "#303133",
                        "pageStartBgColor"=> "",
                        "pageEndBgColor"=> "",
                        "pageGradientAngle"=> "to bottom",
                        "componentBgUrl"=> "",
                        "componentBgAlpha"=> 2,
                        "componentStartBgColor"=> "rgba(255, 255, 255, 1)",
                        "componentEndBgColor"=> "",
                        "componentGradientAngle"=> "to bottom",
                        "topRounded"=> 10,
                        "bottomRounded"=> 0,
                        "elementBgColor"=> "",
                        "topElementRounded"=> 0,
                        "bottomElementRounded"=> 0,
                        "margin"=> [
                            "top"=> 0,
                            "bottom"=> 0,
                            "both"=> 15
                        ],
                        "pageStyle"=> "padding-top=>2rpx;padding-bottom=>0rpx;padding-right=>30rpx;padding-left=>30rpx;"
                    ],
                    [
                        "path"=> "edit-text",
                        "uses"=> 0,
                        "position"=> "",
                        "id"=> "1puhgfus8www",
                        "componentName"=> "Text",
                        "componentTitle"=> "标题",
                        "ignore"=> [],
                        "style"=> "style-1",
                        "styleName"=> "风格1",
                        "text"=> "我的服务",
                        "link"=> [
                            "name"=> ""
                        ],
                        "textColor"=> "#303133",
                        "fontSize"=> 16,
                        "fontWeight"=> "normal",
                        "textAlign"=> "left",
                        "subTitle"=> [
                            "text"=> "",
                            "color"=> "#999999",
                            "fontSize"=> 14,
                            "control"=> false,
                            "fontWeight"=> "normal"
                        ],
                        "more"=> [
                            "text"=> "",
                            "control"=> false,
                            "isShow"=> true,
                            "link"=> [
                                "name"=> ""
                            ],
                            "color"=> "#999999"
                        ],
                        "pageStartBgColor"=> "",
                        "pageEndBgColor"=> "",
                        "pageGradientAngle"=> "to bottom",
                        "componentBgUrl"=> "",
                        "componentBgAlpha"=> 2,
                        "componentStartBgColor"=> "rgba(255, 255, 255, 1)",
                        "componentEndBgColor"=> "",
                        "componentGradientAngle"=> "to bottom",
                        "topRounded"=> 0,
                        "bottomRounded"=> 0,
                        "elementBgColor"=> "",
                        "topElementRounded"=> 0,
                        "bottomElementRounded"=> 0,
                        "margin"=> [
                            "top"=> 0,
                            "bottom"=> 0,
                            "both"=> 15
                        ],
                        "pageStyle"=> "padding-top=>2rpx;padding-bottom=>0rpx;padding-right=>30rpx;padding-left=>30rpx;"
                    ],
                    [
                        "path"=> "edit-graphic-nav",
                        "id"=> "62b7d7hl4ok",
                        "componentName"=> "GraphicNav",
                        "componentTitle"=> "图文导航",
                        "uses"=> 0,
                        "layout"=> "horizontal",
                        "mode"=> "graphic",
                        "showStyle"=> "singleSlide",
                        "rowCount"=> 3,
                        "pageCount"=> 1,
                        "carousel"=> [
                            "type"=> "circle",
                            "color"=> "#FFFFFF"
                        ],
                        "imageSize"=> 40,
                        "aroundRadius"=> 20,
                        "font"=> [
                            "size"=> 12,
                            "weight"=> "bold",
                            "color"=> "#303133"
                        ],
                        "pageStartBgColor"=> "",
                        "pageEndBgColor"=> "",
                        "pageGradientAngle"=> "to bottom",
                        "componentBgUrl"=> "",
                        "componentBgAlpha"=> 2,
                        "componentStartBgColor"=> "rgba(255, 255, 255, 1)",
                        "componentEndBgColor"=> "",
                        "componentGradientAngle"=> "to bottom",
                        "topRounded"=> 0,
                        "bottomRounded"=> 10,
                        "elementBgColor"=> "",
                        "topElementRounded"=> 0,
                        "bottomElementRounded"=> 0,
                        "margin"=> [
                            "top"=> 0,
                            "bottom"=> 15,
                            "both"=> 15
                        ],
                        "ignore"=> [],
                        "list"=> [
                            [
                                "title"=> "我的订单",
                                "link"=> [
                                    "parent"=> "ZZHC_LINK",
                                    "name"=> "ZZHC_ORDER_LIST",
                                    "title"=> "订单",
                                    "url"=> "/addon/zzhc/pages/order/list",
                                    "action"=> "decorate"
                                ],
                                "imageUrl"=> "addon/zzhc/diy/member/menu1.png",
                                "label"=> [
                                    "control"=> false,
                                    "text"=> "热门",
                                    "textColor"=> "#FFFFFF",
                                    "bgColorStart"=> "#F83287",
                                    "bgColorEnd"=> "#FE3423"
                                ],
                                "id"=> "xvlauaflc6o",
                                "imgWidth"=> 128,
                                "imgHeight"=> 128
                            ],
                            [
                                "title"=> "我的优惠券",
                                "link"=> [
                                    "parent"=> "ZZHC_LINK",
                                    "name"=> "ZZHC_MEMBER_COUPON",
                                    "title"=> "我的优惠券",
                                    "url"=> "/addon/zzhc/pages/member/coupon",
                                    "action"=> "decorate"
                                ],
                                "imageUrl"=> "addon/zzhc/diy/member/menu2.png",
                                "label"=> [
                                    "control"=> false,
                                    "text"=> "热门",
                                    "textColor"=> "#FFFFFF",
                                    "bgColorStart"=> "#F83287",
                                    "bgColorEnd"=> "#FE3423"
                                ],
                                "id"=> "63bjscck5n40",
                                "imgWidth"=> 128,
                                "imgHeight"=> 128
                            ],
                            [
                                "title"=> "我的会员卡",
                                "link"=> [
                                    "parent"=> "ZZHC_LINK",
                                    "name"=> "ZZHC_MEMBER_VIP",
                                    "title"=> "我的会员卡",
                                    "url"=> "/addon/zzhc/pages/member/vip",
                                    "action"=> "decorate"
                                ],
                                "imageUrl"=> "addon/zzhc/diy/member/menu3.png",
                                "label"=> [
                                    "control"=> false,
                                    "text"=> "热门",
                                    "textColor"=> "#FFFFFF",
                                    "bgColorStart"=> "#F83287",
                                    "bgColorEnd"=> "#FE3423"
                                ],
                                "id"=> "4qiczw54t8g0",
                                "imgWidth"=> 128,
                                "imgHeight"=> 128
                            ]
                        ],
                        "pageStyle"=> "padding-top=>2rpx;padding-bottom=>30rpx;padding-right=>30rpx;padding-left=>30rpx;"
                    ],
                    [
                        "path"=> "edit-graphic-nav",
                        "uses"=> 0,
                        "id"=> "33yn28534fs0",
                        "componentName"=> "GraphicNav",
                        "componentTitle"=> "图文导航",
                        "ignore"=> [],
                        "layout"=> "vertical",
                        "mode"=> "graphic",
                        "showStyle"=> "fixed",
                        "rowCount"=> 4,
                        "pageCount"=> 2,
                        "carousel"=> [
                            "type"=> "circle",
                            "color"=> "#FFFFFF"
                        ],
                        "imageSize"=> 25,
                        "aroundRadius"=> 0,
                        "font"=> [
                            "size"=> 13,
                            "weight"=> "normal",
                            "color"=> "rgba(0, 0, 0, 1)"
                        ],
                        "list"=> [
                            [
                                "title"=> "个人资料",
                                "link"=> [
                                    "parent"=> "MEMBER_LINK",
                                    "name"=> "MEMBER_PERSONAL",
                                    "title"=> "个人资料",
                                    "url"=> "/app/pages/member/personal"
                                ],
                                "imageUrl"=> "static/resource/images/diy/vert_m_personal.png",
                                "label"=> [
                                    "control"=> false,
                                    "text"=> "热门",
                                    "textColor"=> "#FFFFFF",
                                    "bgColorStart"=> "#F83287",
                                    "bgColorEnd"=> "#FE3423"
                                ],
                                "id"=> "4xc4kw9xlqu0",
                                "imgWidth"=> 92,
                                "imgHeight"=> 92
                            ],
                            [
                                "title"=> "我的余额",
                                "link"=> [
                                    "parent"=> "MEMBER_LINK",
                                    "name"=> "MEMBER_BALANCE",
                                    "title"=> "我的余额",
                                    "url"=> "/app/pages/member/balance"
                                ],
                                "imageUrl"=> "static/resource/images/diy/vert_m_balance.png",
                                "label"=> [
                                    "control"=> false,
                                    "text"=> "热门",
                                    "textColor"=> "#FFFFFF",
                                    "bgColorStart"=> "#F83287",
                                    "bgColorEnd"=> "#FE3423"
                                ],
                                "id"=> "4555rq0cc1q0",
                                "imgWidth"=> 92,
                                "imgHeight"=> 92
                            ],
                            [
                                "title"=> "联系客服",
                                "link"=> [
                                    "name"=> "MEMBER_CONTACT",
                                    "parent"=> "MEMBER_LINK",
                                    "title"=> "客服",
                                    "url"=> "/app/pages/member/contact",
                                    "action"=> ""
                                ],
                                "imageUrl"=> "static/resource/images/diy/vert_m_service.png",
                                "label"=> [
                                    "control"=> false,
                                    "text"=> "热门",
                                    "textColor"=> "#FFFFFF",
                                    "bgColorStart"=> "#F83287",
                                    "bgColorEnd"=> "#FE3423"
                                ],
                                "id"=> "6gqbh1tvyr00",
                                "imgWidth"=> 92,
                                "imgHeight"=> 92
                            ]
                        ],
                        "pageStartBgColor"=> "",
                        "pageEndBgColor"=> "",
                        "pageGradientAngle"=> "to bottom",
                        "componentBgUrl"=> "",
                        "componentBgAlpha"=> 2,
                        "componentStartBgColor"=> "rgba(255, 255, 255, 1)",
                        "componentEndBgColor"=> "",
                        "componentGradientAngle"=> "to bottom",
                        "topRounded"=> 10,
                        "bottomRounded"=> 10,
                        "elementBgColor"=> "",
                        "topElementRounded"=> 0,
                        "bottomElementRounded"=> 0,
                        "margin"=> [
                            "top"=> 0,
                            "bottom"=> 15,
                            "both"=> 15
                        ],
                        "pageStyle"=> "padding-top=>2rpx;padding-bottom=>30rpx;padding-right=>30rpx;padding-left=>30rpx;"
                    ],
                    [
                        "path"=> "edit-graphic-nav",
                        "uses"=> 0,
                        "id"=> "niwm4okmasw",
                        "componentName"=> "GraphicNav",
                        "componentTitle"=> "图文导航",
                        "ignore"=> [],
                        "layout"=> "vertical",
                        "mode"=> "graphic",
                        "showStyle"=> "fixed",
                        "rowCount"=> 4,
                        "pageCount"=> 1,
                        "carousel"=> [
                            "type"=> "circle",
                            "color"=> "#FFFFFF"
                        ],
                        "imageSize"=> 25,
                        "aroundRadius"=> 0,
                        "font"=> [
                            "size"=> 14,
                            "weight"=> "normal",
                            "color"=> "#303133"
                        ],
                        "list"=> [
                            [
                                "title"=> "发型师端",
                                "link"=> [
                                    "parent"=> "ZZHC_LINK",
                                    "name"=> "ZZHC_MERCHANT_BARBER_INDEX",
                                    "title"=> "发型师端",
                                    "url"=> "/addon/zzhc/pages/merchant/barber/index",
                                    "action"=> "decorate"
                                ],
                                "imageUrl"=> "addon/zzhc/diy/member/menu4.png",
                                "label"=> [
                                    "control"=> false,
                                    "text"=> "热门",
                                    "textColor"=> "#FFFFFF",
                                    "bgColorStart"=> "#F83287",
                                    "bgColorEnd"=> "#FE3423"
                                ],
                                "imgWidth"=> 64,
                                "imgHeight"=> 64,
                                "id"=> "2fp60ap568kk"
                            ],
                            [
                                "title"=> "店长端",
                                "link"=> [
                                    "parent"=> "ZZHC_LINK",
                                    "name"=> "ZZHC_MERCHANT_MANAGE_INDEX",
                                    "title"=> "店长端",
                                    "url"=> "/addon/zzhc/pages/merchant/manage/index",
                                    "action"=> "decorate"
                                ],
                                "imageUrl"=> "addon/zzhc/diy/member/menu5.png",
                                "label"=> [
                                    "control"=> false,
                                    "text"=> "热门",
                                    "textColor"=> "#FFFFFF",
                                    "bgColorStart"=> "#F83287",
                                    "bgColorEnd"=> "#FE3423"
                                ],
                                "imgWidth"=> 64,
                                "imgHeight"=> 64,
                                "id"=> "2ylgdaj28hq0"
                            ]
                        ],
                        "textColor"=> "#303133",
                        "pageStartBgColor"=> null,
                        "pageEndBgColor"=> "",
                        "pageGradientAngle"=> "to bottom",
                        "componentBgUrl"=> "",
                        "componentBgAlpha"=> 2,
                        "componentStartBgColor"=> "rgba(255, 255, 255, 1)",
                        "componentEndBgColor"=> "",
                        "componentGradientAngle"=> "to bottom",
                        "topRounded"=> 10,
                        "bottomRounded"=> 10,
                        "elementBgColor"=> "",
                        "topElementRounded"=> 0,
                        "bottomElementRounded"=> 0,
                        "margin"=> [
                            "top"=> 0,
                            "bottom"=> 0,
                            "both"=> 15
                        ],
                        "pageStyle"=> "padding-top=>2rpx;padding-bottom=>0rpx;padding-right=>30rpx;padding-left=>30rpx;"
                    ]
                ]
            ]
        ]
    ]
];
