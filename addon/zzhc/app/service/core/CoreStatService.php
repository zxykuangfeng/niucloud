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

namespace addon\zzhc\app\service\core;

use core\base\BaseCoreService;
use addon\zzhc\app\model\order\Order;
use addon\zzhc\app\dict\order\OrderDict;


/**
 * 统计
 */
class CoreStatService extends BaseCoreService
{
    
   
    /**
     * 获取时间区间内订单金额
     * @param string $start_date
     * @param string $end_date
     * @return void
     */
    public static function getOrderMoney(int $site_id, int $startTime = 0, int $endTime = 0, int $status = 0) {

        $where = [['site_id', "=", $site_id]];

        if($startTime > 0 && $endTime > 0){
            $where[] = ['create_time', 'between', [$startTime,$endTime]];
        }

        if($status != 0){
            $where[] = ['status', "=", $status];
        }
        
        return (new Order())->where($where)->sum('order_money');
    }

    /**
     * 获取时间区间内订单数量
     * @param string $start_date
     * @param string $end_date
     * @return void
     */
    public static function getOrderCount(int $site_id, int $startTime = 0, int $endTime = 0, int $status = 0) {

        $where = [['site_id', "=", $site_id]];

        if($startTime > 0 && $endTime > 0){
            $where[] = ['create_time', 'between', [$startTime,$endTime]];
        }

        if($status != 0){
            $where[] = ['status', "=", $status];
        }

        return (new Order())->where($where)->count();
    }

    /**
     * 获取时间区间内订单统计数据
     * @param string $start_date
     * @param string $end_date
     * @return void
     */
    public static function getStatOrder(int $site_id, int $startTime, int $endTime) {
       
        $data = [];
        $day = ceil(($endTime - $startTime) / 86400);
        for ($i = 0; $i < $day; $i++) {
            $date = date('Y-m-d', $startTime + $i * 86400);
            $data['date'][] = $date;
            $data['order_num'][] = (new Order())->where([['site_id', "=", $site_id],['create_time', 'between', [$startTime + $i * 86400,$startTime + $i * 86400+86400]]])->count();
            $data['order_money'][] = (new Order())->where([['site_id', "=", $site_id],['create_time', 'between', [$startTime + $i * 86400,$startTime + $i * 86400+86400]],['status', "=", OrderDict::FINISH]])->sum('order_money');
        }

        return $data;
    }


   
}
