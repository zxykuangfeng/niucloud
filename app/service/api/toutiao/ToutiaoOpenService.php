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
        
        $token = $this->core_service->getComponentAccessSanFangToken();
        
         $accessToken = $token['component_access_token'] ?? '';
        if ($accessToken === '') {
            return [];
        }
        return $this->core_service->getQrcode($params, $accessToken);
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
    
      /**
     * 提审已授权抖音小程序
     * @param array $hostNames
     * @param string $auditNote
     * @param int $auditWay
     * @return array
     */
    public function auditToutiaoPackage(array $hostNames, string $auditNote = '', int $auditWay = 0): array
    {
        return $this->core_service->auditToutiaoPackage($hostNames, $auditNote, $auditWay);
    }
}