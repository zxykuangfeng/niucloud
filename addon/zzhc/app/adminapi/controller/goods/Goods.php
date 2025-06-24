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
use addon\zzhc\app\service\admin\goods\GoodsService;


/**
 * 项目控制器
 * Class Goods
 * @package addon\zzhc\app\adminapi\controller\goods
 */
class Goods extends BaseAdminController
{
   /**
    * 获取项目列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["category_id",""],
             ["goods_name",""],
             ["status",""]
        ]);
        return success((new GoodsService())->getPage($data));
    }

    /**
     * 项目详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new GoodsService())->getInfo($id));
    }

    /**
     * 添加项目
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["category_id",0],
             ["goods_name",""],
             ["goods_image",""],
             ["duration",0],
             ["price",0.00],
             ["sort",0],
             ["status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\goods\Goods.add');
        $id = (new GoodsService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 项目编辑
     * @param $id  项目id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["category_id",0],
             ["goods_name",""],
             ["goods_image",""],
             ["duration",0],
             ["price",0.00],
             ["sort",0],
             ["status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\goods\Goods.edit');
        (new GoodsService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 项目删除
     * @param $id  项目id
     * @return \think\Response
     */
    public function del(int $id){
        (new GoodsService())->del($id);
        return success('DELETE_SUCCESS');
    }

    
    

}
