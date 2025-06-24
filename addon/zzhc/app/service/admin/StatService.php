<?php
// +----------------------------------------------------------------------
// | Niucloud-admin 企业快速开发的多应用管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace addon\zzhc\app\service\admin;

use addon\zzhc\app\dict\order\OrderDict;
use addon\zzhc\app\service\core\CoreStatService;
use core\base\BaseAdminService;
use app\model\member\Member;
use addon\zzhc\app\model\store\Store;
use addon\zzhc\app\model\staff\Staff;
use addon\zzhc\app\dict\staff\StaffDict;

/**
 * 统计服务层
 * Class StatService
 * @package app\service\admin
 */
class StatService extends BaseAdminService
{
    /**
     * 获取统计数据
     */
    public function getStatData() {


        //今日起始时间戳
        $beginToday = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $endToday = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")) - 1;

        //昨日起始时间戳
        $beginYesterday = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
        $endYesterday = mktime(0, 0, 0, date("m"), date("d"), date("Y")) - 1;
       
        //本月起始时间戳
        $beginThisMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
        $endThisMonth = mktime(23, 59, 59, date("m"), date("t"), date("Y"));

        //今年起始时间戳
        $beginThisYear=strtotime(date("Y",time())."-1"."-1");
        $endThisYear=strtotime(date("Y",time())."-12"."-31");

        //订单收入
        $orderMoney = [
            'total'         => CoreStatService::getOrderMoney($this->site_id,0,0,OrderDict::FINISH),
            'today'         => CoreStatService::getOrderMoney($this->site_id,$beginToday,$endToday,OrderDict::FINISH),
            'yesterday'     => CoreStatService::getOrderMoney($this->site_id,$beginYesterday,$endYesterday,OrderDict::FINISH),
            'month'         => CoreStatService::getOrderMoney($this->site_id,$beginThisMonth,$endThisMonth,OrderDict::FINISH),
            'year'          => CoreStatService::getOrderMoney($this->site_id,$beginThisYear,$endThisYear,OrderDict::FINISH),
            'wait_pay'      => CoreStatService::getOrderMoney($this->site_id,0,0,OrderDict::WAIT_PAY),
        ];

        //订单数量
        $orderCount = [
            'wait_pay'   => CoreStatService::getOrderCount($this->site_id,0,0,OrderDict::WAIT_PAY),
            'finish'     => CoreStatService::getOrderCount($this->site_id,0,0,OrderDict::FINISH),
            'wait_service'     => CoreStatService::getOrderCount($this->site_id,0,0,OrderDict::WAIT_SERVICE),
            'in_service'     => CoreStatService::getOrderCount($this->site_id,0,0,OrderDict::IN_SERVICE),
            
        ];

        //门店、人员数量
        $peopleCount = [
            'store' => (new Store())->where([['site_id', "=", $this->site_id]])->count(),
            'barber' => (new Staff())->where([['site_id', "=", $this->site_id],['staff_role', "like", '%' .StaffDict::BARBER .'%']])->count(),
            'manage' => (new Staff())->where([['site_id', "=", $this->site_id],['staff_role', "like", '%' .StaffDict::MANAGER .'%']])->count(),
            'member'     => (new Member())->where([['site_id', "=", $this->site_id]])->count(),
        ];

        //订单统计数据
        $startTime = strtotime(date('Y-m-d', strtotime('-6 day')));
        $endTime = strtotime(date('Y-m-d', strtotime('+1 day')));
        $statOrder = CoreStatService::getStatOrder($this->site_id,$startTime,$endTime);
       

        return [
            'order_money'    => $orderMoney,
            'order_count'  => $orderCount,
            'people_count'   => $peopleCount,
            'stat_order'     => $statOrder,
        ];
    }

    
}
