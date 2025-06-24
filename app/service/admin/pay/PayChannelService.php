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

namespace app\service\admin\pay;

use app\dict\common\ChannelDict;
use app\dict\common\CommonDict;
use app\dict\pay\PayChannelDict;
use app\dict\pay\PayDict;
use app\model\pay\PayChannel;
use app\service\core\pay\CorePayChannelService;
use core\base\BaseAdminService;
use core\exception\PayException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 支付配置服务层
 */
class PayChannelService extends BaseAdminService
{

    public $core_pay_channel_service;

    public function __construct()
    {
        parent::__construct();
        $this->model = new PayChannel();
        $this->core_pay_channel_service = new CorePayChannelService();
    }

    /**
     * 添加模板
     * @param string $channel
     * @param string $type
     * @param array $data
     * @return true
     */
    public function set(string $channel, string $type, array $data)
    {
        $where = array (
            'type' => $type,
            'channel' => $channel
        );
        if (!array_key_exists($type, PayDict::getPayType())) throw new PayException('PATMENT_METHOD_INVALID');
        if ($channel != 'transfer') {
            if (!array_key_exists($channel, ChannelDict::getType())) throw new PayException('CHANNEL_MARK_INVALID');
        }
        $pay_channel = $this->core_pay_channel_service->find($this->site_id, $where);

        if ($pay_channel->isEmpty()) {
            $data[ 'channel' ] = $channel;
            $data[ 'type' ] = $type;
            $data[ 'site_id' ] = $this->site_id;
            $data[ 'config' ] = $this->getConfigByPayType($data[ 'config' ], $type);
            $res = $this->model->create($data);
        } else {
            $config = $pay_channel->config;
            $data[ 'config' ] = $this->getConfigByPayType($data[ 'config' ], $type);
            foreach ($data[ 'config' ] as $config_k => $config_v) {
                if ($config_v == CommonDict::ENCRYPT_STR && isset($config[$config_k])) {
                    $data[ 'config' ][$config_k] = $config[$config_k];
                }
            }
            $pay_channel->save($data);
        }
        return true;
    }

    /**
     * 用于后端支付渠道
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getChannelList()
    {
        $channel_list = PayChannelDict::getPayChannel();
        $where = array (
            'site_id' => $this->site_id
        );
        $pay_channel_list_temp = $this->model->where($where)->field('type, channel, config, sort, status')->select()->toArray();

        $pay_channel_list = [];
        foreach ($pay_channel_list_temp as $v) {
            $pay_channel_list[ $v[ 'channel' ] ][ $v[ 'type' ] ] = $v;
        }

        $pay_type_list = PayDict::getPayType();

        foreach ($channel_list as $k => $v) {
            $temp_item = $pay_channel_list[ $k ] ?? [];
            foreach ($v[ 'pay_type' ] as $item_k => $item_v) {
                if (isset($temp_item[ $item_k ])) {
                    $temp_v_item = $temp_item[ $item_k ];
                    $encrypt_params = $pay_type_list[$item_k]['encrypt_params'] ?? [];
                    foreach ($temp_v_item['config'] as $config_k => $config_v) {
                        if ($config_v !== '' && in_array($config_k, $encrypt_params)) $temp_v_item['config'][$config_k] = CommonDict::ENCRYPT_STR;
                    }
                } else {
                    $temp_v_item = [ 'status' => 0, 'config' => $this->getConfigByPayType([], $item_k), 'sort' => 0 ];
                }
                $item_v[ 'config' ] = $temp_v_item[ 'config' ];
                $item_v[ 'status' ] = $temp_v_item[ 'status' ];
                $item_v[ 'sort' ] = $temp_v_item[ 'sort' ];
                if ($item_k == PayDict::FRIENDSPAY) {
                    $item_v[ 'name' ] = $temp_v_item[ 'config' ][ 'pay_type_name' ] ?? get_lang('dict_pay.type_friendspay');
                }
                $channel_list[ $k ][ 'pay_type' ][ $item_k ] = $item_v;
            }
            $temp_pay_type = array_values($channel_list[ $k ][ 'pay_type' ]);
            $sort = array_column($temp_pay_type, 'sort');
            array_multisort($sort, SORT_ASC, $temp_pay_type);
            $channel_list[ $k ][ 'pay_type' ] = $temp_pay_type;
        }
        return $channel_list;
    }

    /**
     * 通过渠道获取配置
     * @param string $channel
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getListByChannel(string $channel)
    {
        $where = array (
            'site_id' => $this->site_id,
            'channel' => $channel
        );
        $list = $this->model->where($where)->field('type, channel, config, sort, status')->select()->toArray();
        if (!empty($list)) {
            $pay_type_list = PayDict::getPayType();
            foreach ($list as $k => &$v) {
                $encrypt_params = $pay_type_list[ $v['type'] ]['encrypt_params'] ?? [];
                foreach ($v['config'] as $config_k => $config_v) {
                    if ($config_v !== '' && in_array($config_k, $encrypt_params)) $v['config'][$config_k] = CommonDict::ENCRYPT_STR;
                }
            }
        }
        return $list;
    }

    /**
     * 通过支付方式获取配置格式
     * @param $data
     * @param $type
     * @return array
     */
    public function getConfigByPayType($data, $type)
    {
        $config = [];
        switch ($type) {
            case PayDict::WECHATPAY:
                $config = [
                    'mch_id' => $data[ 'mch_id' ] ?? '',//商户号
                    'mch_secret_key' => $data[ 'mch_secret_key' ] ?? '',//商户秘钥  现在默认认为是v3版
                    'mch_secret_cert' => $data[ 'mch_secret_cert' ] ?? '',//商户私钥 字符串或路径
                    'mch_public_cert_path' => $data[ 'mch_public_cert_path' ] ?? '',//商户公钥证书路径
                    'wechat_public_cert_path' => $data['wechat_public_cert_path'] ?? '', // 微信支付公钥
                    'wechat_public_cert_id' => $data['wechat_public_cert_id'] ?? '' // 微信支付公钥id
                ];
                break;
            case PayDict::ALIPAY:
                $config = [
                    'app_id' => $data[ 'app_id' ] ?? '',// 必填-支付宝分配的 app_id
                    'app_secret_cert' => $data[ 'app_secret_cert' ] ?? '',// 必填-应用私钥 字符串或路径
                    'app_public_cert_path' => $data[ 'app_public_cert_path' ] ?? '',//必填-应用公钥证书 路径
                    'alipay_public_cert_path' => $data[ 'alipay_public_cert_path' ] ?? '',//必填-支付宝公钥证书 路径
                    'alipay_root_cert_path' => $data[ 'alipay_root_cert_path' ] ?? '',// 必填-支付宝根证书 路径
                ];
                break;
            case PayDict::OFFLINEPAY:
                $config = [
                    'collection_name' => $data[ 'collection_name' ] ?? '',// 必填-收款账户名称
                    'collection_bank' => $data[ 'collection_bank' ] ?? '',//必填-收款银行
                    'collection_account' => $data[ 'collection_account' ] ?? '',//必填-收款账号
                    'collection_desc' => $data[ 'collection_desc' ] ?? '',// 必填-转账说明
                ];
                break;
            case PayDict::FRIENDSPAY:
                $config = [
                    'pay_type_name' => $data[ 'pay_type_name' ] ?? get_lang('dict_pay.type_friendspay'),// 支付方式名称
                    'pay_leave_message' => $data[ 'pay_leave_message' ] ?? get_lang('dict_pay_config.pay_leave_message'),// 发起帮付默认留言
                    'pay_button_name' => $data[ 'pay_button_name' ] ?? get_lang('dict_pay_config.pay_button_name'),// 支付按钮名称
                    'pay_page_name' => $data[ 'pay_page_name' ] ?? get_lang('dict_pay_config.pay_page_name'),// 帮付页面名称
                    'pay_explain_switch' => $data[ 'pay_explain_switch' ] ?? 1,// 帮付说明开关, 1开启，0关闭
                    'pay_explain_title' => $data[ 'pay_explain_title' ] ?? get_lang('dict_pay_config.pay_explain_title'),// 帮付说明标题
                    'pay_explain_content' => $data[ 'pay_explain_content' ] ?? get_lang('dict_pay_config.pay_explain_content'),// 帮付说明内容
                    'pay_info_switch' => $data[ 'pay_info_switch' ] ?? 1,// 订单信息, 1开启，0关闭
                    'pay_wechat_share_image' => $data[ 'pay_wechat_share_image' ] ?? '/static/resource/images/pay/pay_wechat_share_image.png',// 默认分享图片（公众号）
                    'pay_weapp_share_image' => $data[ 'pay_weapp_share_image' ] ?? '/static/resource/images/pay/pay_weapp_share_image.png'// 默认分享图片（小程序）
                ];
                break;
            default:
                $config = $data;
        }
        return $config;
    }

    /**
     * 设置打款设置
     * @param $data
     * @return true
     */
    public function setTransfer($data)
    {
        $wechatpay_config = $data[ 'wechatpay_config' ];
        $alipay_config = $data[ 'alipay_config' ];
        $this->set('transfer', PayDict::WECHATPAY, [
            'config' => $wechatpay_config,
            'status' => 1,
        ]);
        $this->set('transfer', PayDict::ALIPAY, [
            'config' => $alipay_config,
            'status' => 1,
        ]);
        return true;
    }

    public function setAll($data)
    {
        foreach ($data as $k => $v) {
            $temp_v = $v[ 'pay_type' ];
            foreach ($temp_v as $item_k => $item) {
                $this->set($k, $item[ 'key' ], [
                    'config' => $item[ 'config' ] ?? [],
                    'status' => $item[ 'status' ] ?? 0,
                    'sort' => $item[ 'sort' ] ?? 0,
                ]);
            }
        }
        return true;
    }

    /**
     * 查询信息
     * @param array $where
     * @return mixed
     */
    public function getInfo($where = [])
    {
        $where[ 'site_id' ] = $this->site_id;
        $res = $this->model->where($where)->field('type, channel, config, sort, status')->findOrEmpty()->toArray();
        return $res;

    }

}
