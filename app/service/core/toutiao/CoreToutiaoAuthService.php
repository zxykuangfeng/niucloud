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
        $client = new Client();
        $response = $client->post('https://open.douyin.com/api/apps/v2/jscode2session', [
            'json' => [
                'appid' => $config['app_id'] ?? '',
                'secret' => $config['app_secret'] ?? '',
                'code' => (string)$code
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserPhoneNumber(int $site_id, string $code)
    {
        // 未实现手机号获取，可根据抖音开放平台接口补充
        return [];
    }
}