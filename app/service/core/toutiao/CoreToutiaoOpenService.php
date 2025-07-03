<?php
namespace app\service\core\toutiao;

use app\dict\sys\ConfigKeyDict;
use app\service\core\sys\CoreConfigService;
use core\base\BaseCoreService;
use GuzzleHttp\Client;

/**
 * 今日头条开放平台相关接口服务
 */
class CoreToutiaoOpenService extends BaseCoreService
{
    /**
     * 获取第三方小程序接口调用凭据 component_access_token
     * @return array
     */
    public function getComponentAccessToken(): array
    {
        $config = (new CoreConfigService())->getConfigValue(0, ConfigKeyDict::TOUTIAO);
        $ticket = (new CoreConfigService())->getConfigValue(0, ConfigKeyDict::TOUTIAO_TICKET);

        $client = new Client();
        $response = $client->get('https://open.microapp.bytedance.com/openapi/v1/auth/tp/token', [
            'query' => [
                'component_appid' => $config['app_id'] ?? '',
                'component_appsecret' => $config['app_secret'] ?? '',
                'component_ticket' => $ticket['ticket'] ?? '',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 直接获取授权链接
     * @param array $params 请求参数
     * @param string $componentAccessToken 已获取的 component_access_token
     * @return array
     */
    public function genAuthLink(array $params, string $componentAccessToken): array
    {
        $config = (new CoreConfigService())->getConfigValue(0, ConfigKeyDict::TOUTIAO);

        $client = new Client();
        $response = $client->post('https://open.microapp.bytedance.com/openapi/v2/auth/gen_link', [
            'query' => [
                'component_appid' => $config['app_id'] ?? '',
                'component_access_token' => $componentAccessToken,
            ],
            'json' => $params,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }




    /**
     * 获取授权小程序对应阶段的二维码
     * @param array $params 请求参数
     * @param string $authorizerAccessToken 授权小程序接口调用凭据
     * @return string 二维码图片路径
     */
    public function getQrcode(array $params, string $authorizerAccessToken): string
    {
        $config = (new CoreConfigService())->getConfigValue(0, ConfigKeyDict::TOUTIAO);

        $client = new Client();
        $response = $client->post('https://open.microapp.bytedance.com/openapi/v1/microapp/app/qrcode', [
            'query' => [
                'component_appid' => $config['app_id'] ?? '',
                'authorizer_access_token' => $authorizerAccessToken,
            ],
            'json' => $params,
        ]);

        $dir = public_path() . 'qrcode/0/';
        mkdirs_or_notexist($dir);
        $filepath = $dir . time() . '.png';
        file_put_contents($filepath, $response->getBody()->getContents());

        return $filepath;
    }
}