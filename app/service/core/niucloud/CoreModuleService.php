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

namespace app\service\core\niucloud;

use core\util\niucloud\BaseNiucloudClient;
use core\util\niucloud\http\Response;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * 应用管理服务层
 */
class CoreModuleService extends BaseNiucloudClient
{

    public function getModuleList()
    {
        $params = [
            'code' => $this->code,
            'secret' => $this->secret,
            'product_key' => self::PRODUCT
        ];
        return $this->httpGet('member_app_all', $params);
    }

    public function getIndexModuleLabelList()
    {
        $params = [
            'code' => $this->code,
            'secret' => $this->secret,
        ];
        return $this->httpGet('store/label/all', $params);
    }

    public function getIndexModuleList($label_id)
    {
        $params = [
            'code' => $this->code,
            'secret' => $this->secret,
            'labels' => [$label_id],
            'product_key' => self::PRODUCT,
            'is_recommend' => 1,
            'order_field' => 'sale_num desc, visit_num desc',
        ];
        return $this->httpGet('store/app', $params);
    }

    /**
     * 授权信息
     * @param $module_id
     * @return array|Collection|Response|object|ResponseInterface|string
     * @throws GuzzleException
     */
    public function getAuthInfo()
    {
        $params = [
            'app_type' => '',
            'code' => $this->code,
            'secret' => $this->secret
        ];
        return $this->httpGet('member_app', $params);
    }

    /**
     * 版本下载
     * @param $app_key
     * @param $dir
     * @return string|void
     * @throws GuzzleException
     */
    public function downloadModule($app_key, $dir)
    {
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        $path = $dir . $app_key . time() . '.zip';
        return $this->download('member_app_download/' . $app_key, [], $path);
    }

    public function upgrade($app_key, $version_id)
    {

    }


    /**
     * 操作token
     * @param $action
     * @param $data
     * @return array|\core\util\niucloud\Response|object|ResponseInterface
     * @throws GuzzleException
     */
    public function getActionToken($action, $data)
    {
        return $this->httpGet('member_app_action/'.$action, $data);
    }

    /**
     * 获取升级内容
     * @param $data
     * @return array|\core\util\niucloud\Response|object|ResponseInterface
     * @throws GuzzleException
     */
    public function getUpgradeContent($data) {
        return $this->httpGet('member_app_upgrade/content', $data);
    }

    /**
     * 校验key是否被占用
     * @param $key
     * @return array|Response|object|ResponseInterface
     * @throws GuzzleException
     */
    public function checkKey($key)
    {
        return $this->httpGet('store/app_check/'.$key, ['product_key' => self::PRODUCT])['data'] ?? false;
    }

    /**
     * 获取框架最新版本
     * @return array|\core\util\niucloud\Response|object|ResponseInterface
     * @throws GuzzleException
     */
    public function getFrameworkLastVersion() {
        return $this->httpGet('store/framework/lastversion', ['product_key' => self::PRODUCT])['data'] ?? false;
    }

    /**
     * 获取框架版本更新记录
     * @return false|mixed
     * @throws GuzzleException
     */
    public function getFrameworkVersionList() {
        return $this->httpGet('store/framework/version', ['product_key' => self::PRODUCT])['data'] ?? false;
    }

    /**
     * 获取应用/插件的版本更新记录
     * @return false|mixed
     * @throws GuzzleException
     */
    public function getAppVersionList($app_key)
    {
        return $this->httpGet('store/app_version/list', [ 'product_key' => self::PRODUCT, 'app_key' => $app_key ])[ 'data' ] ?? false;
    }
}
