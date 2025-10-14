<?php
namespace app\service\core\toutiao;

use app\service\core\toutiao\CoreToutiaoConfigService;
use core\base\BaseCoreService;
use GuzzleHttp\Client;

class CoreToutiaoAuthService extends BaseCoreService
{
    public function session(int $site_id, ?string $code)
    {
        $config = (new CoreToutiaoConfigService())->getToutiaoConfig($site_id);
        
        
        
        //   dd($config);
        //  die;
        //目标小程序（万度）
        // $config['app_id'] = 'tt554123fcca4821c001';
        //   $config['app_secret'] = '0c8084ca6752ce2082d8fc375769afbd43d32226';
        //现在的模板理发预约 
        //      $config['app_id'] = 'tt3a734ccea57a93d001';
        //   $config['app_secret'] = '94ad32ae324a0776d1cb2bf2d10388f171926598';
        //瞬修
                     $config['app_id'] = 'tt3a734ccea57a93d001';
          $config['app_secret'] = '960d82e3d4e95eac12a1cb9dacc0620ff8cded7f';
        $client = new Client();
          $response = $client->post('https://open-sandbox.douyin.com/api/apps/v2/jscode2session', [
        'json' => [
            'appid' => $config['app_id'] ?? '',
            'secret' => $config['app_secret'] ?? '',
            'code' => (string)$code
        ]
]);

$body = $response->getBody()->getContents();
//   dd(json_decode($body, true)['data']);
// dd([
//     '原始内容' => $body,
//     '解析后' => json_decode($body, true),
//     'Content-Type' => $response->getHeaderLine('Content-Type')
// ]);
//           dd(json_decode($response->getBody()->getContents(), true));
         return json_decode($body, true)['data'];
    }

    public function getUserPhoneNumber(int $site_id, string $code)
    {
        // 未实现手机号获取，可根据抖音开放平台接口补充
        return [];
    }
    
    
      /**
     * 获取小程序 access_token
     * @param int $site_id
     * @return string
     */
    public function getAccessToken(int $site_id): string
    {
        $config = (new CoreToutiaoConfigService())->getToutiaoConfig($site_id);
               $config['app_id'] = 'ttb99ecf2326972f8a01';
          $config['app_secret'] = '94ad32ae324a0776d1cb2bf2d10388f171926598';    
        $client = new Client();
        $response = $client->post('https://open.douyin.com/oauth/client_token/', [
            'json' => [
                'appid' => $config['app_id'] ?? '',
                'secret' => $config['app_secret'] ?? '',
                'grant_type' => 'client_credential',
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        
        
//   dd($body);     
        return $body['data']['access_token'] ?? '';
    }

    /**
     * 查询抖音标签组信息
     * @param string $accessToken
     * @return array
     */
    public function getTagGroup(string $accessToken): array
    {
        // dd($accessToken);
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
}