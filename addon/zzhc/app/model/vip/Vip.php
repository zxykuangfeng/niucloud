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

/**
 * VIP套餐模型
 * Class Vip
 * @package addon\zzhc\app\model\vip
 */
class Vip extends BaseModel
{

    

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'vip_id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'zzhc_vip';

    

    

    /**
     * 搜索器:VIP套餐名称
     * @param $value
     * @param $data
     */
    public function searchVipNameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("vip_name", $value);
        }
    }
    
    

    

    
}
