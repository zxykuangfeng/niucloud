<?php
// +----------------------------------------------------------------------
// | Niucloud-api 企业快速开发的多应用管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace addon\zzhc\app\service\api\vip;

use addon\zzhc\app\model\vip\Order;
use core\base\BaseApiService;
use core\exception\ApiException;
use addon\zzhc\app\dict\vip\OrderDict;
use app\service\api\member\MemberService;
use think\facade\Db;
use app\service\core\pay\CorePayService;
use app\dict\pay\PayDict;
use addon\zzhc\app\dict\vip\LogDict;
use addon\zzhc\app\service\core\vip\CoreVipMemberService;

/**
 * VIP订单服务层
 * Class OrderService
 * @package addon\zzhc\app\service\api\order
 */
class OrderService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Order();
    }

    /**
     * 获取办卡订单列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'order_id,site_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,vip_id,vip_name,days,vip_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';
        $order = 'order_id desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["order_no","nickname","mobile","out_trade_no","status","create_time"], $where)->field($field)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取办卡订单信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'order_id,site_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,vip_id,vip_name,days,vip_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';

        $info = $this->model->field($field)->where([['order_id', "=", $id]])->findOrEmpty()->toArray();
        return $info;
    }

    /**
     * 创建VIP订单
     * @param array $data
     * @return mixed
     */
    public function createOrder(array $data)
    {

        $vipInfo = (new VipService())->getInfo($data['vip_id']);
        if(empty($vipInfo)){
            throw new ApiException('会员卡套餐已下架');
        }
        
        $memberInfo = (new MemberService())->getInfo();

        $data['site_id'] = $this->site_id;
        $data['order_no'] = create_no();
        $data['order_type'] = OrderDict::TYPE;
        $data['order_from'] = $this->channel;
        $data['member_id'] = $this->member_id;

        $data['nickname'] = $memberInfo['nickname'];
        $data['headimg'] = $memberInfo['headimg'];
        $data['mobile'] = $memberInfo['mobile'];

        
        $data['vip_id'] = $vipInfo['vip_id'];
        $data['vip_name'] = $vipInfo['vip_name'];
        $data['days'] = $vipInfo['days'];
        $data['vip_money'] = $vipInfo['price'];
        $data['order_money'] = $vipInfo['price'];
        $data['ip'] = request()->ip();


        Db::startTrans();
        try {

            //添加订单表
            $newOrder = $this->model->create($data);

            $orderId = $newOrder->order_id;

            //添加订单支付表
            ( new CorePayService() )->create($this->site_id, PayDict::MEMBER, $data[ 'member_id' ], $data[ 'order_money' ], $data[ 'order_type' ], $orderId,'开通会员卡'); 

            Db::commit();

            //返回订单信息
            return [
                'trade_type' => $data[ 'order_type' ],
                'trade_id' => $orderId
            ];
        } catch (\Exception $e) {
            Db::rollback();
            throw new ApiException($e->getMessage());
        }


    }

    /**
     * 订单已支付操作
     * @param $order_id
     * @return void
     */
    public function pay(array $data)
    {
        $order_id = $data['trade_id'];
        $where = [
            ['order_id', '=', $order_id],
            ['site_id', '=', $this->site_id]
        ];
        $order = $this->model->where($where)->findOrEmpty();
        if ($order->isEmpty())
            throw new ApiException('订单不存在');

        //状态判断
        if (!in_array($order['status'], [OrderDict::WAIT_PAY])) throw new ApiException('订单已支付');
        $out_trade_no = $data['out_trade_no'] ?? '';

        //订单状态变成已完成
        $order_data = array(
            'status' => OrderDict::FINISH,
            'pay_time' => time(),
            'out_trade_no' => $out_trade_no,
        );
        $this->model->where($where)->update($order_data);

        //添加会员卡信息 、记录日志
        (new CoreVipMemberService())->change([
            'site_id'   => $this->site_id,
            'member_id' => $this->member_id,
            'days'      => $order['days'],
            'main_type' => LogDict::MEMBER,
            'main_id'   => $this->member_id,
            'content'   => '',
        ]);
        
        return true;
    }
    
    
    
}
