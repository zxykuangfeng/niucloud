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
 * 办卡会员模型
 * Class Member
 * @package addon\zzhc\app\model\vip
 */
class Member extends BaseModel
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
    protected $name = 'zzhc_vip_member';

    
    protected $type = [
        'overdue_time' => 'timestamp',
    ];
    

    /**
     * 搜索器:办卡会员会员昵称
     * @param $value
     * @param $data
     */
    public function searchNicknameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("nickname", $value);
        }
    }
    
    /**
     * 搜索器:办卡会员会员手机号
     * @param $value
     * @param $data
     */
    public function searchMobileAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("mobile", $value);
        }
    }
    
    /**
     * 搜索器:办卡会员到期时间
     * @param $value
     * @param $data
     */
    public function searchOverdueTimeAttr($query, $value, $data)
    {
        $start = empty($value[0]) ? 0 : strtotime($value[0]);
        $end = empty($value[1]) ? 0 : strtotime($value[1]);
        if ($start > 0 && $end > 0) {
             $query->where([["overdue_time", "between", [$start, $end]]]);
        } else if ($start > 0 && $end == 0) {
            $query->where([["overdue_time", ">=", $start]]);
        } else if ($start == 0 && $end > 0) {
            $query->where([["overdue_time", "<=", $end]]);
        }
    }
    
    

    

    
}
