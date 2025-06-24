<?php
// +----------------------------------------------------------------------
// | Niucloud-admin 企业快速开发的saas管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace app\service\api\diy;

use app\service\core\diy\CoreDiyConfigService;
use app\service\core\site\CoreSiteService;
use core\base\BaseApiService;

/**
 * 自定义页面相关配置服务层
 * Class DiyConfigService
 * @package app\service\admin\diy
 */
class DiyConfigService extends BaseApiService
{

    /**
     * 获取底部导航列表
     * @param array $params
     * @return array|mixed
     */
    public function getBottomList($params = [])
    {
        $list = ( new CoreDiyConfigService() )->getBottomList($params);

        $site_addon = ( new CoreSiteService() )->getSiteCache($this->site_id);
        $bottom_list_keys = array_column($list, 'key');

        // 排除没有底部导航的应用
        foreach ($site_addon[ 'apps' ] as $k => $v) {
            if (!in_array($v[ 'key' ], $bottom_list_keys)) {
                unset($site_addon[ 'apps' ][ $k ]);
            }
        }

        // 单应用，排除 系统 底部导航设置
        if (count($list) > 1 && count($site_addon[ 'apps' ]) == 1) {
            foreach ($list as $k => $v) {
                if ($v[ 'key' ] = 'app') {
                    unset($list[ $k ]);
                    break;
                }
            }
            $list = array_values($list);
        }

        $res = [];
        foreach ($list as $k => $v) {
            $res[] = $this->getBottomConfig($v[ 'key' ]);
        }
        return $res;
    }

    /**
     * 获取底部导航配置
     * @param $key
     * @return array
     */
    public function getBottomConfig($key)
    {
        // 检测当前站点是多应用还是单应用
        if ($key == 'app') {
            $site_addon = ( new CoreSiteService() )->getSiteCache($this->site_id);

            $list = ( new CoreDiyConfigService() )->getBottomList();
            $bottom_list_keys = array_column($list, 'key');
            // 排除没有底部导航的应用
            foreach ($site_addon[ 'apps' ] as $k => $v) {
                if (!in_array($v[ 'key' ], $bottom_list_keys)) {
                    unset($site_addon[ 'apps' ][ $k ]);
                }
            }
            if (count($site_addon[ 'apps' ]) == 1) {
                $key = $site_addon[ 'apps' ][ 0 ][ 'key' ];
            }
        }
        return ( new CoreDiyConfigService() )->getBottomConfig($this->site_id, $key);
    }

    /**
     * 获取启动页配置
     * @return array
     */
    public function getStartUpPageConfig($type)
    {
        return ( new CoreDiyConfigService() )->getStartUpPageConfig($this->site_id, $type);
    }

}
