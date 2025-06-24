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

namespace addon\zzhc\app\model\vip;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use think\model\relation\HasMany;
use think\model\relation\HasOne;

/**
 * VIP会员操作日志模型
 * Class Log
 * @package addon\zzhc\app\model\vip
 */
class Log extends BaseModel
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
    protected $name = 'zzhc_vip_log';

    

    

    /**
     * 搜索器:VIP会员操作日志日志内容
     * @param $value
     * @param $data
     */
    public function searchContentAttr($query, $value, $data)
    {
        $start = empty($value[0]) ? 0 : strtotime($value[0]);
        $end = empty($value[1]) ? 0 : strtotime($value[1]);
        if ($start > 0 && $end > 0) {
             $query->where([["content", "between", [$start, $end]]]);
        } else if ($start > 0 && $end == 0) {
            $query->where([["content", ">=", $start]]);
        } else if ($start == 0 && $end > 0) {
            $query->where([["content", "<=", $end]]);
        }
    }
    
    

    

    
}
