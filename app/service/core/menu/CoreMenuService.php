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

namespace app\service\core\menu;

use app\dict\sys\AppTypeDict;
use app\dict\sys\MenuDict;
use app\model\addon\Addon;
use app\model\sys\SysMenu;
use app\service\admin\sys\MenuService;
use core\base\BaseCoreService;
use core\dict\DictLoader;
use think\db\exception\DbException;
use think\facade\Cache;
use think\facade\Db;

/**
 * 系统菜单
 */
class CoreMenuService extends BaseCoreService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new SysMenu();
    }

    /**
     * 插件卸载
     * @param $addon
     * @return bool
     */
    public function uninstallAddonMenu($addon)
    {
        $addon_loader = new DictLoader("Menu");

        $this->deleteByAddon($addon);
        // 清除缓存
        Cache::tag(MenuService::$cache_tag_name)->clear();
        return true;
    }

    /**
     * 删除插件菜单(强删除)
     * @param string $addon
     * @return true
     * @throws DbException
     */
    public function deleteByAddon(string $addon, bool $is_all = true)
    {
        $where = [ [ 'addon', '=', $addon ] ];
        if (!$is_all) {
            $where[] = [ 'source', '=', MenuDict::SYSTEM ];
        }
        Db::name("sys_menu")->where($where)->delete();
        return true;
    }

    /**
     * 刷新所有插件菜单
     */
    public function refreshAllAddonMenu()
    {

        $addons = ( new Addon() )->field("key")->select()->toArray();
        foreach ($addons as $k => $v) {
            $this->refreshAddonMenu($v[ "key" ]);
        }
        return true;
    }

    /**
     * 安装或者刷新插件菜单
     * @param $addon
     * @return bool
     */
    public function refreshAddonMenu($addon)
    {
        $addon_loader = new DictLoader("Menu");

        $addon_admin_tree = $addon_loader->load([ "addon" => $addon, "app_type" => "admin" ]);
        $addon_site_tree = $addon_loader->load([ "addon" => $addon, "app_type" => "site" ]);

        if (isset($addon_admin_tree[ 'delete' ])) unset($addon_admin_tree[ 'delete' ]);
        if (isset($addon_site_tree[ 'delete' ])) unset($addon_site_tree[ 'delete' ]);

        $menu_list = [];

        if (!empty($addon_admin_tree)) {
            $menu_list = array_merge($menu_list, $this->loadMenu($addon_admin_tree, "admin", $addon));
        }

        if (!empty($addon_site_tree)) {
            $menu_list = array_merge($menu_list, $this->loadMenu($addon_site_tree, "site", $addon));
        }

        $this->deleteByAddon($addon, false);
        if (!empty($menu_list)) {
            $this->install($menu_list);
        }

        return true;

    }

    /**
     * 加载菜单
     * @return array
     */
    public function loadMenu(array $menu_tree, string $app_type, string $addon = '')
    {
        //加载系统
        $menu_list = [];
        $this->menuTreeToList($menu_tree, '', $app_type, $addon, $menu_list);
        return $menu_list;
    }

    /**
     * 菜单数转为列表
     * @param array $tree
     * @param string $parent_key
     * @param string $app_type
     * @param string $addon
     * @param array $menu_list
     */
    private function menuTreeToList(array $tree, string $parent_key = '', string $app_type = AppTypeDict::ADMIN, string $addon = '', array &$menu_list = [])
    {
        if (is_array($tree)) {
            foreach ($tree as $key => $value) {
                $item = [
                    'menu_name' => $value[ 'menu_name' ],
                    'menu_short_name' => $value[ 'menu_short_name' ] ?? '',
                    'menu_key' => $value[ 'menu_key' ],
                    'app_type' => $app_type,
                    'addon' => $addon,
                    'parent_key' => $value[ 'parent_key' ] ?? $parent_key,
                    'menu_type' => $value[ 'menu_type' ],
                    'icon' => $value[ 'icon' ] ?? '',
                    'api_url' => $value[ 'api_url' ] ?? '',
                    'router_path' => $value[ 'router_path' ] ?? '',
                    'view_path' => $value[ 'view_path' ] ?? '',
                    'methods' => $value[ 'methods' ] ?? '',
                    'sort' => $value[ 'sort' ] ?? '',
                    'status' => 1,
                    'is_show' => $value[ 'is_show' ] ?? 1
                ];
                $refer = $value;
                if (isset($refer[ 'children' ])) {
                    unset($refer[ 'children' ]);
                    $menu_list[] = $item;
                    $p_key = $refer[ 'menu_key' ];
                    $this->menuTreeToList($value[ 'children' ], $p_key, $app_type, $addon, $menu_list);
                } else {
                    $menu_list[] = $item;
                }
            }
        }
    }

    /**
     * 安装菜单
     * @param array $menu_list
     * @return true
     */
    public function install(array $menu_list)
    {
        $this->model->replace()->insertAll($menu_list);
        // 清除缓存
        Cache::tag(MenuService::$cache_tag_name)->clear();
        return true;
    }

    /**
     * 获取path
     * @param $menu_key
     * @param $paths
     * @return string
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRoutePathByMenuKey($menu_key, $paths = [])
    {
        $menu = $this->model->where([ [ 'menu_key', '=', $menu_key ], [ 'app_type', '=', 'site' ] ])->field('parent_key,router_path')->find();
        if (empty($menu)) return '';
        array_unshift($paths, $menu[ 'router_path' ]);
        if (!empty($menu[ 'parent_key' ])) {
            return $this->getRoutePathByMenuKey($menu[ 'parent_key' ], $paths);
        } else {
            return implode('/', $paths);
        }
    }
}
