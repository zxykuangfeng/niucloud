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

namespace addon\zzhc\app\model\goods;

use core\base\BaseModel;
use think\model\concern\SoftDelete;
use think\model\relation\HasMany;
use think\model\relation\HasOne;

use addon\zzhc\app\model\goods\Category;

/**
 * 项目模型
 * Class Goods
 * @package addon\zzhc\app\model\goods
 */
class Goods extends BaseModel
{

    

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'goods_id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'zzhc_goods';

    

    

    /**
     * 搜索器:项目适用门店
     * @param $value
     * @param $data
     */
    public function searchStoreIdsAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("store_ids", $value);
        }
    }
    
    /**
     * 搜索器:项目分类id
     * @param $value
     * @param $data
     */
    public function searchCategoryIdAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("category_id", $value);
        }
    }
    
    /**
     * 搜索器:项目项目名称
     * @param $value
     * @param $data
     */
    public function searchGoodsNameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("goods_name", $value);
        }
    }
    
    /**
     * 搜索器:项目状态
     * @param $value
     * @param $data
     */
    public function searchStatusAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("status", $value);
        }
    }

    
    public function category(){
       return $this->hasOne(Category::class, 'category_id', 'category_id')->joinType('left')->withField('category_name,category_id')->bind(['category_name']);
    }

}
