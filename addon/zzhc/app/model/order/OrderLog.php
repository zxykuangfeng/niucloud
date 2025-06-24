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

namespace addon\zzhc\app\model\order;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use think\model\relation\HasMany;
use think\model\relation\HasOne;

/**
 * 订单操作日志模型
 * Class OrderLog
 * @package addon\zzhc\app\model\order
 */
class OrderLog extends BaseModel
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
    protected $name = 'zzhc_order_log';

    

    

    /**
     * 搜索器:订单操作日志操作人类型
     * @param $value
     * @param $data
     */
    public function searchMainTypeAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("main_type", $value);
        }
    }
    
    /**
     * 搜索器:订单操作日志操作人id
     * @param $value
     * @param $data
     */
    public function searchMainIdAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("main_id", $value);
        }
    }
    
    /**
     * 搜索器:订单操作日志订单状态
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
     * 搜索器:订单操作日志操作类型
     * @param $value
     * @param $data
     */
    public function searchTypeAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("type", $value);
        }
    }
    
    /**
     * 搜索器:订单操作日志创建时间
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
    
    

    

    
}
