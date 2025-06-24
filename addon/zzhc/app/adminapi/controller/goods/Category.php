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

namespace addon\zzhc\app\adminapi\controller\goods;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\goods\CategoryService;


/**
 * 项目分类控制器
 * Class Category
 * @package addon\zzhc\app\adminapi\controller\goods
 */
class Category extends BaseAdminController
{
   /**
    * 获取项目分类列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["category_name",""]
        ]);
        return success((new CategoryService())->getPage($data));
    }

    /**
     * 项目分类详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new CategoryService())->getInfo($id));
    }

    /**
     * 添加项目分类
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["category_name",""],
             ["category_image",""],
             ["sort",0],
             ["status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\goods\Category.add');
        $id = (new CategoryService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 项目分类编辑
     * @param $id  项目分类id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["category_name",""],
             ["category_image",""],
             ["sort",0],
             ["status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\goods\Category.edit');
        (new CategoryService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 项目分类删除
     * @param $id  项目分类id
     * @return \think\Response
     */
    public function del(int $id){
        (new CategoryService())->del($id);
        return success('DELETE_SUCCESS');
    }

    public function getCategoryAll(){
        return success(( new CategoryService())->getCategoryAll());
   }
    
}
