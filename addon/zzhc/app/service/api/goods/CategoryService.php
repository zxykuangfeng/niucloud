<?php
// +----------------------------------------------------------------------
// | Niucloud-api 企业快速开发的多应用管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace addon\zzhc\app\service\api\goods;

use addon\zzhc\app\model\goods\Category;

use core\base\BaseApiService;


/**
 * 项目分类服务层
 * Class CategoryService
 * @package addon\zzhc\app\service\api\goods
 */
class CategoryService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Category();
    }

    /**
     * 获取项目分类列表
     * @param array $where
     * @return array
     */
    public function getAll()
    {
        $field = 'category_id,site_id,category_name,category_image,sort,status,create_time,update_time';
        $order = 'sort desc';

        $list = $this->model->where([ [ 'site_id' ,"=", $this->site_id ],[ 'status' ,"=", 'normal' ] ])->field($field)->order($order)->select()->toArray();
        return $list;
    }

    
    
}
