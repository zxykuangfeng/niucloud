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
use addon\zzhc\app\model\staff\Work;
use addon\zzhc\app\model\order\Order;
use addon\zzhc\app\dict\order\OrderDict;
use addon\zzhc\app\dict\order\OrderLogDict;
use addon\zzhc\app\service\core\order\CoreOrderService;
use addon\zzhc\app\service\core\order\CoreOrderLogService;
use addon\zzhc\app\service\api\staff\BarberService as StaffBarberService;
use think\facade\Db;



/**
 * 发型师端服务层
 */
class BarberService extends BaseApiService
{

    public $member = null; // 会员
    public $staff = null;  // 员工
    public $store = null;  // 门店


    public function __construct($store_id = 0)
    {
        parent::__construct();
        $this->member = (new MemberService())->getInfo();
        $this->store  = (new StoreService())->getInfo($store_id);
        $this->staff = (new Staff())->where([['member_id', "=", $this->member['member_id']],['site_id', "=", $this->site_id],['store_id', "=", $store_id],['staff_role', "like", '%' .StaffDict::BARBER .'%']])->findOrEmpty()->toArray();
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
            throw new ApiException('您不是【'.$this->store['store_name'].'】发型师');
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

        $workInfo = null;
        
        if($this->staff['work_id']  > 0){
            $workInfo = (new Work())->where([['id', "=", $this->staff['work_id']]])->findOrEmpty()->toArray();
        }

        //订单数据
        $ordeWaitServiceCount = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['staff_id', "=", $this->staff['staff_id']],
            ['status', "=", OrderDict::WAIT_SERVICE],
        ])->count();

        $ordeInServiceCount = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['staff_id', "=", $this->staff['staff_id']],
            ['status', "=", OrderDict::IN_SERVICE],
        ])->count();

        $ordeWaitPayCount = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['staff_id', "=", $this->staff['staff_id']],
            ['status', "=", OrderDict::WAIT_PAY],
        ])->count();

        $ordeFinishCount = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['staff_id', "=", $this->staff['staff_id']],
            ['status', "in", [OrderDict::FINISH,OrderDict::WAIT_PAY]],
        ])->count();

        //业绩数据
        $orderMoneyAll = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['staff_id', "=", $this->staff['staff_id']],
            ['status', "in", [OrderDict::FINISH,OrderDict::WAIT_PAY]],
        ])->sum('order_money');

        //今日起始时间戳
        $beginToday = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $endToday = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")) - 1;
        
        $orderMoneyToday = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['staff_id', "=", $this->staff['staff_id']],
            ['create_time', 'between', [$beginToday,$endToday]],
            ['status', "in", [OrderDict::FINISH,OrderDict::WAIT_PAY]],
        ])->sum('order_money');

        //本月起始时间戳
        $beginThismonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $endThismonth = mktime(23, 59, 59, date("m"), date("t"), date("Y"));

        $orderMoneyMonth = (new Order())->where([
            ['site_id', "=", $this->site_id],
            ['store_id', "=", $this->staff['store_id']],
            ['staff_id', "=", $this->staff['staff_id']],
            ['create_time', 'between', [$beginThismonth,$endThismonth]],
            ['status', "in", [OrderDict::FINISH,OrderDict::WAIT_PAY]],
        ])->sum('order_money');

        return [
            'info' => $this->staff,
            'work' => $workInfo,
            'store' => $this->store,
            'order_count' => [
                'wait_service_count' => $ordeWaitServiceCount,
                'in_service_count' => $ordeInServiceCount,
                'wait_pay_count' => $ordeWaitPayCount,
                'finish_count' => $ordeFinishCount
            ],
            'order_money' => [
                'all' => $orderMoneyAll,
                'today' => $orderMoneyToday,
                'month' => $orderMoneyMonth
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

        $search_model = (new Order())->where([ [ 'site_id' ,"=", $this->site_id ],[ 'staff_id' ,"=", $this->staff['staff_id'] ] ])->with(['member'])->withSearch(["status"], $where)->field($field)->append(['status_name'])->order($order);
        $list = $this->pageQuery($search_model);

        foreach($list['data'] as $key=>$order){
            //前面人数
            $list['data'][$key]['wait_people'] = (new Order())->where([['staff_id', "=", $this->staff['staff_id']],['order_id', "<", $order['order_id']],['status','=',OrderDict::WAIT_SERVICE]])->count();

            //发型师
            $barber = (new StaffBarberService())->getInfo($this->staff['staff_id']);

            //等待时长
            $list['data'][$key]['wait_duration'] = (new Order())->where([['staff_id', "=", $this->staff['staff_id']],['order_id', "<", $order['order_id']],['status','=',OrderDict::WAIT_SERVICE]])->sum('duration') + ($barber['work'] ? $barber['work']['duration'] : 0);
        }

        return $list;
    }

    /**
     * 预约订单信息
     * @param int $id
     * @return array
     */
    public function getOrderInfo(int $order_id)
    {
        $field = 'order_id,site_id,store_id,order_no,order_type,order_from,member_id,nickname,headimg,mobile,staff_id,staff_headimg,staff_name,goods_id,goods_name,goods_image,duration,goods_money,goods_vip_money,discount_money,order_money,pay_time,out_trade_no,status,ip,create_time,update_time';

        $info = (new Order())->field($field)->append(['status_name'])->where([['order_id', "=", $order_id],['staff_id', "=", $this->staff['staff_id']]])->with(['member'])->findOrEmpty()->toArray();

        //前面人数
        $info['wait_people'] = (new Order())->where([['staff_id', "=", $info['staff_id']],['order_id', "<", $order_id],['status','=',OrderDict::WAIT_SERVICE]])->count();

        //发型师
        $barber = (new StaffBarberService())->getInfo($info['staff_id']);

        //等待时长
        $info['wait_duration'] = (new Order())->where([['staff_id', "=", $info['staff_id']],['order_id', "<", $order_id],['status','=',OrderDict::WAIT_SERVICE]])->sum('duration') + ($barber['work'] ? $barber['work']['duration'] : 0);

        return $info;
    }

    /**
     * 取消订单
     */
    public function orderCancel(int $order_id)
    {
        $data[ 'main_type' ] = OrderLogDict::BARBER;
        $data[ 'main_id' ] = $this->staff['staff_id'];
        $data[ 'order_id' ] = $order_id;
        $data[ 'site_id' ] = $this->site_id;
        ( new CoreOrderService() )->cancel($data);
        return true;
    }

    /**
     * 开始服务
     */
    public function orderService(int $order_id)
    {

        $orderData = (new Order())->where([
            ['order_id', '=', $order_id],
            ['site_id', '=', $this->site_id]
        ])->findOrEmpty()->toArray();

        if (empty($orderData)) throw new ApiException('订单不存在');

        if ($orderData['status'] != OrderDict::WAIT_SERVICE) throw new ApiException('订单状态错误');

        Db::startTrans();
        try {
            
            //更改状态
            (new Order())->where([ ['order_id', '=', $orderData['order_id']] ])->update([
                'status' => OrderDict::IN_SERVICE,
            ]);

            //订单操作日志
            (new CoreOrderLogService())->add([
                'order_id' => $orderData['order_id'],
                'site_id' => $this->site_id,
                'status' => OrderDict::IN_SERVICE,
                'main_type' => OrderLogDict::BARBER,
                'main_id' => $this->staff['staff_id'],
                'type' => OrderDict::ORDER_SERVICE_ACTION,
                'content' => ''
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new ApiException($e->getMessage());
        }
    }
   
    /**
     * 退回排队
     */
    public function orderRevert(int $order_id)
    {

        $orderData = (new Order())->where([
            ['order_id', '=', $order_id],
            ['site_id', '=', $this->site_id]
        ])->findOrEmpty()->toArray();

        if (empty($orderData)) throw new ApiException('订单不存在');

        if ($orderData['status'] != OrderDict::IN_SERVICE) throw new ApiException('订单状态错误');

        Db::startTrans();
        try {
            
            //更改状态
            (new Order())->where([ ['order_id', '=', $orderData['order_id']] ])->update([
                'status' => OrderDict::WAIT_SERVICE,
            ]);

            //订单操作日志
            (new CoreOrderLogService())->add([
                'order_id' => $orderData['order_id'],
                'site_id' => $this->site_id,
                'status' => OrderDict::WAIT_SERVICE,
                'main_type' => OrderLogDict::BARBER,
                'main_id' => $this->staff['staff_id'],
                'type' => OrderDict::ORDER_REVERT_ACTION,
                'content' => ''
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new ApiException($e->getMessage());
        }

    }

    /**
     * 完成服务
     */
    public function orderFinish(int $order_id)
    {

        $orderData = (new Order())->where([
            ['order_id', '=', $order_id],
            ['site_id', '=', $this->site_id]
        ])->findOrEmpty()->toArray();

        if (empty($orderData)) throw new ApiException('订单不存在');

        if ($orderData['status'] != OrderDict::IN_SERVICE) throw new ApiException('订单状态错误');

        Db::startTrans();
        try {
            
             //更改状态
            (new Order())->where([ ['order_id', '=', $orderData['order_id']] ])->update([
                'status' => OrderDict::WAIT_PAY,
            ]);

            //订单操作日志
            (new CoreOrderLogService())->add([
                'order_id' => $orderData['order_id'],
                'site_id' => $this->site_id,
                'status' => OrderDict::WAIT_PAY,
                'main_type' => OrderLogDict::BARBER,
                'main_id' => $this->staff['staff_id'],
                'type' => OrderDict::ORDER_FINISH_ACTION,
                'content' => ''
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * 添加考勤打卡
     */
    public function addWork($data)
    {
        Db::startTrans();
        try {
            
            $workId = (new Work())->create(['site_id'=>$this->site_id,'store_id'=>$this->staff['store_id'],'staff_id'=>$this->staff['staff_id'],'status'=>$data['status'],'duration'=>$data['duration'],'full_address'=>$data['full_address'],'longitude'=>$data['longitude'],'latitude'=>$data['latitude']]);


            (new Staff())->where([['staff_id', "=", $this->staff['staff_id']],['site_id', "=", $this->site_id]])->update(['work_id'=>$workId->id]);


            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new ApiException($e->getMessage());
        }

    }

    /**
     * 获取月打卡数据
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getWorkInfo(int $year, int $month)
    {
        $data = [];

        $model_result = (new Work())->field('create_time')->where([['site_id', '=', $this->site_id],['store_id', '=', $this->staff['store_id']], ['staff_id', '=', $this->staff['staff_id']]])->whereMonth('create_time', $year . '-' . sprintf("%02d", $month))->select();

        $days = [];
        foreach ($model_result as $value) {
            $day = date('d', strtotime($value['create_time']));
            array_push($days, $day);
        }

        $data['days'] = array_unique($days); ;
        
        return $data;
    }
    
    /**
     * 获取日打卡数据
     */
    public function getWorkDate(string $date)
    {
        $model_result = (new Work())->where([['site_id', '=', $this->site_id],['store_id', '=', $this->staff['store_id']],['create_time', 'between', [strtotime($date),strtotime($date)+86400]], ['staff_id', '=', $this->staff['staff_id']]])->append(['status_name'])->select()->toArray();
        return $model_result;
    }
    
    
}
