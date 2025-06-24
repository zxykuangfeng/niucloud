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

namespace addon\zzhc\app\service\api\staff;

use addon\zzhc\app\model\staff\Staff;
use core\base\BaseApiService;
use addon\zzhc\app\model\order\Order;
use addon\zzhc\app\dict\order\OrderDict;


/**
 * 发型师服务层
 * Class BarberService
 * @package addon\zzhc\app\service\api\staff
 */
class BarberService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Staff();
    }

    /**
     * 获取发型师列表（分页）
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'staff_id,site_id,store_id,member_id,staff_headimg,staff_name,staff_mobile,staff_position,staff_experience,staff_image,staff_content,sort,status,work_id,create_time,update_time';
        $order = 'sort desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ],[ 'status' ,"=", 'normal' ],[ 'staff_role' ,"like", '%barber%' ] ])->withSearch(["store_id"], $where)->with(['store','work'])->field($field)->order($order);
        $list = $this->pageQuery($search_model);

        foreach($list['data'] as $key=>$barber){
            //前面人数
            $list['data'][$key]['wait_people'] = (new Order())->where([['staff_id', "=", $barber['staff_id']],['status','=',OrderDict::WAIT_SERVICE]])->count();

            //预计时长
            $list['data'][$key]['wait_duration'] = (new Order())->where([['staff_id', "=", $barber['staff_id']],['status','=',OrderDict::WAIT_SERVICE]])->sum('duration') + ($barber['work'] ? $barber['work']['duration'] : 0);
        }

        return $list;
    }

    /**
     * 获取门店发型师列表
     */
    public function getList(array $data = []){
        extract($data);

        $field = 'staff_id,site_id,store_id,member_id,staff_headimg,staff_name,staff_mobile,staff_position,staff_experience,staff_image,work_id,sort,status,create_time,update_time';
        $order = 'sort desc';

        $where = [ 
            [ 'site_id' ,"=", $this->site_id ],
            [ 'store_id' ,"=", $store_id ],
            [ 'status' ,"=", 'normal' ],
            [ 'staff_role' ,"like", '%barber%' ],
        ];

        $list = $this->model->where($where)->with(['store','work'])->field($field)->order($order)->limit($num)->select()->toArray();

        foreach($list as $key=>$barber){
            //前面人数
            $list[$key]['wait_people'] = (new Order())->where([['staff_id', "=", $barber['staff_id']],['status','=',OrderDict::WAIT_SERVICE]])->count();

            //预计时长
            $list[$key]['wait_duration'] = (new Order())->where([['staff_id', "=", $barber['staff_id']],['status','=',OrderDict::WAIT_SERVICE]])->sum('duration') + ($barber['work'] ? $barber['work']['duration'] : 0);
        }

        return $list;
    }

    /**
     * 获取发型师信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'staff_id,site_id,store_id,member_id,staff_headimg,staff_name,staff_mobile,work_id,staff_position,staff_experience,staff_image,staff_content,sort,status,create_time,update_time';

        $info = $this->model->field($field)->where([['staff_id', "=", $id],[ 'staff_role' ,"like", '%barber%' ],[ 'status' ,"=", 'normal' ]])->with(['store','work'])->append(['staff_image_arr'])->findOrEmpty()->toArray();

        //预计时长
        $info['finish_order'] = (new Order())->where([['staff_id', "=", $info['staff_id']],['status','=',OrderDict::FINISH]])->count();

        return $info;
    }
    
    
}
