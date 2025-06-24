<?php
namespace core\pay;

use core\exception\PayException;
use think\Response;

class Douyinpay extends BasePay
{
    protected function initialize(array $config = [])
    {
        $this->config = $config;
    }

    protected function web(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    protected function wap(array $params)
    {
        return $this->web($params);
    }

    protected function app(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    protected function mini(array $params)
    {
        return $this->app($params);
    }

    protected function pos(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    protected function scan(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    protected function transfer(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    protected function mp(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    protected function close(string $out_trade_no)
    {
        return true;
    }

    protected function refund(array $params)
    {
        throw new PayException('DOUYINPAY_NOT_SUPPORTED');
    }

    protected function notify(string $action, callable $callback)
    {
        return Response::create('success', 'html');
    }

    protected function getOrder(array $params)
    {
        return [];
    }

    protected function getRefund(string $out_trade_no, ?string $refund_no)
    {
        return [];
    }

    protected function getTransfer(string $transfer_no, $out_transfer_no = '')
    {
        return [];
    }

    protected function transferCancel(array $params)
    {
        return [];
    }
}