<?php
// +----------------------------------------------------------------------
// | Niucloud-core 企业快速开发的多应用管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace addon\zzhc\app\service\api\merchant;

use core\base\BaseApiService;
use app\service\api\member\MemberService;
use core\exception\ApiException;
use addon\zzhc\app\service\api\store\StoreService;
use addon\zzhc\app\model\staff\Staff;
use addon\zzhc\app\dict\staff\StaffDict;
use addon\zzhc\app\model\order\Order;
use addon\zzhc\app\dict\order\OrderDict;
use addon\zzhc\app\service\api\staff\BarberService as StaffBarberService;
use addon\zzhc\app\dict\order\OrderLogDict;
use addon\zzhc\app\service\core\order\CoreOrderService;
use addon\zzhc\app\model\staff\Work;

/**
 * 店长端服务层
 */
class ManageService extends BaseApiService
{

    public $member = null; // 会员
    public $staff = null;  // 员工
    public $store = null;  // 门店


    public function __construct($store_id = 0)
    {
        parent::__construct();
        $this->member = (new MemberService())->getInfo();
        $this->store  = (new StoreService())->getInfo($store_id);
        $this->staff = (new Staff())->where([['member_id', "=", $this->member['member_id']],['site_id', "=", $this->site_id],['store_id', "=", $store_id],['staff_role', "like", '%' .StaffDict::MANAGER .'%']])->findOrEmpty()->toArray();
    }

    /**
     * 检测权限
     */
    public function checkAuth()
    {
        if(empty($this->store)){
            throw new ApiException('门店已停业');
        }

        if (empty($this->staff)) {
            throw new ApiException('您不是【'.$this->store['store_name'].'】店长');
        }

        if($this->staff['status'] == 'quit'){
            throw new ApiException('您已从【'.$this->store['store_name'].'】离职');
        }
        
        return true;
    }

    /**
     * 首页数据
     */
    public function getInfo(){

        //收入数据
        $orderMoneyAll = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['status', "in", [OrderDict::FINISH]],
        ])->sum('order_money');

        //今日起始时间戳
        $beginToday = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $endToday = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")) - 1;
        
        $orderMoneyToday = $ordeWaitPayCount = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['create_time', 'between', [$beginToday,$endToday]],
            ['status', "in", [OrderDict::FINISH]],
        ])->sum('order_money');

        //本月起始时间戳
        $beginThisMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $endThisMonth = mktime(23, 59, 59, date("m"), date("t"), date("Y"));

        $orderMoneyMonth = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['create_time', 'between', [$beginThisMonth,$endThisMonth]],
            ['status', "in", [OrderDict::FINISH]],
        ])->sum('order_money');

        //今年起始时间戳
        $beginThisYear = mktime(0,0,0,1,1,date('Y',time()));
        $endThisYear = mktime(23,59,59,12,31,date('Y',time()));
        $orderMoneyYear = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['create_time', 'between', [$beginThisYear,$endThisYear]],
            ['status', "in", [OrderDict::FINISH]],
        ])->sum('order_money');


        //订单数据
        $ordeWaitServiceCount = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['status', "=", OrderDict::WAIT_SERVICE],
        ])->count();

        $ordeInServiceCount = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['status', "=", OrderDict::IN_SERVICE],
        ])->count();

        $ordeWaitPayCount = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['status', "=", OrderDict::WAIT_PAY],
        ])->count();

        return [
            'info' => $this->staff,
            'store' => $this->store,
            'order_count' => [
                'wait_service_count' => $ordeWaitServiceCount,
                'in_service_count' => $ordeInServiceCount,
                'wait_pay_count' => $ordeWaitPayCount,
            ],
            'order_money' => [
                'all' => $orderMoneyAll,
                'today' => $orderMoneyToday,
                'month' => $orderMoneyMonth,
                'year'  => $orderMoneyYear,
            ]
        ];
    }

    /**
     * 预约订单列表
     * @param array $where
     * @return array
     */
    public function getOrderPage(array $where = [])
    {
        $field = 'order_id,site_id,store_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,staff_id,staff_headimg,staff_name,goods_id,goods_name,goods_image,duration,goods_money,goods_vip_money,discount_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';
        $order = 'order_id desc';

        $search_model = (new Order())->where([ [ 'site_id' ,"=", $this->site_id ],[ 'store_id' ,"=", $this->staff['store_id'] ] ])->with(['member'])->withSearch(["status"], $where)->field($field)->append(['status_name'])->order($order);
        $list = $this->pageQuery($search_model);

        foreach($list['data'] as $key=>$order){
            //前面人数
            $list['data'][$key]['wait_people'] = (new Order())->where([['staff_id', "=", $order['staff_id']],['order_id', "<", $order['order_id']],['status','=',OrderDict::WAIT_SERVICE]])->count();

            //发型师
            $barber = (new StaffBarberService())->getInfo($order['staff_id']);

            //等待时长
            $list['data'][$key]['wait_duration'] = (new Order())->where([['staff_id', "=", $order['staff_id']],['order_id', "<", $order['order_id']],['status','=',OrderDict::WAIT_SERVICE]])->sum('duration') + ($barber['work'] ? $barber['work']['duration'] : 0);
        }

        return $list;
    }
   
     /**
     * 取消订单
     */
    public function orderCancel(int $order_id)
    {
        $data[ 'main_type' ] = OrderLogDict::MANAGE;
        $data[ 'main_id' ] = $this->staff['staff_id'];
        $data[ 'order_id' ] = $order_id;
        $data[ 'site_id' ] = $this->site_id;
        ( new CoreOrderService() )->cancel($data);
        return true;
    }

    /**
     * 预约订单信息
     * @param int $id
     * @return array
     */
    public function getOrderInfo(int $order_id)
    {
        $field = 'order_id,site_id,store_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,staff_id,staff_headimg,staff_name,goods_id,goods_name,goods_image,duration,goods_money,goods_vip_money,discount_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';

        $info = (new Order())->field($field)->append(['status_name'])->where([['order_id', "=", $order_id]])->with(['member'])->findOrEmpty()->toArray();

        //前面人数
        $info['wait_people'] = (new Order())->where([['staff_id', "=", $info['staff_id']],['order_id', "<", $order_id],['status','=',OrderDict::WAIT_SERVICE]])->count();

        //发型师
        $barber = (new StaffBarberService())->getInfo($info['staff_id']);

        //等待时长
        $info['wait_duration'] = (new Order())->where([['staff_id', "=", $info['staff_id']],['order_id', "<", $order_id],['status','=',OrderDict::WAIT_SERVICE]])->sum('duration') + ($barber['work'] ? $barber['work']['duration'] : 0);

        return $info;
    }

    /**
     * 考勤记录
     */
    public function getWorkList($date)
    {
        $field = 'id,site_id,store_id,staff_id,status,duration,full_address,create_time,update_time';
        $order = 'id desc';

        $where = [ [ 'site_id' ,"=", $this->site_id ],[ 'store_id' ,"=", $this->staff['store_id'] ] ];

        if($date == 1){
            //今日起始时间戳
            $beginToday = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
            $endToday = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")) - 1;
            $where[] = ['create_time', 'between', [$beginToday,$endToday]];
        }

        if($date == 2){
            //昨日起始时间戳
            $beginYesterday = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
            $endYesterday = mktime(0, 0, 0, date("m"), date("d"), date("Y")) - 1;
            $where[] = ['create_time', 'between', [$beginYesterday,$endYesterday]];
        }
       
        if($date == 3){
            //本月起始时间戳
            $beginThisMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
            $endThisMonth = mktime(23, 59, 59, date("m"), date("t"), date("Y"));
            $where[] = ['create_time', 'between', [$beginThisMonth,$endThisMonth]];
        }

        $search_model = (new Work())->with(['staff'])->where($where)->append(['status_name'])->field($field)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }
    
}
