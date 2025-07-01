<?php
namespace app\service\core\toutiao;

use app\dict\sys\ConfigKeyDict;
use app\model\sys\SysConfig;
use app\service\core\sys\CoreConfigService;
use core\base\BaseCoreService;
use think\Model;

class CoreToutiaoConfigService extends BaseCoreService
{
    public function getToutiaoConfig(int $site_id)
    {
        $info = (new CoreConfigService())->getConfig($site_id, ConfigKeyDict::TOUTIAO)['value'] ?? [];
        return [
            'app_id' => $info['app_id'] ?? '',
            'app_secret' => $info['app_secret'] ?? ''
        ];
    }

    public function setToutiaoConfig(int $site_id, array $data)
    {
        $config = [
            'app_id' => $data['app_id'] ?? '',
            'app_secret' => $data['app_secret'] ?? ''
        ];
        return (new CoreConfigService())->setConfig($site_id, ConfigKeyDict::TOUTIAO, $config);
    }
}