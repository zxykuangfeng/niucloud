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

namespace addon\zzhc\app\model\staff;

use core\base\BaseModel;
use addon\zzhc\app\dict\staff\WorkDict;
use addon\zzhc\app\model\store\Store;

/**
 * 考勤管理模型
 * Class Work
 * @package addon\zzhc\app\model\staff
 */
class Work extends BaseModel
{

    

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'zzhc_staff_work';

    
    /**
     * 订单状态
     * @param $value
     * @param $data
     * @return mixed|string
     */
    public function getStatusNameAttr($value, $data)
    {
        if (empty($data['status']))
            return '';
        return WorkDict::getStatus()[$data['status']] ?? '';
    }
    

    /**
     * 搜索器:考勤管理门店
     * @param $value
     * @param $data
     */
    public function searchStoreIdAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("store_id", $value);
        }
    }
    
    /**
     * 搜索器:考勤管理员工
     * @param $value
     * @param $data
     */
    public function searchStaffIdAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("staff_id", $value);
        }
    }
    
    /**
     * 搜索器:考勤管理考勤状态
     * @param $value
     * @param $data
     */
    public function searchStatusAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("status", $value);
        }
    }
    
    /**
     * 搜索器:考勤管理添加时间
     * @param $value
     * @param $data
     */
    public function searchCreateTimeAttr($query, $value, $data)
    {
        $start = empty($value[0]) ? 0 : strtotime($value[0]);
        $end = empty($value[1]) ? 0 : strtotime($value[1]);
        if ($start > 0 && $end > 0) {
             $query->where([["create_time", "between", [$start, $end]]]);
        } else if ($start > 0 && $end == 0) {
            $query->where([["create_time", ">=", $start]]);
        } else if ($start == 0 && $end > 0) {
            $query->where([["create_time", "<=", $end]]);
        }
    }
    
    public function store(){
        return $this->hasOne(Store::class, 'store_id', 'store_id')->joinType('left');
    }

    public function staff(){
        return $this->hasOne(Staff::class, 'staff_id', 'staff_id')->joinType('left');
    }

    
}
