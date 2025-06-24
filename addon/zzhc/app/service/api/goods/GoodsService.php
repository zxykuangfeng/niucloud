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

use addon\zzhc\app\model\goods\Goods;
use addon\zzhc\app\model\goods\Category;

use core\base\BaseApiService;


/**
 * 项目服务层
 * Class GoodsService
 * @package addon\zzhc\app\service\api\goods
 */
class GoodsService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Goods();
    }

    /**
     * 获取项目列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'goods_id,site_id,category_id,goods_name,goods_image,duration,price,sort,status,create_time,update_time';
        $order = 'sort desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ],[ 'status' ,"=", 'up' ] ])->withSearch(["category_id","goods_name","status"], $where)->with(['category'])->field($field)->order($order);
        $list = $this->pageQuery($search_model);

        return $list;
    }

    /**
     * 项目信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'goods_id,site_id,category_id,goods_name,goods_image,duration,price,sort,status,create_time,update_time';
        $info = $this->model->field($field)->where([['goods_id', "=", $id],[ 'status' ,"=", 'up' ]])->findOrEmpty()->toArray();
        return $info;
    }
    
    public function getCategoryAll(){
       $categoryModel = new Category();
       return $categoryModel->where([["site_id","=",$this->site_id]])->select()->toArray();
    }

}
