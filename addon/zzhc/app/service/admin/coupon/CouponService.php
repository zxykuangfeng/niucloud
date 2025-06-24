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

namespace addon\zzhc\app\service\admin\coupon;

use addon\zzhc\app\model\coupon\Coupon;
use core\exception\AdminException;

use core\base\BaseAdminService;


/**
 * 优惠券服务层
 * Class CouponService
 * @package addon\zzhc\app\service\admin\coupon
 */
class CouponService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Coupon();
    }

    /**
     * 获取优惠券列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'coupon_id,site_id,name,count,money,lead_count,used_count,atleast,is_show,validity_type,end_usetime,fixed_term,max_fetch,sort,create_time,update_time';
        $order = 'sort desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["name"], $where)->field($field)->order($order);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取优惠券信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'coupon_id,site_id,name,count,money,lead_count,used_count,atleast,is_show,receive_time_type,validity_type,end_usetime,fixed_term,max_fetch,sort,start_time,end_time,create_time,update_time';

        $info = $this->model->field($field)->where([['coupon_id', "=", $id]])->findOrEmpty()->toArray();
        $info['receive_time'] = [$info['start_time'],$info['end_time']];
        return $info;
    }

    /**
     * 添加优惠券
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['site_id'] = $this->site_id;

       

        if ($data[ 'receive_time_type' ] == 1) {
            if (!empty($data[ 'receive_time' ])) {
                $data[ 'start_time' ] = strtotime($data[ 'receive_time' ][ 0 ]);;
                $data[ 'end_time' ] = strtotime($data[ 'receive_time' ][ 1 ]);
            } else {
                $data[ 'start_time' ] = 0;
                $data[ 'end_time' ] = 0;
            }
        } else {
            $data[ 'start_time' ] = 0;
            $data[ 'end_time' ] = 0;
        }

        if(empty($data['end_usetime']) && $data['validity_type'] == 1){
            throw new AdminException('请选择过期日期');
        }

        if($data['validity_type'] == 1){
            $data['end_usetime'] = strtotime($data['end_usetime']);
        }

        $res = $this->model->create($data);
        return $res->coupon_id;

    }

    /**
     * 优惠券编辑
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data)
    {
        if ($data[ 'receive_time_type' ] == 1) {
            if (!empty($data[ 'receive_time' ])) {
                $data[ 'start_time' ] = strtotime($data[ 'receive_time' ][ 0 ]);;
                $data[ 'end_time' ] = strtotime($data[ 'receive_time' ][ 1 ]);
            } else {
                $data[ 'start_time' ] = '';
                $data[ 'end_time' ] = '';
            }
        } else {
            $data[ 'start_time' ] = '';
            $data[ 'end_time' ] = '';
        }
        
        unset($data[ 'receive_time' ]);
        unset($data[ 'receive_type_time' ]);

        if(empty($data['end_usetime']) && $data['validity_type'] == 1){
            throw new AdminException('请选择过期日期');
        }

        if($data['validity_type'] == 1){
            $data['end_usetime'] = strtotime($data['end_usetime']);
        }

        $this->model->where([['coupon_id', '=', $id],['site_id', '=', $this->site_id]])->update($data);
        return true;
    }

    /**
     * 删除优惠券
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['coupon_id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }
    
}
