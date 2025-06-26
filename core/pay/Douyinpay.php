<?php
namespace core\pay;

use core\exception\PayException;
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
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    public function mini(array $params)
    {
        return $this->app($params);
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
}