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
    'params' => json_encode(['orderId' => $outOrderNo], JSON_UNESCAPED_UNICODE),
    'query' => 'orderId=' . $outOrderNo
];

    // 构造完整 data
    $data = [
        'skuList' => $skuList,
        'outOrderNo' => $outOrderNo,
        'totalAmount' => $totalAmount,
        'payExpireSeconds' => $params['payExpireSeconds'] ?? ($params['valid_time'] ?? 600),
        'payNotifyUrl' => $params['payNotifyUrl'] ?? ($this->config['notify_url'] ?? ''),
        'orderEntrySchema' => $orderEntrySchema
    ];

    $dataStr = json_encode($data, JSON_UNESCAPED_UNICODE);
    $timestamp = time();
    $nonceStr = $this->randStr(10);
    $keyVersion = $this->config['key_version'] ?? '1';
    $byteAuthorization = $this->getByteAuthorization(
        $this->config['private_key'] ?? '',
        $dataStr,
        $this->config['app_id'] ?? '',
        $nonceStr,
        $timestamp,
        $keyVersion
    );

    return [
        'data' => $dataStr, // 保持字符串，供前端 JSON.parse 或直接传入
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

    private function getByteAuthorization($privateKeyStr, $data, $appId, $nonceStr, $timestamp, $keyVersion)
    {
        // $privateKey = openssl_pkey_get_private($privateKeyStr);
        // if (!$privateKey) {
        //     throw new PayException('DOUYINPAY_PRIVATE_KEY_ERROR');
        // }
        
        $privateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAukbZSBLPo1g7YK5j8BiX6YG++HEkHKMhfX7EvpXyJIbg5QSy
CEswgDTRMpNCQs/+Vt2BdJkSCwoKLjSkijWVLobsOewUBOncDcJKwztg5aOjaxaj
8ig0aWIWnS994L0lq9OTf80cvaWOAMwAshZ44OiSYI1jObS1AaGFgokTeMasUKbR
q8CLEfevf+lh2eEQZCPllYLYBeg5VTR+GHR20w6Mt6GpXMn4In8i5uK2wIsxHBca
mOBtTGFWIT7NJYOZ+vUTi07wQvT4dhiljaD8aN8pKeqI0Er77xG3hQurA1DuJNRN
eSg2vr3b3ZVZpU/I/LVaOoa0LeM2+0oac1pc7wIDAQABAoIBAG5lPrBwNY5g5A/Q
SnoomR9SPZOPug8evuJZFtC8nNz48p/HwJsZtIzGwJRwoXxnOBzS+b3YWdEhCheI
K1udleQVIjrRpquizT96PkSmFC8EY/07Vb5WBXnPIAfX9YoTjxfeNDQxoy7hxt7C
CEeC+fCQ5O9D2+J/LibhSvaptliEopZ0/Eb0MQDc3I+d8RWbRmmt2BKDU5BmNqN/
O6yzQqvPflPLroNDOI8MJjIn6YFkpu3OT53U4Vh+U8Y8mPryBzMc+r70d6mrPia9
Yym64DWacHsHzCbiFr4QuMHsQ7TXuEE/uDXNGyh06S0w5fYzP694jpjf5L0U8eF8
7nBVcAECgYEA6D7e6xfn2bYBxlNHZaHcBXTYxlayScPWhkaZVscgjSZZEHdh5uNf
66+C3aBN+yk3vaaKE8QA1rStDITpyt4M9My4SPQkH7H/wIwTXHhrmKBJVdpVX8CJ
TOiKSBefEDyoEpl8zjaj4uC87mYecHPUV9x9bj51Yz17lQuhnQFfNwECgYEAzVRT
7miAAK7q6E0Y8APXgs7jwuoOeO5S04blMNXy17M7qryEttP90r+fYUk627HO40gQ
VtjZ5iAd4DpOXsTi4lACYa+xBP3FjW2A+bs2e/upjwsV7iv6b5dhKWK9Zrmc6D7i
e0TNTGVOYD6A2x7cXzuVUV+T1icgZv7MnCbRA+8CgYAEvOudTjKLrXvhyOcm+qNb
dSPLAA/JE3a37I7KR2uxlXuxq03TAQZ+72izDscofZHGi7Q7bP87Yho5rCh83ATV
pauIyXpHL0Fxcyod89L2HScB2l9tgacLa58Ok3TKRwKCxqDWMCUtxrnz/x9V2fcW
B8iDTEDm0mVICCG260U/AQKBgG+OzJcwkbNNVfXmxZDKFZJNg/PHpRtRKREiLm3t
ICbPi7CFAnovDa4uTJLX7bGllqln4vC8mw7sDi9gnmnhAQBCxjh682up0Wa4wyVr
8PtFzWcZd83SeRueHL0Wl58zY6vPVs/wnrZOKFokO3BSARuAzOzMSA9HbNRoxZRl
hpRjAoGBAKcEUqxTaWwBw9A5WEQDR0EfCnVsQNiV/wEo98m3gbtPx1DxyhSP51p+
HMIyPCuxYAKgFf60tB908WPhHHZHJ9afhVMqZc3SWIYhcE3kH/3PHFVCItqaEW5/
LUQNwmEalB4U6fqFHTVW2f7uUuqQAUgRrXgmtqwXTUppAGb5VXkK
-----END RSA PRIVATE KEY-----
EOD;

$method = 'POST';
$url = '/api/trade_basic/v1/user/order_create';
$timestamp = '1753688758';
$nonce = 'XU2YVY6G4E';

$data = [
    'skuList' => [[
        'skuId' => '20250728590761576026112',
        'title' => '清洁',
        'quantity' => 1,
        'price' => 5000,
        'type' => 0,
        'imageList' => [],
    ]],
    'outOrderNo' => '20250728590761576026112',
    'totalAmount' => 5000,
    'payExpireSeconds' => 600,
    'payNotifyUrl' => 'https://carplus.ycwlgs.com/api/pay/notify/100001/douyin/douyinpay/pay',
    'orderEntrySchema' => [
        'path' => 'pages/order/detail',
        'params' => json_encode(['orderId' => '20250728590761576026112'], JSON_UNESCAPED_UNICODE),
        'query' => 'orderId=20250728590761576026112',
    ]
];

$signature = $this->getSignature($method, $url, $timestamp, $nonce, $data, $privateKey);

dd($signature);
        return sprintf(
            'SHA256-RSA2048 appid=%s,nonce_str=%s,timestamp=%s,key_version=%s,signature=%s',
            $appId,
            $nonceStr,
            $timestamp,
            $keyVersion,
            $signature
        );
    }

 private function getSignature($method, $url, $timestamp, $nonce, $data, $privateKey)
{
 $text = 'POST\n/abc\n1680835692\ngjjRNfQlzoDIJtVDOfUe\n{\"eventTime\":1677653869000,\"status\":102}\n';
 $priKey = file_get_contents("./private_key.pem");
  $privateKey = openssl_get_privatekey($priKey, '');
  
  openssl_sign($text, $sign, $privateKey, OPENSSL_ALGO_SHA256);

    $sign = base64_encode($sign);
    
    dd($sign);
    return $sign;
}
    
    
    
    
    
}