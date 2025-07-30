<?php
namespace core\pay;

use core\exception\PayException;
use GuzzleHttp\Client;
use think\Response;

class Douyinpay extends BasePay
{
    public function initialize(array $config = [])
    {
        $this->config = $config;
    }

    public function web(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    public function wap(array $params)
    {
        return $this->web($params);
    }

    public function app(array $params)
    {
        return $this->createOrder($params);    
    }

    public function mini(array $params)
    {
        return $this->createOrder($params);
    }

    public function pos(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    public function scan(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    public function transfer(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    public function mp(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    public function close(string $out_trade_no)
    {
        return true;
    }

    public function refund(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    public function notify(string $action, callable $callback)
    {
        return Response::create('success', 'html');
    }

    public function getOrder(array $params)
    {
        return [];
    }

    public function getRefund(string $out_trade_no, ?string $refund_no)
    {
        return [];
    }

    public function getTransfer(string $transfer_no, $out_transfer_no = '')
    {
        return [];
    }

    public function transferCancel(array $params)
    {
        return [];
    }

 /**
     * 创建抖音订单
     * @param array $params
     * @return array
     * @throws PayException
     */
    private function createOrder(array $params)
    {
        // dd($this->config);
        if (empty($this->config['app_id']) || empty($this->config['merchant_id']) || empty($this->config['secret_key'])) {
            throw new PayException('DOUYINPAY_CONFIG_ERROR');
        }

        $order = [
            'app_id' => $this->config['app_id'],
            'merchant_id' => $this->config['merchant_id'],
            'out_order_no' => $params['out_trade_no'],
            'total_amount' => (int)$params['money'],
            'subject' => $params['body'],
            'timestamp' => time(),
            'sign_type' => 'MD5',
            'valid_time' => $params['valid_time'] ?? 600,
            'notify_url' => $this->config['notify_url'] ?? '',
            'body'=>'商品',
        ];

        if (!empty($params['openid'])) {
            $order['openid'] = $params['openid'];
        }

        $order['sign'] = $this->sign($order);

        $client = new Client();
        $response = $client->post("https://developer.toutiao.com/api/apps/ecpay/v1/create_order" ?? 'https://developer.toutiao.com/api/apps/ecpay/v1/create_order', [
            'json' => $order,
        ]);
        $raw = $response->getBody()->getContents();

        $result = json_decode($response->getBody()->getContents(), true);
        
        if (($result['err_no'] ?? 0) != 0) {
            throw new PayException($result['err_tips'] ?? 'douyin pay error');
        }
        return $result['data'] ?? $result;
    }

    private function sign($map)
    {
        $rList = [];
        foreach ($map as $k => $v) {
            if ($k == 'other_settle_params' || $k == 'app_id' || $k == 'sign' || $k == 'thirdparty_id') {
                continue;
            }

            $value = trim(strval($v));
            if (is_array($v)) {
                $value = $this->arrayToStr($v);
            }

            $len = strlen($value);
            if ($len > 1 && substr($value, 0, 1) == '"' && substr($value, $len - 1) == '"') {
                $value = substr($value, 1, $len - 2);
            }
            $value = trim($value);
            if ($value == '' || $value == 'null') {
                continue;
            }
            $rList[] = $value;
        }
        $rList[] = $this->config['secret_key'];
        sort($rList, SORT_STRING);
        return md5(implode('&', $rList));
    }

    private function arrayToStr($map)
    {
        $isMap = $this->isArrMap($map);

        $result = '';
        if ($isMap) {
            $result = 'map[';
        }

        $keyArr = array_keys($map);
        if ($isMap) {
            sort($keyArr);
        }

        $paramsArr = [];
        foreach ($keyArr as $k) {
            $v = $map[$k];
            if ($isMap) {
                if (is_array($v)) {
                    $paramsArr[] = sprintf('%s:%s', $k, $this->arrayToStr($v));
                } else {
                    $paramsArr[] = sprintf('%s:%s', $k, trim(strval($v)));
                }
            } else {
                if (is_array($v)) {
                    $paramsArr[] = $this->arrayToStr($v);
                } else {
                    $paramsArr[] = trim(strval($v));
                }
            }
        }

        $result = sprintf('%s%s', $result, join(' ', $paramsArr));
        if (!$isMap) {
            $result = sprintf('[%s]', $result);
        } else {
            $result = sprintf('%s]', $result);
        }

        return $result;
    }

    private function isArrMap($map)
    {
        foreach ($map as $k => $v) {
            if (is_string($k)) {
                return true;
            }
        }

        return false;
    }
    
        /**
     * 获取 tt.requestOrder 所需参数
     * @param array $params
     * @return array
     * @throws PayException
     */
   public function getRequestOrderParams(array $params)
{
    $outOrderNo = $params['outOrderNo'] ?? ($params['out_trade_no'] ?? '');
    $totalAmount = $params['totalAmount'] ?? ($params['money'] ?? 0);
    $title = $params['title'] ?? ($params['body'] ?? '商品名称');
    
    
    // $outOrderNo = '20250728590761576026112';
    // 金额转为整数，单位为“分”
    if (!is_numeric($totalAmount)) {
        $totalAmount = 0;
    } else {
        $totalAmount = (int) bcmul($totalAmount, 100);
    }

    // 默认图片列表
    $imageList = $params['imageList'] ?? [];

    // 构造 skuList
    $skuList = $params['skuList'] ?? [
        [
            'skuId' => $outOrderNo,
            'title' => $title,
            'quantity' => 1,
            'price' => $totalAmount,
            'type' => 0,
            'imageList' => $imageList
        ]
    ];

    // ✅ 构造 orderEntrySchema，必须是对象，必须包含 path 字段
    $orderEntrySchema = [
    'path' => 'pages/order/detail',
    // 'params' => '{"orderId":"' . $outOrderNo . '"}',
    // 'query' => "orderId={$outOrderNo}"

];

    // 构造完整 data
    $data = [
        'skuList' => $skuList,
        'outOrderNo' => $outOrderNo,
        'totalAmount' => $totalAmount,
        'payExpireSeconds' => $params['payExpireSeconds'] ?? ($params['valid_time'] ?? 600),
        'payNotifyUrl' => 'https://carplus.ycwlgs.com/api/pay/notify/100001/douyin/douyinpay/pay',
        'orderEntrySchema' => $orderEntrySchema
    ];
    $timestamp = time();
    $nonceStr = $this->randStr(10);
    // $keyVersion = 2;
    
    $timestamp = 1753778759;
$nonceStr = 'XU2YVY6G4E';
$keyVersion = 2;
     $dataStr = '';
    $byteAuthorization = $this->getByteAuthorization(
        file_get_contents(__DIR__ . '/private.pem'),
        $data,
        'ttb99ecf2326972f8a01',
        $nonceStr,
        $timestamp,
        $keyVersion,
        $dataStr // 传入引用变量，获取最终的 data 字符串
    );

    return [
        'data' => $dataStr, // ✅ 返回的是 JSON 字符串，而非 PHP 数组
        'byteAuthorization' => $byteAuthorization,
        'timestamp' => $timestamp,
        'nonceStr' => $nonceStr,
        'key_version' => $keyVersion
    ];
}
    
    
        private function randStr($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $str;
    }

private function getByteAuthorization($privateKeyStr, $data, $appId, $nonceStr, $timestamp, $keyVersion, &$dataStrOut = null)
{
    $privateKey = openssl_pkey_get_private($privateKeyStr);
    if (!$privateKey) {
        throw new PayException('DOUYINPAY_PRIVATE_KEY_ERROR');
    }

    $dataStr = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataStrOut = $dataStr; 

    $signature = $this->getSignature('POST', '/api/trade_basic/v1/user/order_create', $timestamp, $nonceStr, $dataStr, $privateKey);

    return sprintf(
        'SHA256-RSA2048 appid="%s",nonce_str="%s",timestamp="%s",key_version="%s",signature="%s"',
        $appId,
        $nonceStr,
        $timestamp,
        $keyVersion,
        $signature
    );
}

   private function getSignature($method, $url, $timestamp, $nonce, $dataStr, $privateKey)
{
    $targetStr = $method . "\n" . $url . "\n" . $timestamp . "\n" . $nonce . "\n" . $dataStr . "\n";

    // 执行签名
    openssl_sign($targetStr, $sign, $privateKey, OPENSSL_ALGO_SHA256);
    $signature = base64_encode($sign);

    // 写入日志，包括原始串和生成的签名
    $logPath = __DIR__ . '/log.log';
    file_put_contents($logPath,
        "------\n" .
        date('Y-m-d H:i:s') . "\n" .
        "[签名原始串]:\n" . $targetStr . "\n" .
        "[签名结果]:\n" . $signature . "\n\n",
        FILE_APPEND
    );

    return $signature;
}
}