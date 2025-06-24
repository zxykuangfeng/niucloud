<?php
// +----------------------------------------------------------------------
// | Niucloud-admin 企业快速开发的saas管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace app\service\admin\weapp;

use app\dict\common\CommonDict;
use app\model\sys\SysConfig;
use app\service\core\weapp\CoreWeappConfigService;
use app\service\core\wxoplatform\CoreOplatformService;
use core\base\BaseAdminService;
use core\exception\CommonException;
use think\Model;
use app\service\admin\wxoplatform\WeappVersionService;

/**
 * 微信小程序设置
 * Class WeappConfigService
 * @package app\service\admin\weapp
 */
class WeappConfigService extends BaseAdminService
{
    /**
     * 获取配置信息
     * @return array|null
     */
    public function getWeappConfig()
    {
        $config_info = (new CoreWeappConfigService())->getWeappConfig($this->site_id);
        foreach ($config_info as $k => $v) {
            if ($v !== '' && in_array($k, ['app_secret', 'encoding_aes_key'])) {
                $config_info[$k] = CommonDict::ENCRYPT_STR;
            }
        }
        $config_info['domain'] =  [
            'requestdomain' => '',
            'wsrequestdomain' => '',
            'uploaddomain' => '',
            'downloaddomain' => '',
            'tcpdomain' => '',
            'udpdomain' => ''
        ];
        if ($config_info[ 'is_authorization' ] == 1) {
            try {
                $domain = CoreOplatformService::getDomain($this->site_id);
                foreach ($config_info[ 'domain' ] as $k => $v) {
                    $config_info[ 'domain' ][ $k ] = isset($domain[$k]) && is_array($domain[$k]) ? implode(';', $domain[$k]) : '';
                }
            } catch (\Exception $e) {
            }
        }
        return array_merge($config_info, $this->getWeappStaticInfo());

    }

    /**
     * 设置配置
     * @param array $data
     * @return SysConfig|bool|Model
     */
    public function setWeappConfig(array $data){
        $config = (new CoreWeappConfigService())->getWeappConfig($this->site_id);
        foreach ($data as $k => $v) {
            if ($v == CommonDict::ENCRYPT_STR) {
                $data[$k] = $config[$k];
            }
        }
        return (new CoreWeappConfigService())->setWeappConfig($this->site_id, $data);
    }

    /**
     *查询微信小程序需要的静态信息
     * @return array
     */
    public function getWeappStaticInfo(){
        $domain = request()->domain();
        $domain = str_replace('http://', 'https://', $domain);
        return [
            'serve_url' => (string)url('/api/weapp/serve/'.$this->site_id, [],'',true),
            'request_url' => $domain,
            'socket_url'   => "wss://".request()->host(),
            'upload_url'  => $domain,
            'download_url'   => $domain,
            'upload_ip' => gethostbyname('oss.niucloud.com')
        ];
    }

    /**
     * 设置域名
     * @param $data
     * @return void
     */
    public function setDomain($data){
        foreach ($data as $k => $v) {
            $data[$k] = empty($v) ? [] : explode(';', $v);
        }
        $result = CoreOplatformService::setDomain($this->site_id, $data);
        if (isset($result['errcode']) && $result['errcode'] != 0) {
            throw new CommonException($result['errmsg']);
        }
    }

    /**
     * 获取隐私协议
     * @return array
     */
    public function getPrivacySetting(){
        return CoreOplatformService::getPrivacySetting($this->site_id);
    }

    /**
     * 设置隐私协议
     * @return array
     */
    public function setPrivacySetting($data){
        $data['privacy_ver'] = 1;
        CoreOplatformService::setPrivacySetting($this->site_id, $data);
        // 提交小程序
        try {
            (new WeappVersionService())->siteWeappCommit();
        } catch (\Exception $e) {
        }
    }

}
