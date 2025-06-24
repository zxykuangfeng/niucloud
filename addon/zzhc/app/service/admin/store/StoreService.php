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

namespace addon\zzhc\app\service\admin\store;

use addon\zzhc\app\model\store\Store;
use app\service\admin\sys\AreaService;
use app\service\core\sys\CoreAreaService;
use core\base\BaseAdminService;

/**
 * 门店服务层
 * Class StoreService
 * @package addon\zzhc\app\service\admin\store
 */
class StoreService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Store();
    }

    /**
     * 获取门店列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'store_id,site_id,store_logo,store_name,trade_time,store_contacts,store_mobile,store_image,store_content,province_id,city_id,district_id,address,full_address,longitude,latitude,status,create_time,update_time';
        $order = 'store_id desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["store_name","store_contacts","store_mobile"], $where)->field($field)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取门店信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'store_id,site_id,store_logo,store_name,trade_time,store_contacts,store_mobile,store_image,store_content,province_id,city_id,district_id,address,full_address,longitude,latitude,status,create_time,update_time';

        $info = $this->model->field($field)->where([['store_id', "=", $id]])->findOrEmpty()->toArray();
        $info[ 'province_name' ] = ( new AreaService() )->getAreaName($info[ 'province_id' ]);
        $info[ 'city_name' ] = ( new AreaService() )->getAreaName($info[ 'city_id' ]);
        $info[ 'district_name' ] = ( new AreaService() )->getAreaName($info[ 'district_id' ]);
        return $info;
    }

    /**
     * 添加门店
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['site_id'] = $this->site_id;
        $data[ 'province_id' ] = ( new AreaService() )->getAreaId($data[ 'province_name' ], 1);
        $data[ 'city_id' ] = ( new AreaService() )->getAreaId($data[ 'city_name' ], 2);
        $data[ 'district_id' ] = ( new AreaService() )->getAreaId($data[ 'district_name' ], 3);
        if ($data[ 'full_address' ] == '') $data[ 'full_address' ] = ( new CoreAreaService() )->getFullAddress($data[ 'province_id' ], $data[ 'city_id' ], $data[ 'district_id' ], $data[ 'address' ], '');
        $res = $this->model->create($data);
        return $res->store_id;

    }

    /**
     * 门店编辑
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data, array $address)
    {

        $data[ 'province_id' ] = ( new AreaService() )->getAreaId($address[ 'province_name' ], 1);
        $data[ 'city_id' ] = ( new AreaService() )->getAreaId($address[ 'city_name' ], 2);
        $data[ 'district_id' ] = ( new AreaService() )->getAreaId($address[ 'district_name' ], 3);
        if ($data[ 'full_address' ] == '') $data[ 'full_address' ] = ( new CoreAreaService() )->getFullAddress($data[ 'province_id' ], $data[ 'city_id' ], $data[ 'district_id' ], $data[ 'address' ], '');
        $this->model->where([['store_id', '=', $id],['site_id', '=', $this->site_id]])->update($data);
        return true;
    }

    /**
     * 删除门店
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['store_id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }

    public function getStoreAll(){
        return $this->model->where([["site_id","=",$this->site_id]])->select()->toArray();
    }
 

}
