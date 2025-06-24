<?php
// +----------------------------------------------------------------------
// | Niucloud-admin 企业快速开发的多应用管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace addon\zzhc\app\service\core\vip;

use app\service\core\sys\CoreConfigService;
use core\base\BaseCoreService;


/**
 * VIP设置服务层
 */
class CoreVipConfigService extends BaseCoreService
{
    //系统配置文件
    public $core_config_service;

    public function __construct()
    {
        parent::__construct();
        $this->core_config_service = new CoreConfigService();
    }

    /**
     * 设置VIP配置
     * @param array $params
     * @return array
     */
    public function setConfig($params)
    {
        $site_id = $params['site_id'];

        $data = [
            'is_enable' => $params['is_enable'],
            'discount' => $params['discount'],
            'banner' => $params['banner'],
            'statement' => $params['statement'],
        ];

        $this->core_config_service->setConfig($site_id, 'ZZHC_VIP_CONFIG', $data);

        return true;
    }

   

    /**
     * 获取VIP配置
     * @param int $id
     * @return array
     */
    public function getConfig(int $site_id)
    {
        $data = (new CoreConfigService())->getConfigValue($site_id, 'ZZHC_VIP_CONFIG');
        if (empty($data)) {
            $data['is_enable'] = 1;
            $data['discount'] = 8.8;
            $data['banner'] = 'addon/zzhc/vip_banner.png';
            $data['statement'] = 'VIP会员卡开通后立即生效，有效期内所有门店所有项目8.8折';
        } 
        return $data;
    }

    
}

