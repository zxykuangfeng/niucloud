<?php
return [
    'pages' => <<<EOT
        // PAGE_BEGIN
		{
		    "root": "addon/zzhc",
		    "pages": [
				{
				    "path": "pages/index",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.index%",
						"navigationStyle": "custom"
				    }
				},
				{
				    "path": "pages/barber/list",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.barber.list%"
				    }
				},
				{
				    "path": "pages/barber/detail",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.barber.detail%"
				    }
				},
				{
				    "path": "pages/order/list",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.order.list%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/order/detail",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.order.detail%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/member/index",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.member.index%",
						"navigationStyle": "custom"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/member/coupon",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.member.coupon%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/member/vip",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.member.vip%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/store/list",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.store.list%"
				    }
				},
				{
				    "path": "pages/store/detail",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.store.detail%"
				    }
				},
				{
				    "path": "pages/goods/select",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.goods.select%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/vip/buy",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.vip.buy%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/coupon/list",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.coupon.list%"
				    }
				},
				{
				    "path": "pages/merchant/manage/index",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.merchant.manage.index%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/merchant/barber/index",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.merchant.barber.index%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/merchant/barber/order/list",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.merchant.barber.order.list%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/merchant/barber/order/detail",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.merchant.barber.order.detail%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/merchant/barber/work/list",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.merchant.barber.work.list%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/merchant/manage/order/list",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.merchant.manage.order.list%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/merchant/manage/order/detail",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.merchant.manage.order.detail%"
				    },
					"needLogin": true
				},
				{
				    "path": "pages/merchant/manage/work/list",
				    "style": {
				        "navigationBarTitleText": "%zzhc.pages.merchant.manage.work.list%"
				    },
					"needLogin": true
				}
			]
		},
        // PAGE_END
EOT
];