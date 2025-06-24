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

namespace addon\zzhc\app\service\api\coupon;

use addon\zzhc\app\model\coupon\Member as CouponMember;

use core\base\BaseApiService;


/**
 * 领券记录服务层
 * Class MemberService
 * @package addon\zzhc\app\service\api\coupon
 */
class MemberService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new CouponMember();
    }

    /**
     * 查询会员有效可用的优惠券
     */
    public function getUseCouponListByMemberId($member_id)
    {
        $where = array (
            [ 'member_id', '=', $member_id ],
            [ 'use_time', '=', 0 ],
            [ 'expire_time', '>=',time()]
        );
        $field = 'id,site_id,coupon_id,name,member_id,create_time,expire_time,use_time,money,atleast,receive_type';
        $order = 'id desc';
        return $this->model->where($where)->field($field)->select()->order($order)->toArray();
    }
    
    /**
     * 获取领券记录信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'id,site_id,coupon_id,name,member_id,create_time,expire_time,use_time,money,atleast,receive_type';

        $info = $this->model->field($field)->where([['id', "=", $id]])->findOrEmpty()->toArray();
        return $info;
    }

}
