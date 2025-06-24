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

namespace addon\zzhc\app\model\store;

use core\base\BaseModel;
use app\dict\sys\FileDict;

/**
 * 门店模型
 * Class Store
 * @package addon\zzhc\app\model\store
 */
class Store extends BaseModel
{

    

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'store_id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'zzhc_store';

    

    

    /**
     * 搜索器:门店门店名称
     * @param $value
     * @param $data
     */
    public function searchStoreNameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("store_name", $value);
        }
    }
    
    /**
     * 搜索器:门店联系人
     * @param $value
     * @param $data
     */
    public function searchStoreContactsAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("store_contacts", $value);
        }
    }
    
    /**
     * 搜索器:门店联系电话
     * @param $value
     * @param $data
     */
    public function searchStoreMobileAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("store_mobile", $value);
        }
    }
    
    /**
     * 评论图片
     */
    public function getStoreImageArrAttr($value, $data)
    {
        if (isset($data[ 'store_image' ]) && $data[ 'store_image' ] != '') {
            return explode(',', $data[ 'store_image' ]);
        }
        return [];
    }
    
}
