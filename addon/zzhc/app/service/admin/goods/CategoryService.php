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

use addon\zzhc\app\model\goods\Category;

use core\base\BaseAdminService;


/**
 * 项目分类服务层
 * Class CategoryService
 * @package addon\zzhc\app\service\admin\goods
 */
class CategoryService extends BaseAdminService
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
    public function getPage(array $where = [])
    {
        $field = 'category_id,site_id,category_name,category_image,sort,status,create_time,update_time';
        $order = 'sort desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["category_name"], $where)->field($field)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取项目分类信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'category_id,site_id,category_name,category_image,sort,status,create_time,update_time';

        $info = $this->model->field($field)->where([['category_id', "=", $id]])->findOrEmpty()->toArray();
        return $info;
    }

    /**
     * 添加项目分类
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['site_id'] = $this->site_id;
        $res = $this->model->create($data);
        return $res->id;

    }

    /**
     * 项目分类编辑
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data)
    {

        $this->model->where([['category_id', '=', $id],['site_id', '=', $this->site_id]])->update($data);
        return true;
    }

    /**
     * 删除项目分类
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['category_id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }

    public function getCategoryAll(){
        return $this->model->where([["site_id","=",$this->site_id]])->select()->toArray();
     }
    
}
