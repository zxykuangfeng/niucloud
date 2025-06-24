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

use app\api\middleware\ApiCheckToken;
use app\api\middleware\ApiLog;
use app\api\middleware\ApiChannel;
use think\facade\Route;


/**
 * 理发店预约系统
 */
Route::group('zzhc', function() {

    // 门店列表
    Route::get('store', 'addon\zzhc\app\api\controller\store\Store@lists');
    // 门店详情
    Route::get('store/:store_id', 'addon\zzhc\app\api\controller\store\Store@info');
    // 门店发型师组件数据
    Route::get('store/components', 'addon\zzhc\app\api\controller\store\Store@components');
    // 发型师列表
    Route::get('barber', 'addon\zzhc\app\api\controller\staff\Barber@lists');
    // 发型师详情
    Route::get('barber/:staff_id', 'addon\zzhc\app\api\controller\staff\Barber@info');
    // 项目分类列表
    Route::get('goods/category', 'addon\zzhc\app\api\controller\goods\Category@lists');
    // 项目列表
    Route::get('goods', 'addon\zzhc\app\api\controller\goods\Goods@lists');
    // VIP配置
    Route::get('vip/config', 'addon\zzhc\app\api\controller\vip\Vip@getConfig');
    // 优惠券列表
    Route::get('coupon', 'addon\zzhc\app\api\controller\coupon\Coupon@lists');
})->middleware(ApiChannel::class)
    ->middleware(ApiCheckToken::class, false) //false表示不验证登录
    ->middleware(ApiLog::class);


Route::group('zzhc', function() {
    //订单创建
    Route::post('order/create', 'addon\zzhc\app\api\controller\order\Order@create');
    //订单查询优惠券
    Route::get('order/coupon', 'addon\zzhc\app\api\controller\order\Order@getCoupon');
    //订单使用优惠券
    Route::put('order/coupon', 'addon\zzhc\app\api\controller\order\Order@useCoupon');
    //订单详情
    Route::get('order/:order_id', 'addon\zzhc\app\api\controller\order\Order@info');
    //订单列表
    Route::get('order', 'addon\zzhc\app\api\controller\order\Order@lists');
    //订单状态
    Route::get('order/status', 'addon\zzhc\app\api\controller\order\Order@getStatus');
    //订单取消
    Route::put('order/cancel/:order_id', 'addon\zzhc\app\api\controller\order\Order@cancel');
    //添加或更新订单支付表
    Route::put('order/pay/:order_id', 'addon\zzhc\app\api\controller\order\Order@addPay');
    //VIP套餐列表
    Route::get('vip', 'addon\zzhc\app\api\controller\vip\Vip@lists');
    //VIP订单创建
    Route::post('vip/order/create', 'addon\zzhc\app\api\controller\vip\Order@create');
    //领取优惠券
    Route::post('coupon', 'addon\zzhc\app\api\controller\coupon\Coupon@receive');
    //会员优惠券列表
    Route::get('member/coupon', 'addon\zzhc\app\api\controller\coupon\Coupon@memberCouponlists');
    //会员办卡信息
    Route::get('member/vip', 'addon\zzhc\app\api\controller\vip\Vip@memberVip');

    //发型师端首页数据
    Route::get('merchant/barber/info/:store_id','addon\zzhc\app\api\controller\merchant\Barber@info');
    //发型师订单列表
    Route::get('merchant/barber/order', 'addon\zzhc\app\api\controller\merchant\Barber@order');
    //发型师订单详情
    Route::get('merchant/barber/order_detail', 'addon\zzhc\app\api\controller\merchant\Barber@orderInfo');
    //发型师取消订单
    Route::put('merchant/barber/order_cancel', 'addon\zzhc\app\api\controller\merchant\Barber@orderCancel');
    //发型师订单开始服务
    Route::put('merchant/barber/order_service', 'addon\zzhc\app\api\controller\merchant\Barber@orderService');
    //发型师订单退回排队
    Route::put('merchant/barber/order_revert', 'addon\zzhc\app\api\controller\merchant\Barber@orderRevert');
    //发型师订单完成服务
    Route::put('merchant/barber/order_finish', 'addon\zzhc\app\api\controller\merchant\Barber@orderFinish');
    //发型师添加考勤打卡
    Route::post('merchant/barber/work', 'addon\zzhc\app\api\controller\merchant\Barber@addWork');
    //获取发型师月打卡数据
    Route::get('merchant/barber/work', 'addon\zzhc\app\api\controller\merchant\Barber@getWorkInfo');
    //获取发型师日打卡数据
    Route::get('merchant/barber/work_date', 'addon\zzhc\app\api\controller\merchant\Barber@getWorkDate');

    //店长端首页数据
    Route::get('merchant/manage/info/:store_id','addon\zzhc\app\api\controller\merchant\Manage@info');
    //店长端订单数据
    Route::get('merchant/manage/order', 'addon\zzhc\app\api\controller\merchant\Manage@order');
    //店长端取消订单
    Route::put('merchant/manage/order_cancel', 'addon\zzhc\app\api\controller\merchant\Manage@orderCancel');
    //店长获取订单详情
    Route::get('merchant/manage/order_detail', 'addon\zzhc\app\api\controller\merchant\Manage@orderInfo');
    //发型师查看考勤打卡
    Route::get('merchant/manage/work', 'addon\zzhc\app\api\controller\merchant\Manage@getWrokList');
})->middleware(ApiChannel::class)
    ->middleware(ApiCheckToken::class, true) //表示验证登录
    ->middleware(ApiLog::class);

