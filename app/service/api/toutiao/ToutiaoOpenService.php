<?php
namespace app\service\api\toutiao;

use app\service\core\toutiao\CoreToutiaoOpenService;
use core\base\BaseApiService;

class ToutiaoOpenService extends BaseApiService
{
    protected CoreToutiaoOpenService $core_service;

    public function __construct()
    {
        parent::__construct();
        $this->core_service = new CoreToutiaoOpenService();
    }

    /**
     * 获取授权链接
     * @param array $params
     * @return array
     */
    public function getAuthLink(array $params = []): array
    {
        $token = $this->core_service->getComponentAccessToken();
        $accessToken = $token['component_access_token'] ?? '';
        if ($accessToken === '') {
            return [];
        }
        return $this->core_service->genAuthLink($params, $accessToken);
    }



      /**
     * 获取小程序二维码
     * @param string $authorizerAccessToken 授权小程序接口调用凭据
     * @param array $params 请求参数
     * @return string 二维码图片路径
     */
    public function getQrcode(string $authorizerAccessToken, array $params = []): string
    {
        return $this->core_service->getQrcode($params, $authorizerAccessToken);
    }


    
    /**
     * 获取已授权抖音小程序二维码
     * @param string $version
     * @param string $path
     * @return string
     */
    public function getToutiaoQrcode(string $version = 'latest', string $path = ''): string
    {
        return $this->core_service->getToutiaoQrcode($version, $path);
    }
}