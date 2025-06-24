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

/**
 * 项目分类模型
 * Class Category
 * @package addon\zzhc\app\model\goods
 */
class Category extends BaseModel
{

    

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'category_id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'zzhc_goods_category';

    

    

    /**
     * 搜索器:项目分类分类名称
     * @param $value
     * @param $data
     */
    public function searchCategoryNameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("category_name","like",'%'.$value.'%');
        }
    }
    
    

    

    
}
