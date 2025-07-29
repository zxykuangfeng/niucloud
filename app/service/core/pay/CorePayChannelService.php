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


use app\dict\pay\PayDict;
use app\model\pay\PayChannel;
use app\service\core\weapp\CoreWeappConfigService;
use app\service\core\wechat\CoreWechatConfigService;
use core\base\BaseCoreService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;


/**
 * 支付渠道服务层
 */
class CorePayChannelService extends BaseCoreService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new PayChannel();

    }

    /**
     * 查询实例
     * @param int $site_id
     * @param array $where
     * @return PayChannel|array|mixed|Model
     */
    public function find(int $site_id, array $where)
    {
        $where[ 'site_id' ] = $site_id;
        return $this->model->where($where)->findOrEmpty();
    }

    /**
     * 通过渠道获取支持的支付方式(专属用于支付业务)
     * @param int $site_id
     * @param string $channel
     * @param string $trade_type
     * @return array|array[]
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getAllowPayTypeByChannel(int $site_id, string $channel, string $trade_type = '')
    {
        $channel_pay_list = $this->model->where([ [ 'site_id', '=', $site_id ], [ 'channel', '=', $channel ], [ 'status', '=', 1 ] ])->field('type,config')->order('sort asc')->select()->toArray();

        if (!empty($channel_pay_list)) {
            $temp_channel_pay_list = array_column($channel_pay_list, 'type');
            $pay_type_arr = PayDict::getPayType($temp_channel_pay_list);
            foreach ($temp_channel_pay_list as $key) {
                if (isset($pay_type_arr[$key])) {
                    $pay_type_list[$key] = $pay_type_arr[$key];
                }
            }
        }
        //充值订单不支持余额支付和找朋友帮忙付
        if (!empty($pay_type_list) && $trade_type == 'recharge') {
            unset($pay_type_list[ PayDict::BALANCEPAY ], $pay_type_list[ PayDict::FRIENDSPAY ]);
        }
        // 线下支付做处理
        if (!empty($pay_type_list) && isset($pay_type_list[ PayDict::OFFLINEPAY ])) {
            $temp_channel_pay_list = array_column($channel_pay_list, null, 'type');
            $pay_type_list[ PayDict::OFFLINEPAY ][ 'config' ] = $temp_channel_pay_list[ PayDict::OFFLINEPAY ][ 'config' ];
        }
        // 找朋友帮忙付做处理
        if (!empty($pay_type_list) && isset($pay_type_list[ PayDict::FRIENDSPAY ])) {
            $temp_channel_pay_list = array_column($channel_pay_list, null, 'type');
            $pay_type_list[ PayDict::FRIENDSPAY ][ 'config' ] = $temp_channel_pay_list[ PayDict::FRIENDSPAY ][ 'config' ];
            if (!empty($temp_channel_pay_list[ PayDict::FRIENDSPAY ][ 'config' ])) {
                $pay_type_list[ PayDict::FRIENDSPAY ][ 'name' ] = $temp_channel_pay_list[ PayDict::FRIENDSPAY ][ 'config' ][ 'pay_type_name' ];
            }
        }
        return $pay_type_list ?? [];

    }

    /**
     * 通过渠道和支付方式获取支付配置
     * @param int $site_id
     * @param string $channel
     * @param string $type
     * @return array|mixed
     */
public function getConfigByChannelAndType(int $site_id, string $channel, string $type)
{
    $pay_channel = $this->model->where([
        ['site_id', '=', $site_id],
        ['channel', '=', $channel],
        ['type', '=', $type]
    ])->find();
    
    // dd($pay_channel);
    if (!empty($pay_channel)) {
        // 取原始 config 内容（防止自动 json 解析失败）
        $config_raw = $pay_channel->getData('config') ?: '{}';
        $config = json_decode($config_raw, true) ?: [];

        // 写死 ID = 47 时的私钥覆盖
        if ($pay_channel->id == 47) {
            $config['private_key'] = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEAtDOuVZftfRp612eyJq9wr9UvrTSFT7zAlkvYnPHWscfRab9x
NCp50rvjAYNW1MszKrN3RVf7GXUbjOQF/4uclORNbaWgiMwxYVd6aRHIqJmunGo0
QArIeUWbmWsm3fLtceToX4wNLoDd2QpIr+xmdyPrlWyqJznNp99Qn6s6KJTSxohC
ong/pAsYqpdFhmJW8Qotdzq7H/CnGwLm4YBYAuJXZ/p48/EWdJPhfoYY8JtHqvs8
5utJYeTC3sK4mQzWIm036P7Q6JmukxBQ3X0bAlmAwSLWwuUXlc+TmeK1+rfP5zui
ZrqdxJivYRyYzjt85kf349qBDf3Rdds8ljnmOQIDAQABAoIBAHYoHPuKrvIE1t8+
4xVym9TvWF+dvHvYK/9gpBvkhv3zI2DPYo7t+wsun15ynBDTXC13l5Eka0T6AKKV
MUmqZXVLbWmj3GtWWFqXXXBfdM74VgHBsZj8eQ7rkWc7VzTZANBZY/SihFIltVGG
6LpRq64bI3HK2pb1099rEhZf07afJHbjH+tTRmny+P2CnmKRUxDm4hs8279QynJe
IZBizZpNO1QztkQiEo3ozgfJ4v1iwAYvuwcfW+JR4wxy6iNT3IoahXqapFbKx/ie
jxBf62Q+BFbkHDSvLY1c6MChwgNxAkK/+sUZaTct7z5ZsHlD0xa5pvdDoipj0W+k
S4jP1gECgYEA5cPDQ6ju2KS2qfESuGm4qYr9zrKYSKq4kLx/CMygtKM/+G+h82j1
UxoBTdcSVaFLA7ykj6iX1lNDWneFUFPlGp21OQ6UYmOkVy81ZbK/1lIr1IT61gKW
zY2NaHOlCRKU3KOvkLtHhPUFops4Cle7ZIQpgXh1DCILTiZzcmSphpECgYEAyMcm
j5rkECfOzZxaU3Ye0DNBkD/QtMY2Vls5I4FwP1S0FyO4nHfARMuryekICYQJrI+F
x8XCKsW5wN5Boi+sRdY4mwx0+HvD80MfSYm6ypveB/8youHWaeOYFcPhub5ko1FC
c2NRFBbORoixcwR/l7ySPWn5kntjj5wBwcZ0SSkCgYEAwBR8NSARLMPmgQOsZsbb
PcGYlSfw7y7pxPYQLUcEQn8Hh6WrelYQYTyoQm6+QR/qGmGmIQMMjHxnHkY1CQZZ
zXpyehSaL/ak+M3akf5xKbbgNXZGTIs1jvn7cYrcOU1zbVDaAODP1XMRFvM0UlEt
s8ZY/Ie7Mj1zvg2fDc7hekECgYEAgum3rvMjuZT7Nv23t6vRM5f4LAIwJ28GhxA8
FXaUpfao5l2YRg2fBDx46tJTN0EsvaNna3b6v8Dk+WjyCrpi7bZcelyI+GxavAcM
I3r2nJ09DKHNdn8iuzB3PdnXGLGYFRUq6unbN+oW3c7LRV+tglamU/0Big2CQWVL
j/nCYOECgYEAoLPZ3l6laklPxZWUOUmRgxBFXmMUst2l7zdXXocJtMfJVLVc/j14
NtOcg8HNcryXllDBOLa+c444TX9912FH79lckFMhRfRLdzLb6gbWH7R0KD0OBHR/
tpqswNMO15FxJfuCAY2PgcNdbZpRpAI8NQHPSQeGwE/3p3KXZ4dsmyA=
-----END RSA PRIVATE KEY-----
EOD;
 $config['app_id'] = 'tt554123fcca4821c001';
        }
    
        // 如果是微信支付，还合并微信额外配置
        if ($type == PayDict::WECHATPAY) {
            $config = array_merge($config, $this->getWechatPayFullConfig($site_id));
        }

        return $config;
    }

    return [];
}


    /**
     * 获取完整的微信支付配置(根据场景)
     * @param int $site_id
     * @return array
     */
    public function getWechatPayFullConfig(int $site_id)
    {
        //TODO 先判断是否是开放平台授权,然后再决定使用什么appid
        //查询公众号配置
        $core_wechat_config_service = new CoreWechatConfigService();
        $mp_app_id = $core_wechat_config_service->getWechatConfig($site_id)[ 'app_id' ];//公众号appid
        //查询公众号配置
        $core_weapp_config_service = new CoreWeappConfigService();
        $mini_app_id = $core_weapp_config_service->getWeappConfig($site_id)[ 'app_id' ];//小程序appid
        

        //todo  查询微信小程序 appid  .  应用appid.....
        return [
            'mp_app_id' => $mp_app_id,
            'mini_app_id' => $mini_app_id
            //............
        ];
    }
}
