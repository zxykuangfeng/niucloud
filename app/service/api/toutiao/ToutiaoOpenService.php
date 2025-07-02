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
}