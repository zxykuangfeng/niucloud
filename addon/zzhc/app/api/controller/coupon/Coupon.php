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

namespace addon\zzhc\app\api\controller\coupon;

use addon\zzhc\app\service\api\coupon\CouponService;
use core\base\BaseApiController;
use think\Response;

class Coupon extends BaseApiController
{
    /**
     * 优惠券列表
     * @return Response
     */
    public function lists()
    {
        return success(( new CouponService() )->getPage());
    }

    /**
     * 优惠券领取
     */
    public function receive()
    {
        $data = $this->request->params([
            [ 'coupon_id', '' ],
            [ 'type', 'receive' ],
            [ 'number', 1 ],
        ]);
        ( new CouponService())->receive($data);
        return success('优惠券领取成功');

    }

    /**
     * 会员优惠券列表
     * @return Response
     */
    public function memberCouponlists()
    {
        $data = $this->request->params([
            ['status','']
        ]);
        return success(( new CouponService() )->getMemberPage($data));
    }

}
