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

namespace addon\zzhc\app\model\coupon;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use think\model\relation\HasMany;
use think\model\relation\HasOne;

/**
 * 领券记录模型
 * Class Member
 * @package addon\zzhc\app\model\coupon
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
    protected $name = 'zzhc_coupon_member';

    
    protected $type = [
        'expire_time' => 'timestamp',
        'use_time' => 'timestamp',
    ];
    

    /**
     * 搜索器:领券记录优惠券名称
     * @param $value
     * @param $data
     */
    public function searchNameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("name", $value);
        }
    }
    
    /**
     * 搜索器:领券记录过期时间
     * @param $value
     * @param $data
     */
    public function searchExpireTimeAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("expire_time", $value);
        }
    }

    
}
