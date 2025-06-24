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

namespace addon\zzhc\app\listener;

use app\service\core\addon\CoreAddonService;
use app\service\core\site\CoreSiteService;

/**
 * 底部导航
 */
class BottomNavigationListener
{
    /**
     * @param array $params
     * @return array|void
     */
    public function handle($params = [])
    {
        $key = 'zzhc';

        $site_addon = (new CoreSiteService())->getAddonKeysBySiteId(request()->siteId());
        if (!in_array($key, $site_addon)) return;

        if (!empty($params) && $params[ 'key' ] != $key) return;

        $core_addon_service = new CoreAddonService();
        $addon_info = $core_addon_service->getAddonConfig($key);
        return [
            'key' => $key,
            'info' => $addon_info,
            'value' => [
                'backgroundColor' => '#ffffff',
                'textColor' => '#333333',
                'textHoverColor' => '#2d70ff',
                'type' => '1',
                'list' => [
                    [
                        "text" => "首页",
                        "link" => [
                            "parent" => "ZZHC_LINK",
                            "name" => "ZZHC_INDEX",
                            "title" => "首页",
                            "url" => "/addon/zzhc/pages/index"
                        ],
                        "iconPath" => "addon/zzhc/diy/tabbar/home.png",
                        "iconSelectPath" => "addon/zzhc/diy/tabbar/home-selected.png"
                    ],
                    [
                        "text" => "取号",
                        "link" => [
                            "parent" => "ZZHC_LINK",
                            "name" => "ZZHC_BARBER_LIST",
                            "title" => "取号",
                            "url" => "/addon/zzhc/pages/barber/list"
                        ],
                        "iconPath" => "addon/zzhc/diy/tabbar/barber.png",
                        "iconSelectPath" => "addon/zzhc/diy/tabbar/barber-selected.png"
                    ],
                    [
                        "text" => "订单",
                        "link" => [
                            "parent" => "ZZHC_LINK",
                            "name" => "ZZHC_ORDER_LIST",
                            "title" => "订单",
                            "url" => "/addon/zzhc/pages/order/list"
                        ],
                        "iconPath" => "addon/zzhc/diy/tabbar/order.png",
                        "iconSelectPath" => "addon/zzhc/diy/tabbar/order-selected.png"
                    ],
                    [
                        "text" => "我的",
                        "link" => [
                            "parent" => "ZZHC_LINK",
                            "name" => "ZZHC_MEMBER_INDEX",
                            "title" => "我的",
                            "url" => "/addon/zzhc/pages/member/index"
                        ],
                        "iconPath" => "addon/zzhc/diy/tabbar/my.png",
                        "iconSelectPath" => "addon/zzhc/diy/tabbar/my-selected.png"
                    ]
                ]
            ]
        ];
    }
}
