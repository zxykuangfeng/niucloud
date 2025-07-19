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
        $config['app_id'] = 'tt554123fcca4821c001';
          $config['app_secret'] = '0c8084ca6752ce2082d8fc375769afbd43d32226';
        //现在的模板理发预约 
        //      $config['app_id'] = 'ttb99ecf2326972f8a01';
        //   $config['app_secret'] = '94ad32ae324a0776d1cb2bf2d10388f171926598';
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
}