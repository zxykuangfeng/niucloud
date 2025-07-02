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

namespace app\service\admin\toutiao;

use app\dict\sys\ConfigKeyDict;
use app\service\core\sys\CoreConfigService;
use core\base\BaseAdminService;

class ToutiaoServerService extends BaseAdminService
{
    /**
     * 接收今日头条 component_ticket
     * @return string
     */
    public function receiveTicket()
    {
        $params = request()->param();
        $timestamp = $params['timestamp'] ?? '';
        $nonce = $params['nonce'] ?? '';
        $signature = $params['MsgSignature'] ?? '';
        $data = json_decode(request()->getContent(), true);
        $encrypt = $data['Encrypt'] ?? '';

        $config = (new CoreConfigService())->getConfigValue(0, ConfigKeyDict::TOUTIAO);
        $token = $config['token'] ?? '';
        $aesKey = $config['aes_key'] ?? '';

        if ($encrypt && $this->verifySignature($token, $timestamp, $nonce, $encrypt, $signature)) {
            $payload = $this->decrypt($encrypt, $aesKey);
            $payloadArr = json_decode($payload, true);
            if (!empty($payloadArr['Ticket'])) {
                (new CoreConfigService())->setConfig(0, ConfigKeyDict::TOUTIAO_TICKET, ['ticket' => $payloadArr['Ticket']]);
            }
        }

        return 'success';
    }

    /**
     * 校验签名
     * @param string $token
     * @param string $timestamp
     * @param string $nonce
     * @param string $encrypt
     * @param string $signature
     * @return bool
     */
    private function verifySignature(string $token, string $timestamp, string $nonce, string $encrypt, string $signature): bool
    {
        $arr = [$token, $timestamp, $nonce, $encrypt];
        sort($arr, SORT_STRING);
        $sign = sha1(implode('', $arr));
        return $sign === $signature;
    }

    /**
     * 解密
     * @param string $encrypt
     * @param string $aesKey
     * @return string
     */
    private function decrypt(string $encrypt, string $aesKey): string
    {
        $key = base64_decode($aesKey . '=');
        $iv = substr($key, 0, 16);
        $decrypted = openssl_decrypt(base64_decode($encrypt), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return (string)$decrypted;
    }
}