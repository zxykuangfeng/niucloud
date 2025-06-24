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

namespace addon\zzhc\app\api\controller\store;

use core\base\BaseApiController;
use addon\zzhc\app\service\api\store\StoreService;


/**
 * 门店控制器
 * Class Store
 * @package addon\zzhc\app\api\controller\store
 */
class Store extends BaseApiController
{
   /**
    * 获取门店列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
            [ 'latitude', '' ],
            [ 'longitude', '' ],
        ]);
        return success((new StoreService())->getPage($data));
    }

    /**
     * 门店详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $store_id){
        return success((new StoreService())->getInfo($store_id));
    }

    /**
    * 获取门店发型师组件数据
    * @return \think\Response
    */
    public function components(){
        $data = $this->request->params([
            [ 'store_id', 0 ],
            [ 'latitude', '' ],
            [ 'longitude', '' ],
            [ 'source', '' ],
            [ 'num', 0 ],
        ]);

        return success(( new StoreService() )->getStoreStaffComponents($data));
    }

    
    
}
