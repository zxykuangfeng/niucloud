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

namespace addon\zzhc\app\service\api\order;

use addon\zzhc\app\model\order\Order;
use addon\zzhc\app\service\api\goods\GoodsService;
use addon\zzhc\app\service\api\staff\BarberService;
use addon\zzhc\app\service\api\store\StoreService;
use core\base\BaseApiService;
use core\exception\ApiException;
use addon\zzhc\app\dict\order\OrderDict;
use addon\zzhc\app\dict\order\OrderLogDict;
use app\service\api\member\MemberService;
use addon\zzhc\app\service\core\order\CoreOrderService;
use addon\zzhc\app\service\core\vip\CoreVipConfigService;
use addon\zzhc\app\service\core\vip\CoreVipMemberService;
use addon\zzhc\app\service\api\coupon\MemberService as CouponMemberService;
use addon\zzhc\app\model\coupon\Member as CouponMemberModel;
use app\service\core\pay\CorePayService;
use addon\zzhc\app\service\api\staff\WorkService;
use addon\zzhc\app\dict\staff\WorkDict;

/**
 * 预约订单服务层
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
     * 获取预约订单列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'order_id,site_id,store_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,staff_id,staff_headimg,staff_name,goods_id,goods_name,goods_image,duration,goods_money,goods_vip_money,discount_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';
        $order = 'order_id desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ],[ 'member_id' ,"=", $this->member_id ] ])->withSearch(["status"], $where)->with(['store'])->field($field)->append(['status_name'])->order($order);
        $list = $this->pageQuery($search_model);

        foreach($list['data'] as $key=>$order){
            //前面人数
            $list['data'][$key]['wait_people'] = $this->model->where([['staff_id', "=", $order['staff_id']],['order_id', "<", $order['order_id']],['status','=',OrderDict::WAIT_SERVICE]])->count();

            //发型师
            $barber = (new BarberService())->getInfo($order['staff_id']);
            

            //等待时长
            $list['data'][$key]['wait_duration'] = $this->model->where([['staff_id', "=", $order['staff_id']],['order_id', "<", $order['order_id']],['status','=',OrderDict::WAIT_SERVICE]])->sum('duration') + ($barber['work'] ? $barber['work']['duration'] : 0);
        }

        return $list;
    }

    /**
     * 获取预约订单信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $order_id)
    {
        $field = 'order_id,site_id,store_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,staff_id,staff_headimg,staff_name,goods_id,goods_name,goods_image,duration,goods_money,goods_vip_money,discount_money,order_money,pay_time,coupon_member_id,out_trade_no,status,ip,create_time,update_time';

        $info = $this->model->field($field)->append(['status_name'])->where([['order_id', "=", $order_id],['member_id', "=", $this->member_id]])->with(['store'])->findOrEmpty()->toArray();

        //发型师
        $barber = (new BarberService())->getInfo($info['staff_id']);

        //前面人数
        $info['wait_people'] = $this->model->where([['staff_id', "=", $info['staff_id']],['order_id', "<", $order_id],['status','=',OrderDict::WAIT_SERVICE]])->count();

        //等待时长
        $info['wait_duration'] = $this->model->where([['staff_id', "=", $info['staff_id']],['order_id', "<", $order_id],['status','=',OrderDict::WAIT_SERVICE]])->sum('duration') + ($barber['work'] ? $barber['work']['duration'] : 0);

        return $info;
    }

    /**
     * 创建预约订单
     * @param array $data
     * @return mixed
     */
    public function createOrder(array $data)
    {
        
        $storeInfo = (new StoreService())->getInfo($data['store_id']);
        if(empty($storeInfo)){
            throw new ApiException('门店已停业');
        }

        $barberInfo = (new BarberService())->getInfo($data['staff_id']);

        if(empty($barberInfo)){
            throw new ApiException('发型师已离职');
        }

        if($barberInfo['work_id'] == 0){
            throw new ApiException('发型师休息中');
        }

        $workInfo = (new WorkService())->getInfo($barberInfo['work_id']);

        if(empty($workInfo) || $workInfo['status'] == WorkDict::REST){
            throw new ApiException('发型师休息中');
        }

        if($workInfo['status'] == WorkDict::STOP){
            throw new ApiException('发型师停止接单');
        }

        $goodsInfo = (new GoodsService())->getInfo($data['goods_id']);
        $memberInfo = (new MemberService())->getInfo();

        //VIP价格
        $vipPrice = $goodsInfo['price'];
        $orderMoney = $goodsInfo['price'];
        $vipConfig = (new CoreVipConfigService())->getConfig($this->site_id);
        $vipMemberInfo = (new CoreVipMemberService())->getInfo($this->site_id,$this->member_id);
        $data['is_vip'] = 0;
        if($vipConfig['is_enable'] == 1){
            $vipPrice = $this->moneyFormat($vipPrice * ($vipConfig['discount']/10));
            if(!empty($vipMemberInfo) && $vipMemberInfo['overdue_time'] > time()){
                $orderMoney = $vipPrice;
                $data['is_vip'] = 1;
            }
        }

        $data['site_id'] = $this->site_id;
        $data['order_no'] = create_no();
        $data['order_type'] = OrderDict::TYPE;
        $data['order_from'] = $this->channel;
        $data['member_id'] = $this->member_id;

        $data['nickname'] = $memberInfo['nickname'];
        $data['headimg'] = $memberInfo['headimg'];
        $data['mobile'] = $memberInfo['mobile'];

        $data['staff_id'] = $barberInfo['staff_id'];
        $data['staff_headimg'] = $barberInfo['staff_headimg'];
        $data['staff_name'] = $barberInfo['staff_name'];
        
        $data['goods_id'] = $goodsInfo['goods_id'];
        $data['goods_name'] = $goodsInfo['goods_name'];
        $data['goods_image'] = $goodsInfo['goods_image'];
        $data['duration'] = $goodsInfo['duration'];
        $data['goods_money'] = $goodsInfo['price'];
        $data['goods_vip_money'] = $vipPrice;
        $data['discount_money'] = 0;
        $data['order_money'] = $orderMoney;
        $data['ip'] = request()->ip();

        $res = $this->model->create($data);
        return $res->order_id;

    }

     /**
     * 金额格式化
     * @param $money
     * @return float|int
     */
    public function moneyFormat($money)
    {
        return floor(strval(($money) * 100)) / 100;
    }

    /**
     * 订单取消
     * @param array $data
     * @return void
     */
    public function cancel(int $order_id)
    {
        $data[ 'main_type' ] = OrderLogDict::MEMBER;
        $data[ 'main_id' ] = $this->member_id;
        $data[ 'order_id' ] = $order_id;
        $data[ 'site_id' ] = $this->site_id;
        ( new CoreOrderService() )->cancel($data);
        return true;
    }

    /**
     * 添加或更新订单支付表
     * @param array $data
     * @return void
     */
    public function addPay(int $order_id)
    {

        $orderData = $this->model->where([['order_id', "=", $order_id],['member_id', "=", $this->member_id]])->findOrEmpty()->toArray();

        if($orderData['status'] != OrderDict::WAIT_PAY){
            throw new ApiException('订单状态错误');
        }

        $payInfo = ( new CorePayService() )->findPayInfoByTrade($this->site_id,$orderData['order_type'],$orderData['order_id']);

        if(empty($payInfo) || $payInfo['money'] != $orderData['order_money']){
            ( new CorePayService() )->checkOrCreate($this->site_id,$orderData['order_type'],$orderData['order_id']);
        }
       

        return true;
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
       
        return true;
    }
    
    /**
     * 获取有效的优惠券
     * @param $data
     * @return void
     */
    public function getCoupon($data)
    {

        $couponList = (new CouponMemberService())->getUseCouponListByMemberId($this->member_id);

        if (!empty($couponList)) {

            $orderInfo = $this->model->where([['site_id', '=', $this->site_id],['order_id', "=", $data['order_id']],['member_id', "=", $this->member_id]])->findOrEmpty()->toArray();

            foreach ($couponList as &$v) {

                if ($orderInfo['order_money'] <= $v['atleast']) {
                    $v['is_normal'] = false;
                    $v['error'] = '未达到最低可使用金额';
                } else {
                    $v['is_normal'] = true;
                }
            }
        }
        return $couponList;
    }

    /**
     * 使用优惠券
     * @param $data
     * @return void
     */
    public function useCoupon($data)
    {

        $orderInfo = $this->model->where([['site_id', '=', $this->site_id],['order_id', "=", $data['order_id']],['member_id', "=", $this->member_id]])->findOrEmpty()->toArray();

        if($orderInfo['coupon_member_id'] > 0){
            throw new ApiException('订单已使用优惠券');
        }

        $couponMemberInfo = (new CouponMemberService())->getInfo($data['coupon_member_id']);

        if (!empty($couponMemberInfo) && $orderInfo['order_money'] >= $couponMemberInfo['atleast'] && $couponMemberInfo['expire_time'] > time()) {

            $discountMoney = $couponMemberInfo['money'];

            if($orderInfo['is_vip']){
                $orderMoney = $orderInfo['goods_vip_money'] - $discountMoney;
                if($orderMoney < 0){
                    $orderMoney = 0;
                    $discountMoney = $orderInfo['goods_vip_money'];
                }
            }else{
                $orderMoney = $orderInfo['goods_money'] - $discountMoney;
                if($orderMoney < 0){
                    $orderMoney = 0;
                    $discountMoney = $orderInfo['goods_money'];
                }
            }
            

            $this->model->where([['site_id', '=', $this->site_id],['order_id', "=", $data['order_id']],['member_id', "=", $this->member_id]])->update(['discount_money'=>$discountMoney,'order_money'=>$orderMoney,'coupon_member_id'=>$data['coupon_member_id']]);

            (new CouponMemberModel())->where([['site_id', '=', $this->site_id],['id', "=", $data['coupon_member_id']],['member_id', "=", $this->member_id]])->update(['use_time'=>time(),'order_id'=>$data['order_id']]);

            
        }else{
            throw new ApiException('优惠券不可用');
        }

        return true;
    }

    
    
}
