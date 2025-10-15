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
        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO);
        $ticket = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO_TICKET);

        $client = new Client();
        $response = $client->get('https://open.microapp.bytedance.com/openapi/v1/auth/tp/token', [
            'query' => [
                'component_appid' => $config['app_id'] ?? '',
                'component_appsecret' => $config['app_secret'] ?? '',
                'component_ticket' => $ticket['ticket'] ?? '',
            ],
        ]);
        // dd($config['app_id']);
        return json_decode($response->getBody()->getContents(), true);
    }
    
       /**
     * 获取已授权抖音小程序的 access_token
     * @return string
     */
    public function getToutiaoAccessToken(): string
    {
        $component = $this->getComponentAccessToken();
        $componentAccessToken = $component['component_access_token'] ?? '';
        if ($componentAccessToken === '') {
            return '';
        }

        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO_WANDU);
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
        return $tokenRes['authorizer_access_token'] ?? '';
    }
    // authorizer_access_token
    //     public function getAuthorizerAccessToken(): array
    // {
    //     $config = (new CoreConfigService())->getConfigValue(100001, 'toutiao');
    //     $ticket = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO_TICKET);
    //     $client = new Client();
    //     $response = $client->get('https://open.microapp.bytedance.com/openapi/v1/oauth/token', [ 
    //         // https://open.microapp.bytedance.com/openapi/v1/oauth/token
    //         'query' => [
    //             'component_appid' => $config['app_id'] ?? '',
    //             'component_appsecret' => $config['app_secret'] ?? '',
    //             'grant_type' => 'app_to_tp_authorization_code',
    //         ],
    //     ]);
    //     dd(json_decode($response->getBody()->getContents(), true));
    //     return json_decode($response->getBody()->getContents(), true);
    // }

    /**
     * 直接获取授权链接
     * @param array $params 请求参数
     * @param string $componentAccessToken 已获取的 component_access_token
     * @return array
     */
    public function genAuthLink(array $params, string $componentAccessToken): array
    {
        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO);

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
        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO);

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

    // /**
    //  * 根据授权码换取 authorizer_access_token
    //  * @param string $authorizationCode 授权码
    //  * @param string $componentAccessToken 第三方平台接口凭据
    //  * @return array
    //  */
    public function getAuthorizerAccessToken(string $authorizationCode, string $componentAccessToken): array
    {
        // dd(111);
        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO);

        $client = new Client();
        $response = $client->get('https://open.microapp.bytedance.com/openapi/v1/oauth/token', [
            'query' => [
                'component_appid' => $config['app_id'] ?? '',
                'component_access_token' => $componentAccessToken,
                'authorization_code' => $authorizationCode,
                'grant_type' => 'app_to_tp_authorization_code',
            ],
        ]);
        // dd($config);
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
    $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO);

    $client = new Client();
    $response = $client->post('https://open.microapp.bytedance.com/openapi/v1/microapp/app/qrcode', [
        'query' => [
            'component_appid' => $config['app_id'] ?? '',
            'authorizer_access_token' => $authorizerAccessToken,
        ],
        'json' => $params,
    ]);

    $contentType = $response->getHeaderLine('Content-Type');
    $body = $response->getBody()->getContents();
    // dd($response);
    // 判断是否是 JSON（错误信息）或图片
    if (str_contains($contentType, 'application/json')) {
        $json = json_decode($body, true);
        // 打印错误信息或记录日志
        
        
        // throw new \Exception('获取二维码失败：' . json_encode($json, JSON_UNESCAPED_UNICODE));
    }

    $dir = public_path('qrcode/0/');
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $filepath = $dir . time() . '.png';
    file_put_contents($filepath, $body);

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
        
       
        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO_WANDU);
        $authorizationAppid = $config['authorization_appid'] ?? ($config['app_id'] ?? '');
        if ($authorizationAppid === '') {
            return '';
        }
 
        $codeRes = $this->retrieveAuthCode($authorizationAppid, $componentAccessToken);
    //   dd($codeRes);
        $authorizationCode = $codeRes['authorization_code'] ?? '';
        if ($authorizationCode === '') {
            return '';
        }

        $tokenRes = $this->getAuthorizerAccessToken($authorizationCode, $componentAccessToken);
        // dd($tokenRes);
        $authorizerAccessToken = $tokenRes['authorizer_access_token'] ?? '';
        if ($authorizerAccessToken === '') {
            return '';
        }

        $params = ['version' => $version];
        if ($path !== '') $params['path'] = $path;

        return $this->getQrcode($params, $authorizerAccessToken);
    }
    
    
      /**
     * 提审已授权抖音小程序
     * @param array $hostNames 需要审核的宿主端
     * @param string $auditNote 提审备注
     * @param int $auditWay 是否使用上次失败版本重新提审
     * @return array
     */
    public function auditToutiaoPackage(array $hostNames, string $auditNote = '', int $auditWay = 0): array
    {
        $component = $this->getComponentAccessToken();
        $componentAccessToken = $component['component_access_token'] ?? '';
        if ($componentAccessToken === '') {
            return [];
        }

        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO_WANDU);
        $authorizationAppid = $config['authorization_appid'] ?? ($config['app_id'] ?? '');
        if ($authorizationAppid === '') {
            return [];
        }

        $codeRes = $this->retrieveAuthCode($authorizationAppid, $componentAccessToken);
        $authorizationCode = $codeRes['authorization_code'] ?? '';
        if ($authorizationCode === '') {
            return [];
        }

        $tokenRes = $this->getAuthorizerAccessToken($authorizationCode, $componentAccessToken);
        $authorizerAccessToken = $tokenRes['authorizer_access_token'] ?? '';
        if ($authorizerAccessToken === '') {
            return [];
        }

        $params = [
            'hostNames' => $hostNames,
        ];
        if ($auditNote !== '') $params['auditNote'] = $auditNote;
        if ($auditWay !== 0) $params['auditWay'] = $auditWay;

        return $this->auditPackage($params, $authorizerAccessToken);
    }

    /**
     * 调用抖音提审接口
     * @param array $params 请求参数
     * @param string $authorizerAccessToken 授权小程序接口调用凭据
     * @return array
     */
    public function auditPackage(array $params, string $authorizerAccessToken): array
    {
        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO);

        $client = new Client();
        $response = $client->post('https://open.microapp.bytedance.com/openapi/v2/microapp/package/audit', [
            'query' => [
                'component_appid' => $config['app_id'] ?? '',
                'authorizer_access_token' => $authorizerAccessToken,
            ],
            'json' => $params,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
    
    
        /**
     * 查询抖音标签组信息
     * @param string $accessToken 接口调用凭据
     * @return array
     */
    public function getToutiaoTagGroup(string $accessToken): array
    {
        $client = new Client();
        $response = $client->post('https://open.douyin.com/api/trade_basic/v1/developer/tag_query/', [
            'headers' => [
                'access-token' => $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'goods_type' => 302,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }


     /**
     * 获取抖音开放平台 access_token
     * @param string $code 授权码，留空则使用 client_credential 模式
     * @return array
     */
    public function getOauthAccessToken(string $code = ''): array
    {
        $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO);

        $formParams = [
            'client_key' => 'tt3a734ccea57a93d001',
            'client_secret' => '960d82e3d4e95eac12a1cb9dacc0620ff8cded7f',
            'grant_type' =>'client_credential'
        ];


        $client = new Client();
        $response = $client->post('https://open.douyin.com/oauth/client_token/', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => $formParams,
        ]);
        // dd($response->getBody()->getContents());
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 调用抖音商品创建接口
     * @param string $accessToken
     * @param array $payload
     * @return array
     */
    public function saveToutiaoProduct(string $accessToken, array $payload): array
    {
        $client = new Client();
        $response = $client->post('https://open.douyin.com/goodlife/v1/goods/product/save/', [
            'headers' => [
                'access-token' => $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}