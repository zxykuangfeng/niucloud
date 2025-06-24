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

namespace addon\zzhc\app\adminapi\controller\coupon;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\coupon\CouponService;


/**
 * 优惠券控制器
 * Class Coupon
 * @package addon\zzhc\app\adminapi\controller\coupon
 */
class Coupon extends BaseAdminController
{
   /**
    * 获取优惠券列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["name",""]
        ]);
        return success((new CouponService())->getPage($data));
    }

    /**
     * 优惠券详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new CouponService())->getInfo($id));
    }

    /**
     * 添加优惠券
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["name",""],
             ["count",0],
             ["money",0.00],
             ["atleast",0.00],
             ["is_show",0],
             ["discount",0.00],
             ["validity_type",0],
             ["receive_time_type","2"],
             ["receive_time",""],
             ["end_usetime",0],
             ["fixed_term",0],
             ["max_fetch",0],
             ["sort",0],

        ]);

        $this->validate($data, 'addon\zzhc\app\validate\coupon\Coupon.add');
        $id = (new CouponService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 优惠券编辑
     * @param $id  优惠券id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["name",""],
             ["count",0],
             ["money",0.00],
             ["lead_count",0],
             ["used_count",0],
             ["atleast",0.00],
             ["is_show",0],
             ["validity_type",0],
             ["receive_time_type","2"],
             ["receive_time",""],
             ["end_usetime",0],
             ["fixed_term",0],
             ["max_fetch",0],
             ["sort",0],
        ]);
        $this->validate($data, 'addon\zzhc\app\validate\coupon\Coupon.edit');
        (new CouponService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 优惠券删除
     * @param $id  优惠券id
     * @return \think\Response
     */
    public function del(int $id){
        (new CouponService())->del($id);
        return success('DELETE_SUCCESS');
    }

    
}
