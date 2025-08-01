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

namespace app\service\core\pay;

use app\dict\pay\OnlinePayDict;
use app\dict\pay\PayDict;
use app\dict\pay\PaySceneDict;
use app\job\pay\PayReturnTo;
use app\model\pay\Pay;
use core\base\BaseCoreService;
use core\exception\PayException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\facade\Log;
use think\Model;
use Throwable;

/**
 * 支付服务层
 * Class CorePayService
 * @package app\service\core\pay
 */
class CorePayService extends BaseCoreService
{
    protected $pay_event;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Pay();
        $this->pay_event = new CorePayEventService();
    }

    /**
     * 创建支付单据
     * @param $site_id
     * @param string $main_type
     * @param int $main_id
     * @param float $money
     * @param string $trade_type 业务类型
     * @param int $trade_id 业务id
     * @param string $body
     * @return string|null
     */
    public function create($site_id, string $main_type, int $main_id, float $money, string $trade_type, int $trade_id, string $body)
    {
        $out_trade_no = create_no();
        $data = array(
            'site_id' => $site_id,
            'money' => $money,
            'trade_type' => $trade_type,
            'trade_id' => $trade_id,
            'body' => $body,
            'out_trade_no' => $out_trade_no,
            'main_id' => $main_id,
            'from_main_id' => $main_id,
            'main_type' => $main_type
        );
        $this->model->create($data);
        return $out_trade_no;
    }


    /**
     * 通过业务类型和id查询有效的支付单据
     * @param int $site_id
     * @param string $trade_type
     * @param int $trade_id
     * @return Pay|array|mixed|Model
     */
    public function findPayInfoByTrade(int $site_id, string $trade_type, int $trade_id)
    {
        $where = array(
            [ 'site_id', '=', $site_id ],
            [ 'trade_type', '=', $trade_type ],
            [ 'trade_id', '=', $trade_id ],
            [ 'status', '<>', PayDict::STATUS_CANCLE ],///不查询已取消的单据
        );
        return $this->model->where($where)->append([ 'type_name', 'status_name' ])->findOrEmpty();
    }

    /**
     * 通过交易流水号查询支付
     * @param int $site_id
     * @param string $out_trade_no
     * @return Pay|array|mixed|Model
     */
    public function findPayInfoByOutTradeNo(int $site_id, string $out_trade_no)
    {
        $where = array(
            [ 'site_id', '=', $site_id ],
            [ 'out_trade_no', '=', $out_trade_no ]
        );
        return $this->model->where($where)->append([ 'type_name', 'status_name' ])->findOrEmpty();
    }

    /**
     * 通过交易流水号查询支付信息以及支付方式()
     * @param int $site_id
     * @param string $out_trade_no
     * @param string $channel
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getInfoByOutTradeNo(int $site_id, string $out_trade_no, string $channel)
    {
        $where = array(
            [ 'site_id', '=', $site_id ],
            [ 'out_trade_no', '=', $out_trade_no ]
        );
        $pay = $this->model->where($where)->append([ 'type_name', 'status_name' ])->findOrEmpty()->toArray();
        if (!empty($pay)) {
            //todo  校验场景控制支付方式
            $pay[ 'pay_type_list' ] = array_values(( new CorePayChannelService() )->getAllowPayTypeByChannel($site_id, $channel, $pay[ 'trade_type' ]));
        }
        return $pay;
    }

    /**
     * 通过业务类型和id查询支付状态
     * @param int $site_id
     * @param string $trade_type
     * @param int $trade_id
     * @return bool
     */
    public function getPayStatusByTrade(int $site_id, string $trade_type, int $trade_id)
    {
        $where = array(
            [ 'site_id', '=', $site_id ],
            [ 'trade_type', '=', $trade_type ],
            [ 'trade_id', '=', $trade_id ],
        );
        $pay_info = $this->model->field('status')->where($where)->findOrEmpty();
        if ($pay_info->isEmpty()) {
            throw new PayException('TRADE_NOT_EXIST');//支付单据不存在
        } else {
            if ($pay_info[ 'status' ] == PayDict::STATUS_FINISH) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 通过交易信息获取支付单据
     * @param int $site_id
     * @param string $trade_type
     * @param string $trade_id
     * @param string $channel
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getInfoByTrade(int $site_id, string $trade_type, string $trade_id, string $channel, string $scene = '')
    {
        $pay = $this->findPayInfoByTrade($site_id, $trade_type, $trade_id);
        if ($pay->isEmpty()) {
            //创建新的支付单据
            $pay = $this->createByTrade($site_id, $trade_type, $trade_id);
        }
        if (!is_array($pay)) {
            $pay = $pay->toArray();
        }
        if (!empty($pay)) {
            //todo  校验场景控制支付方式
            $pay_type_list = ( new CorePayChannelService() )->getAllowPayTypeByChannel($site_id, $channel, $pay[ 'trade_type' ]);
            //找朋友帮忙付时不支持找朋友帮忙付
            if(!empty($pay_type_list) && !empty($pay_type_list[PayDict::FRIENDSPAY]) && $scene == PaySceneDict::FRIENDSPAY){
                $pay[ 'config' ] = $pay_type_list[PayDict::FRIENDSPAY]['config'];
                unset($pay_type_list[PayDict::FRIENDSPAY]);
            }
            $pay[ 'pay_type_list' ] = array_values($pay_type_list);
        }
        return $pay;
    }

    /**
     * 获取支付方法
     * @param int $site_id
     * @param string $trade_type
     * @param string $channel
     * @return array|array[]
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getPayTypeByTrade(int $site_id, string $trade_type, string $channel)
    {
        //todo  校验场景控制支付方式
        return array_values(( new CorePayChannelService() )->getAllowPayTypeByChannel($site_id, $channel, $trade_type));
    }

    /**
     * 去支付
     * @param $site_id
     * @param $trade_type
     * @param $trade_id
     * @param $type
     * @param $channel
     * @param string $openid
     * @param string $return_url
     * @param string $quit_url
     * @param string $buyer_id
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function pay($site_id, $trade_type, $trade_id, $type, $channel, string $openid, string $return_url = '', string $quit_url = '', string $buyer_id = '', string $voucher = '', $member_id = 0)
    {
        //检测并创建支付单据
        $pay = $this->checkOrCreate($site_id, $trade_type, $trade_id);
        $out_trade_no = $pay[ 'out_trade_no' ];
        $money = $pay[ 'money' ];
        $body = $pay[ 'body' ];
        $trade_type = $pay[ 'trade_type' ];
        if (!in_array($type, array_column(( new CorePayChannelService() )->getAllowPayTypeByChannel($site_id, $channel, $trade_type), 'key'))) throw new PayException('PAYMENT_METHOD_NOT_SCENE');//场景不支持
        if ($member_id != 0) {
            //更新付款人id
            $pay_info = $this->findPayInfoByTrade($site_id, $trade_type, $trade_id);
            $pay_info->save([
                'main_id' => $member_id
            ]);
        }
        $pay_result = $this->pay_event->init($site_id, $channel, $type)->pay($out_trade_no, $money, $body, $return_url, $quit_url, $buyer_id, $openid ?? '', $voucher);
        //todo  特殊支付方式会直接返回支付状态,状态如果为已支付会直接支付
        if (!empty($pay_result[ 'status' ]) && $pay_result[ 'status' ] == PayDict::STATUS_FINISH) {
            $pay->save([
                'channel' => $channel
            ]);
            $this->paySuccess($site_id, [
                'status' => PayDict::STATUS_FINISH,
                'type' => $type,
                'out_trade_no' => $out_trade_no
            ]);
            return true;
        }

        // 如果是线下支付
        if ($type == PayDict::OFFLINEPAY) {
            //将支付设置为支付中
            $pay->save(
                [
                    'type' => $type,
                    'status' => PayDict::STATUS_AUDIT,
                    'channel' => $channel
                ]
            );
            event('OfflinePayAfter', [
                'trade_type' => $trade_type,
                'trade_id' => $trade_id,
                'out_trade_no' => $out_trade_no,
                'site_id' => $site_id
            ]);
        } else {
            //将支付设置为支付中
            $pay->save(
                [
                    'type' => $type,
                    'status' => PayDict::STATUS_ING,
                    'channel' => $channel
                ]
            );
            if (env('queue.state', true)) {
                PayReturnTo::dispatch([ 'site_id' => $site_id, 'out_trade_no' => $out_trade_no ], secs: 60);
            }
        }
        return $pay_result;
    }
    
    
        /**
     * 获取抖音 tt.requestOrder 参数
     * @param int $site_id
     * @param string $trade_type
     * @param int $trade_id
     * @param string $channel
     * @param string $openid
     * @param string $return_url
     * @param string $quit_url
     * @param string $buyer_id
     * @param string $voucher
     * @param int $member_id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function douyinRequestOrderParams(int $site_id, string $trade_type, int $trade_id, string $channel, string $openid = '', string $return_url = '', string $quit_url = '', string $buyer_id = '', string $voucher = '', int $member_id = 0)
    {
        $type = PayDict::DOUYINPAY;
        $pay = $this->checkOrCreate($site_id, $trade_type, $trade_id);
        $out_trade_no = $pay['out_trade_no'];
        $money = $pay['money'];
        $body = $pay['body'];
        $trade_type = $pay['trade_type'];
        
        if (!in_array($type, array_column((new CorePayChannelService())->getAllowPayTypeByChannel($site_id, $channel, $trade_type), 'key')))
            throw new PayException('PAYMENT_METHOD_NOT_SCENE');
//dd(222222222);
        if ($member_id != 0) {
            $pay_info = $this->findPayInfoByTrade($site_id, $trade_type, $trade_id);
            $pay_info->save(['main_id' => $member_id]);
        }

        $params = [
            'out_trade_no' => $out_trade_no,
            'money' => $money,
            'body' => $body,
            'return_url' => $return_url,
            'quit_url' => $quit_url,
            'buyer_id' => $buyer_id,
            'openid' => $openid,
            'voucher' => $voucher
        ];
     //    dd($params);
        $result = $this->pay_event->init($site_id, $channel, $type)->getRequestOrderParams($params);
        //  dd($result);
        $pay->save([
            'type' => $type,
            'status' => PayDict::STATUS_ING,
            'channel' => $channel
        ]);
        if (env('queue.state', true)) {
            PayReturnTo::dispatch(['site_id' => $site_id, 'out_trade_no' => $out_trade_no], secs: 60);
        }

        return $result;
    }

    /**
     * 检测支付单据  (如果不存在就创建,如果存在但支付中就尝试关闭)
     * @param $site_id
     * @param $trade_type
     * @param $trade_id
     * @return Pay|array|mixed|string|Model|null
     */
    public function checkOrCreate($site_id, $trade_type, $trade_id)
    {
        $pay = $this->findPayInfoByTrade($site_id, $trade_type, $trade_id);
        if ($pay->isEmpty()) {
            //创建新的支付单据
            $pay = $this->createByTrade($site_id, $trade_type, $trade_id);
        }
        if ($pay[ 'status' ] == PayDict::STATUS_FINISH) throw new PayException('PAY_SUCCESS');
//        if ($pay['status'] == PayDict::STATUS_CANCLE) throw new PayException('PAY_IS_REMOVE');
        if ($pay[ 'status' ] == PayDict::STATUS_ING || $pay[ 'status' ] == PayDict::STATUS_CANCLE) {
            if ($pay[ 'status' ] == PayDict::STATUS_ING) {
                //尝试关闭原有的支付单据
                $this->close($site_id, $pay->out_trade_no);
            }
            //创建新的支付单据
            $pay = $this->createByTrade($site_id, $trade_type, $trade_id);
        }
        return $pay;
    }

    /**
     * 通过业务信息创建支付单据
     * @param $site_id
     * @param $trade_type
     * @param $trade_id
     * @return Pay|array|mixed|Model
     */
    public function createByTrade($site_id, $trade_type, $trade_id)
    {
        //创建新的支付单据
        $data = array_values(array_filter(event('PayCreate', [ 'site_id' => $site_id, 'trade_type' => $trade_type, 'trade_id' => $trade_id ])))[ 0 ] ?? [];
        if (empty($data)) throw new PayException('PAY_NOT_FOUND_TRADE');//找不到可支付的交易

        if (isset($data[ 'status' ]) && $data[ 'money' ] == 0) {
            $data[ 'status' ] = PayDict::STATUS_FINISH;
            $data[ 'status_name' ] = PayDict::getStatus()[ $data[ 'status' ] ] ?? '';
            $data[ 'type' ] = PayDict::BALANCEPAY;
            $data[ 'type_name' ] = PayDict::getPayType()[ $data[ 'type' ] ][ 'name' ] ?? '';
            return $data;
        } else {
            $out_trade_no = $this->create($site_id, $data[ 'main_type' ], $data[ 'main_id' ], $data[ 'money' ], $data[ 'trade_type' ], $data[ 'trade_id' ], $data[ 'body' ]);
            return $this->findPayInfoByOutTradeNo($site_id, $out_trade_no);
        }
    }

    /**
     * 支付单据恢复到待支付
     * @param int $site_id
     * @param $pay_item
     * @return bool
     */
    public function returnTo(int $site_id, $pay_item)
    {
        if (is_object($pay_item)) {
            $pay = $pay_item;
            $out_trade_no = $pay->out_trade_no;
        } else {
            $out_trade_no = $pay_item;
            $pay = $this->findPayInfoByOutTradeNo($site_id, $out_trade_no);
        }
        if ($pay->isEmpty()) return true;
        if ($pay[ 'status' ] != PayDict::STATUS_ING) return true;
        if (empty($pay->type)) return true;
        //尝试取消或关闭第三方支付
        try {
            $close = $this->close($site_id, $out_trade_no);
        } catch (Throwable $e) {
            $close = false;
        }
        return $close;
    }

    /**
     * 关闭支付
     * @param int $site_id
     * @param string $out_trade_no
     * @return true
     */
    public function close(int $site_id, string $out_trade_no)
    {
        $pay = $this->findPayInfoByOutTradeNo($site_id, $out_trade_no);
        if ($pay->isEmpty()) throw new PayException('ALIPAY_TRANSACTION_NO_NOT_EXIST');
        if ($pay[ 'status' ] == PayDict::STATUS_CANCLE) return true;

        if (!in_array($pay[ 'status' ], [
            PayDict::STATUS_WAIT,
            PayDict::STATUS_ING
        ])) throw new PayException('TREAT_PAYMENT_IS_OPEN');
        if ($pay[ 'status' ] == PayDict::STATUS_ING) {
            if (!empty($pay->type)) {
                //尝试取消或关闭第三方支付
                $close = $this->pay_event->init($site_id, $pay->channel, $pay->type)->close($out_trade_no);
                if (!$close) {//有问题查询第三方订单详情
                    $order = $this->pay_event->init($site_id, $pay->channel, $pay->type)->getOrder($out_trade_no);
                    if (!empty($order)) {
                        if ($order[ 'status' ] == OnlinePayDict::SUCCESS) {//如果已支付,就将支付调整为已支付
                            $this->paySuccess($site_id, [
                                'out_trade_no' => $out_trade_no,
                                'type' => $pay->type
                            ]);
                            return false;
                        }
                    }
                }
            }
        }
        //支付关闭
        $this->payClose($site_id, [
            'out_trade_no' => $out_trade_no
        ]);
        return true;
    }

    /**
     * 通过业务信息关闭订单
     * @param int $site_id
     * @param string $trade_type
     * @param int $trade_id
     * @return true
     */
    public function closeByTrade(int $site_id, string $trade_type, int $trade_id)
    {
        $pay = $this->findPayInfoByTrade($site_id, $trade_type, $trade_id);
//        if ($pay->isEmpty()) throw new PayException('ALIPAY_TRANSACTION_NO_NOT_EXIST');
        if ($pay->isEmpty()) return true;
//        if ($pay['status'] == PayDict::STATUS_FINISH) throw new PayException('DOCUMENT_IS_PAID');//当前单据已支付
        if (!in_array($pay[ 'status' ], [
            PayDict::STATUS_WAIT,
            PayDict::STATUS_ING
        ])) throw new PayException('IS_PAY_REMOVE_NOT_RESETTING');//只有待支付可以关闭支付
        if (!$this->close($site_id, $pay[ 'out_trade_no' ])) {
            throw new PayException('DOCUMENT_IS_PAY_REMOVE');
        }
        return true;
    }

    /**
     * 支付完成
     * @param int $site_id
     * @param string $out_trade_no
     * @param string $type
     * @param array $params
     * @return true
     */
    public function payNotify(int $site_id, string $out_trade_no, string $type, array $params = [])
    {
        $pay = $this->findPayInfoByOutTradeNo($site_id, $out_trade_no);

        if ($pay->isEmpty()) throw new PayException('ALIPAY_TRANSACTION_NO_NOT_EXIST');
        if ($pay[ 'status' ] == PayDict::STATUS_FINISH) throw new PayException('DOCUMENT_IS_PAID');
        if ($pay[ 'status' ] == PayDict::STATUS_CANCLE) throw new PayException('PAY_IS_REMOVE');
        $status = $params[ 'status' ];
        switch ($status) {
            case OnlinePayDict::SUCCESS://支付成功
                $this->paySuccess($site_id, array_merge([
                    'out_trade_no' => $out_trade_no,
                    'type' => $type
                ], $params));
                break;
            case OnlinePayDict::CLOSED://支付关闭
                $this->payClose($site_id, [
                    'out_trade_no' => $out_trade_no,
                    'type' => $type
                ]);
                break;
            case OnlinePayDict::NOTPAY://未支付
                //todo  主动去校验支付状态
                $this->check($site_id, [
                    'out_trade_no' => $out_trade_no,
                    'type' => $type
                ]);
                break;
        }
        return true;
    }


    /**
     * 异步通知
     * @param int $site_id
     * @param string $channel
     * @param string $type
     * @param string $action
     * @return null
     */
    public function notify(int $site_id, string $channel, string $type, string $action)
    {
        $callback = function($out_trade_no, $params) use ($site_id, $type, $action) {
            try {
                switch ($action) {
                    case 'pay'://支付结果通知
                        return $this->payNotify($site_id, $out_trade_no, $type, $params);
                        break;
                    case 'refund':
                        return ( new CoreRefundService() )->refundNotify($site_id, $out_trade_no, $type, $params);
                        break;
                    case 'transfer':
                        return ( new CoreTransferService() )->transferNotify($site_id, $out_trade_no, $params);
                        break;
                }
                //找不到对应的业务
                return true;
            } catch (PayException $e) {
                return false;
            }
        };

        Log::write('业务'.$site_id.'_'.$channel.'_'.$type.'_'.$action);
        return $this->pay_event->init($site_id, $channel, $type)->notify($action, $callback);
    }

    /**
     * 校验支付订单的状态
     * @param int $site_id
     * @param array $data
     * @return bool
     */
    public function check(int $site_id, array $data)
    {
        $out_trade_no = $data[ 'out_trade_no' ];
        $pay = $this->findPayInfoByOutTradeNo($site_id, $out_trade_no);
        if ($pay->isEmpty()) throw new PayException('ALIPAY_TRANSACTION_NO_NOT_EXIST');
        if ($pay[ 'status' ] == PayDict::STATUS_FINISH) throw new PayException('PAY_SUCCESS');//单据已支付
        if ($pay[ 'status' ] == PayDict::STATUS_CANCLE) throw new PayException('PAY_IS_REMOVE');//单据已取消
        //查询第三方支付单据
        $pay_info = $this->pay_event->init($site_id, $pay->channel, $pay->type)->getOrder($out_trade_no);
        $type = $pay[ 'type' ];
        if (empty($pay_info))
            return false;
        $status = $pay_info[ 'status' ];
        switch ($status) {
            case OnlinePayDict::SUCCESS://支付成功
                $this->paySuccess($site_id, [
                    'out_trade_no' => $out_trade_no,
                    'type' => $type
                ]);
                break;
            case OnlinePayDict::CLOSED://支付关闭
                $this->payClose($site_id, [
                    'out_trade_no' => $out_trade_no,
                    'type' => $type
                ]);
                break;
            case OnlinePayDict::NOTPAY://未支付
                //todo  主动去校验支付状态
                $this->check($site_id, [
                    'out_trade_no' => $out_trade_no,
                    'type' => $type
                ]);
                break;
        }
        return true;
    }

    /**
     * 支付成功
     * @param $site_id
     * @param $params
     * @return bool
     */
    public function paySuccess($site_id, $params)
    {
        $out_trade_no = $params[ 'out_trade_no' ];
        $pay = $this->findPayInfoByOutTradeNo($site_id, $out_trade_no);
        $type = $params[ 'type' ];
        $trade_type = $pay->trade_type;
        $trade_id = $pay->trade_id;
        $main_id = $pay->main_id;
        $data = array(
            'pay_time' => time(),
            'status' => PayDict::STATUS_FINISH,
            'type' => $type,
            'trade_no' => $params[ 'trade_no' ] ?? '',
            'voucher' => $params[ 'voucher' ] ?? '',
            'mch_id' => $params[ 'mch_id' ] ?? '',
        );
        //允许修改的值
        $allow_field = array( 'trade_no', 'voucher', 'status', 'pay_time', 'type', 'mch_id' );

        // 启动事务
        Db::startTrans();
        try {
            $pay->allowField($allow_field)->save($data);
            $result = event('PaySuccess', [ 'out_trade_no' => $out_trade_no, 'trade_type' => $trade_type, 'site_id' => $site_id, 'trade_id' => $trade_id, 'main_id' => $main_id ]);
//            if (!check_event_result($result)) {
//                Db::rollback();
//                return false;
//            }
            // 提交事务
            Db::commit();
            return true;
        } catch (Throwable $e) {
            // 回滚事务
            Db::rollback();
            throw new PayException($e->getMessage() . $e->getFile() . $e->getLine());
        }
        return true;
    }

    /**
     * 单据关闭
     * @param $site_id
     * @param $data
     * @return true
     */
    public function payClose($site_id, $data)
    {
        $out_trade_no = $data[ 'out_trade_no' ];

        Db::startTrans();
        try {
            $pay = $this->findPayInfoByOutTradeNo($site_id, $out_trade_no);
            $pay->save([
                'status' => PayDict::STATUS_CANCLE,
                'fail_reason' => $data[ 'reason' ] ?? ''
            ]);

            $result = event('PayClose', [ 'out_trade_no' => $out_trade_no, 'site_id' => $site_id, 'trade_type' => $pay->trade_type, 'trade_id' => $pay->trade_id ]);
            if (!check_event_result($result)) {
                Db::rollback();
                return false;
            }
            // 提交事务
            Db::commit();
            return true;
        } catch (Throwable $e) {
            // 回滚事务
            Db::rollback();
            throw new PayException($e->getMessage() . $e->getFile() . $e->getLine());
        }

    }
}
