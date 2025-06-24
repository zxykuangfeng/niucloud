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

namespace addon\zzhc\app\service\api\store;

use addon\zzhc\app\model\store\Store;
use core\base\BaseApiService;
use addon\zzhc\app\service\api\staff\BarberService;


/**
 * 门店服务层
 * Class StoreService
 * @package addon\zzhc\app\service\api\store
 */
class StoreService extends BaseApiService
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
        $lat = $where['latitude']?$where['latitude']:0;
        $lng = $where['longitude']?$where['longitude']:0;
        $field_lat = 'latitude';
        $field_lng = 'longitude';
        $field = 'store_id,site_id,store_logo,store_name,trade_time,store_contacts,store_mobile,store_image,store_content,province_id,city_id,district_id,address,full_address,longitude,latitude,status,create_time,update_time';

        $distance = "(6378.138 * 2 * asin(sqrt(pow(sin(({$field_lat} * pi() / 180 - {$lat} * pi() / 180) / 2),2) + cos({$field_lat} * pi() / 180) * cos({$lat} * pi() / 180) * pow(sin(({$field_lng} * pi() / 180 - {$lng} * pi() / 180) / 2),2))) * 1000) as distance";

        $order = 'distance asc,store_id desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ],[ 'status' ,"=", 'normal' ] ])->withSearch(["store_name","store_contacts","store_mobile"], $where)->field($field.','.$distance)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取门店信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $store_id)
    {
        $field = 'store_id,site_id,store_logo,store_name,trade_time,store_contacts,store_mobile,store_image,store_content,province_id,city_id,district_id,address,full_address,longitude,latitude,status,create_time,update_time';

        $info = $this->model->field($field)->append(['store_image_arr'])->where([['store_id', "=", $store_id],[ 'status' ,"=", 'normal' ]])->findOrEmpty()->toArray();
        return $info;
    }


    /**
     * 获取门店发型师组件数据
     * @param array $where
     * @return array
     */
    public function getStoreStaffComponents(array $data = [])
    {
        $lat = $data['latitude']?$data['latitude']:0;
        $lng = $data['longitude']?$data['longitude']:0;
        $field_lat = 'latitude';
        $field_lng = 'longitude';
        $field = 'store_id,site_id,store_logo,store_name,trade_time,store_contacts,store_mobile,store_image,province_id,city_id,district_id,address,full_address,longitude,latitude,status,create_time,update_time';

        $distance = "(6378.138 * 2 * asin(sqrt(pow(sin(({$field_lat} * pi() / 180 - {$lat} * pi() / 180) / 2),2) + cos({$field_lat} * pi() / 180) * cos({$lat} * pi() / 180) * pow(sin(({$field_lng} * pi() / 180 - {$lng} * pi() / 180) / 2),2))) * 1000) as distance";

        if($data['store_id']){
            $storeInfo = $this->model->field($field.','.$distance)->where([['site_id', "=", $this->site_id],['store_id', "=", $data['store_id']],['status', "=", 'normal']])->findOrEmpty()->toArray();
        }else{
            $storeInfo = $this->model->field($field.','.$distance)->where([['site_id', "=", $this->site_id],['status', "=", 'normal']])->order("distance asc")->findOrEmpty()->toArray();
        }

        $barberList = []; //发型师列表
        if(!empty($storeInfo)){
            $barberList = (new BarberService())->getList(['store_id'=>$storeInfo['store_id'],'num'=>$data['num']]);
        }

        return [
            'store_info' => $storeInfo,
            'barber_list' => $barberList
        ];
    }
   

}
