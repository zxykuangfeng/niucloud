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
     * 通过授权 appid 取回最新的授权码
     * @param string $authorizationAppid 授权小程序 appid
     * @param string $componentAccessToken 第三方平台接口凭据
     * @return array
     */
    public function retrieveAuthCode(string $authorizationAppid, string $componentAccessToken): array
    {
        $config = (new CoreConfigService())->getConfigValue(0, ConfigKeyDict::TOUTIAO);

        $client = new Client();
        $response = $client->post('https://open.microapp.bytedance.com/openapi/v1/auth/retrieve', [
            'query' => [
                'component_appid' => $config['app_id'] ?? '',
                'component_access_token' => $componentAccessToken,
                'authorization_appid' => $authorizationAppid,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 根据授权码换取 authorizer_access_token
     * @param string $authorizationCode 授权码
     * @param string $componentAccessToken 第三方平台接口凭据
     * @return array
     */
    public function getAuthorizerAccessToken(string $authorizationCode, string $componentAccessToken): array
    {
        $config = (new CoreConfigService())->getConfigValue(0, ConfigKeyDict::TOUTIAO);

        $client = new Client();
        $response = $client->get('https://open.microapp.bytedance.com/openapi/v1/oauth/token', [
            'query' => [
                'component_appid' => $config['app_id'] ?? '',
                'component_access_token' => $componentAccessToken,
                'authorization_code' => $authorizationCode,
                'grant_type' => 'app_to_tp_authorization_code',
            ],
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


        /**
     * 获取已授权小程序的二维码
     * @param string $version 指定版本 latest、audit、current
     * @param string $path 页面路径参数
     * @return string 二维码图片路径
     */
    public function getToutiaoQrcode(string $version = 'latest', string $path = ''): string
    {
        $component = $this->getComponentAccessToken();
        $componentAccessToken = $component['component_access_token'] ?? '';
        if ($componentAccessToken === '') {
            return '';
        }

        $config = (new CoreConfigService())->getConfigValue(0, ConfigKeyDict::TOUTIAO_WANDU);
        $authorizationAppid = $config['authorization_appid'] ?? ($config['app_id'] ?? '');
        if ($authorizationAppid === '') {
            return '';
        }

        $codeRes = $this->retrieveAuthCode($authorizationAppid, $componentAccessToken);
        $authorizationCode = $codeRes['authorization_code'] ?? '';
        if ($authorizationCode === '') {
            return '';
        }

        $tokenRes = $this->getAuthorizerAccessToken($authorizationCode, $componentAccessToken);
        $authorizerAccessToken = $tokenRes['authorizer_access_token'] ?? '';
        if ($authorizerAccessToken === '') {
            return '';
        }

        $params = ['version' => $version];
        if ($path !== '') $params['path'] = $path;

        return $this->getQrcode($params, $authorizerAccessToken);
    }
}