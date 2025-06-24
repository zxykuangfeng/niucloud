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

namespace addon\zzhc\app\adminapi\controller\store;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\store\StoreService;


/**
 * 门店控制器
 * Class Store
 * @package addon\zzhc\app\adminapi\controller\store
 */
class Store extends BaseAdminController
{
   /**
    * 获取门店列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["store_name",""],
             ["store_contacts",""],
             ["store_mobile",""]
        ]);
        return success((new StoreService())->getPage($data));
    }

    /**
     * 门店详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new StoreService())->getInfo($id));
    }

    /**
     * 添加门店
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["store_logo",""],
             ["store_name",""],
             ["trade_time",""],
             ["store_contacts",""],
             ["store_mobile",""],
             ["store_image",""],
             ["store_content",""],
             ["province_name",""],
             ["city_name",""],
             ["district_name",""],
             ["address",""],
             ["full_address",""],
             ["longitude",""],
             ["latitude",""],
             ["status","normal"],
        ]);

        $this->validate($data, 'addon\zzhc\app\validate\store\Store.add');
        $id = (new StoreService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 门店编辑
     * @param $id  门店id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["store_logo",""],
             ["store_name",""],
             ["trade_time",""],
             ["store_contacts",""],
             ["store_mobile",""],
             ["store_image",""],
             ["store_content",""],
             ["address",""],
             ["full_address",""],
             ["longitude",""],
             ["latitude",""],
             ["status","normal"],

        ]);

        $address = $this->request->params([
            ["province_name",""],
            ["city_name",""],
            ["district_name",""],
        ]);
        $this->validate($data, 'addon\zzhc\app\validate\store\Store.edit');
        (new StoreService())->edit($id, $data, $address);
        return success('EDIT_SUCCESS');
    }

    /**
     * 门店删除
     * @param $id  门店id
     * @return \think\Response
     */
    public function del(int $id){
        (new StoreService())->del($id);
        return success('DELETE_SUCCESS');
    }

    /**
     * 所有门店
     */
    public function getStoreAll(){
        return success(( new StoreService())->getStoreAll());
    }

    
}
