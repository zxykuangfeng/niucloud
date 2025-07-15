<?php
namespace app\service\admin\toutiao;

use app\dict\sys\ConfigKeyDict;
use app\service\core\sys\CoreConfigService;
use core\base\BaseAdminService;
use think\facade\Db;

class ToutiaoServerService extends BaseAdminService
{
    /**
     * 接收今日头条 component_ticket（调试版）
     * @return string
     */
public function receiveTicket()
{
    // 从抖音回调请求中获取参数
    $params = request()->param();
    $timestamp = $params['timestamp'] ?? '';
    $nonce = $params['nonce'] ?? '';
    $signature = $params['MsgSignature'] ?? '';

    // 获取加密数据体
    $requestContent = request()->getContent();
    $data = json_decode($requestContent, true);
    $encrypt = $data['Encrypt'] ?? '';

    // 写入原始日志
    $this->insertLog('抖音原始数据：' . $requestContent);

    // 获取平台配置
    $config = (new CoreConfigService())->getConfigValue(100001, ConfigKeyDict::TOUTIAO);
    $token = $config['token'] ?? '';
    $aesKey = $config['aes_key'] ?? '';

    // if ($encrypt && $this->verifySignature($token, $timestamp, $nonce, $encrypt, $signature)) {
        $decrypted = $this->decrypt($encrypt, $aesKey);
        $json = $this->extractToutiaoJson($decrypted);

        $this->insertLog('解密后 JSON：' . $json);

        $payloadArr = json_decode($json, true);
        if (!empty($payloadArr['Ticket'])) {
            (new CoreConfigService())->setConfig(100001, ConfigKeyDict::TOUTIAO_TICKET, [
                'ticket' => $payloadArr['Ticket']
            ]);
            $this->insertLog('写入 ticket 成功：' . $payloadArr['Ticket']);
        } else {
            $this->insertLog('未找到 Ticket 字段');
        }
    // } else {
    //     $this->insertLog('签名验证失败');
    // }

    return 'success';
}
    /**
     * 日志记录
     */
    private function insertLog($content)
    {
        Db::name('log')->insert([
            'content' => mb_substr($content, 0, 255),
            'create_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * 校验签名
     */
    private function verifySignature(string $token, string $timestamp, string $nonce, string $encrypt, string $signature): bool
    {
        $arr = [$token, $timestamp, $nonce, $encrypt];
        sort($arr, SORT_STRING);
        $sign = sha1(implode('', $arr));
        return $sign === $signature;
    }

    /**
     * 解密数据
     */
    private function decrypt(string $encrypt, string $aesKey): string
    {
        $key = base64_decode($aesKey . '=');
        $iv = substr($key, 0, 16);
        return openssl_decrypt(base64_decode($encrypt), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * 提取抖音平台解密数据中的 JSON 部分
     */
   private function extractToutiaoJson(string $decrypted): string
{
    // 正则提取 JSON 大括号内容
    if (preg_match('/\{.*\}/s', $decrypted, $matches)) {
        return $matches[0]; // 提取第一个匹配的大括号内容
    }
    return '';
}
}
