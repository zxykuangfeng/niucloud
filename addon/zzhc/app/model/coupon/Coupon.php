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

/**
 * 优惠券模型
 * Class Coupon
 * @package addon\zzhc\app\model\coupon
 */
class Coupon extends BaseModel
{

    

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'coupon_id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'zzhc_coupon';

    
    protected $type = [
        'start_time' => 'timestamp',
        'end_time' => 'timestamp',
        'end_usetime' => 'timestamp',
    ];
    
    
    /**
     * 搜索器:优惠券优惠券名称
     * @param $value
     * @param $data
     */
    public function searchNameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("name", $value);
        }
    }
    
    

    

    
}
