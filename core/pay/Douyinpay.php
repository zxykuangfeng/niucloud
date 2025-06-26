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
        ];

        if (!empty($params['openid'])) {
            $order['openid'] = $params['openid'];
        }

        $order['sign'] = $this->sign($order);

        $client = new Client();
        $response = $client->post($this->config['gateway'] ?? 'https://open.douyin.com/api/apps/ecpay/v1/create_order', [
            'json' => $order,
        ]);

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
}