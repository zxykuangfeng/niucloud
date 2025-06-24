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

namespace addon\zzhc\app\service\admin\goods;

use addon\zzhc\app\model\goods\Goods;
use addon\zzhc\app\model\goods\Category;

use core\base\BaseAdminService;


/**
 * 项目服务层
 * Class GoodsService
 * @package addon\zzhc\app\service\admin\goods
 */
class GoodsService extends BaseAdminService
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

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["category_id","goods_name","status"], $where)->with(['category'])->field($field)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取项目信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'goods_id,site_id,category_id,goods_name,goods_image,duration,price,sort,status,create_time,update_time';

        $info = $this->model->field($field)->where([['goods_id', "=", $id]])->with(['category'])->findOrEmpty()->toArray();
        return $info;
    }

    /**
     * 添加项目
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['site_id'] = $this->site_id;
        $res = $this->model->create($data);
        return $res->goods_id;

    }

    /**
     * 项目编辑
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data)
    {

        $this->model->where([['goods_id', '=', $id],['site_id', '=', $this->site_id]])->update($data);
        return true;
    }

    /**
     * 删除项目
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['goods_id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }
    
    public function getCategoryAll(){
       $categoryModel = new Category();
       return $categoryModel->where([["site_id","=",$this->site_id]])->select()->toArray();
    }

}
