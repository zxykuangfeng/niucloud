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

use think\facade\Route;

use app\adminapi\middleware\AdminCheckRole;
use app\adminapi\middleware\AdminCheckToken;
use app\adminapi\middleware\AdminLog;

/**
 * 理发店预约系统
 */
Route::group('zzhc', function () {

    //数据概况
    Route::get('stat', 'addon\zzhc\app\adminapi\controller\Stat@info');

    //门店列表
    Route::get('store', 'addon\zzhc\app\adminapi\controller\store\Store@lists');
    //门店详情
    Route::get('store/:id', 'addon\zzhc\app\adminapi\controller\store\Store@info');
    //添加门店
    Route::post('store', 'addon\zzhc\app\adminapi\controller\store\Store@add');
    //编辑门店
    Route::put('store/:id', 'addon\zzhc\app\adminapi\controller\store\Store@edit');
    //删除门店
    Route::delete('store/:id', 'addon\zzhc\app\adminapi\controller\store\Store@del');
    //所有门店
    Route::get('store_all','addon\zzhc\app\adminapi\controller\store\Store@getStoreAll');

    //员工列表
    Route::get('staff', 'addon\zzhc\app\adminapi\controller\staff\Staff@lists');
    //员工详情
    Route::get('staff/:id', 'addon\zzhc\app\adminapi\controller\staff\Staff@info');
    //添加员工
    Route::post('staff', 'addon\zzhc\app\adminapi\controller\staff\Staff@add');
    //编辑员工
    Route::put('staff/:id', 'addon\zzhc\app\adminapi\controller\staff\Staff@edit');
    //删除员工
    Route::delete('staff/:id', 'addon\zzhc\app\adminapi\controller\staff\Staff@del');
    //员工角色
    Route::get('staff/role', 'addon\zzhc\app\adminapi\controller\staff\Staff@getRole');
    //所有员工
    Route::get('staff_all','addon\zzhc\app\adminapi\controller\staff\Staff@getStaffAll');
    //考勤管理列表
    Route::get('work', 'addon\zzhc\app\adminapi\controller\staff\Work@lists');
    //考勤管理详情
    Route::get('work/:id', 'addon\zzhc\app\adminapi\controller\staff\Work@info');
    //添加考勤管理
    Route::post('work', 'addon\zzhc\app\adminapi\controller\staff\Work@add');
    //编辑考勤管理
    Route::put('work/:id', 'addon\zzhc\app\adminapi\controller\staff\Work@edit');
    //删除考勤管理
    Route::delete('work/:id', 'addon\zzhc\app\adminapi\controller\staff\Work@del');
    //项目分类列表
    Route::get('category', 'addon\zzhc\app\adminapi\controller\goods\Category@lists');

    Route::get('category_all','addon\zzhc\app\adminapi\controller\goods\Category@getCategoryAll');
    //项目分类详情
    Route::get('category/:id', 'addon\zzhc\app\adminapi\controller\goods\Category@info');
    //添加项目分类
    Route::post('category', 'addon\zzhc\app\adminapi\controller\goods\Category@add');
    //编辑项目分类
    Route::put('category/:id', 'addon\zzhc\app\adminapi\controller\goods\Category@edit');
    //删除项目分类
    Route::delete('category/:id', 'addon\zzhc\app\adminapi\controller\goods\Category@del');
    
    //项目列表
    Route::get('goods', 'addon\zzhc\app\adminapi\controller\goods\Goods@lists');
    //项目详情
    Route::get('goods/:id', 'addon\zzhc\app\adminapi\controller\goods\Goods@info');
    //添加项目
    Route::post('goods', 'addon\zzhc\app\adminapi\controller\goods\Goods@add');
    //编辑项目
    Route::put('goods/:id', 'addon\zzhc\app\adminapi\controller\goods\Goods@edit');
    //删除项目
    Route::delete('goods/:id', 'addon\zzhc\app\adminapi\controller\goods\Goods@del');
    
    //VIP套餐列表
    Route::get('vip', 'addon\zzhc\app\adminapi\controller\vip\Vip@lists');
    //VIP套餐详情
    Route::get('vip/:id', 'addon\zzhc\app\adminapi\controller\vip\Vip@info');
    //添加VIP套餐
    Route::post('vip', 'addon\zzhc\app\adminapi\controller\vip\Vip@add');
    //编辑VIP套餐
    Route::put('vip/:id', 'addon\zzhc\app\adminapi\controller\vip\Vip@edit');
    //删除VIP套餐
    Route::delete('vip/:id', 'addon\zzhc\app\adminapi\controller\vip\Vip@del');
    //VIP配置
    Route::post('vip/config', 'addon\zzhc\app\adminapi\controller\vip\Vip@setConfig');
    Route::get('vip/config', 'addon\zzhc\app\adminapi\controller\vip\Vip@getConfig');

    //优惠券列表
    Route::get('coupon', 'addon\zzhc\app\adminapi\controller\coupon\Coupon@lists');
    //优惠券详情
    Route::get('coupon/:id', 'addon\zzhc\app\adminapi\controller\coupon\Coupon@info');
    //添加优惠券
    Route::post('coupon', 'addon\zzhc\app\adminapi\controller\coupon\Coupon@add');
    //编辑优惠券
    Route::put('coupon/:id', 'addon\zzhc\app\adminapi\controller\coupon\Coupon@edit');
    //删除优惠券
    Route::delete('coupon/:id', 'addon\zzhc\app\adminapi\controller\coupon\Coupon@del');

    //预约订单列表
    Route::get('order', 'addon\zzhc\app\adminapi\controller\order\Order@lists');
    //预约订单详情
    Route::get('order/:id', 'addon\zzhc\app\adminapi\controller\order\Order@info');
    //添加预约订单
    Route::post('order', 'addon\zzhc\app\adminapi\controller\order\Order@add');
    //编辑预约订单
    Route::put('order/:id', 'addon\zzhc\app\adminapi\controller\order\Order@edit');
    //删除预约订单
    Route::delete('order/:id', 'addon\zzhc\app\adminapi\controller\order\Order@del');
    //订单状态列表
    Route::get('order/status', 'addon\zzhc\app\adminapi\controller\order\Order@getStatus');
    //订单取消
    Route::put('order/cancel/:id', 'addon\zzhc\app\adminapi\controller\order\Order@cancel');

    //订单操作日志列表
    Route::get('order_log', 'addon\zzhc\app\adminapi\controller\order\OrderLog@lists');
    //订单操作日志详情
    Route::get('order_log/:id', 'addon\zzhc\app\adminapi\controller\order\OrderLog@info');
    //添加订单操作日志
    Route::post('order_log', 'addon\zzhc\app\adminapi\controller\order\OrderLog@add');
    //编辑订单操作日志
    Route::put('order_log/:id', 'addon\zzhc\app\adminapi\controller\order\OrderLog@edit');
    //删除订单操作日志
    Route::delete('order_log/:id', 'addon\zzhc\app\adminapi\controller\order\OrderLog@del');

    //办卡会员列表
    Route::get('vip_member', 'addon\zzhc\app\adminapi\controller\vip\Member@lists');
    //办卡会员详情
    Route::get('vip_member/:id', 'addon\zzhc\app\adminapi\controller\vip\Member@info');
    //添加办卡会员
    Route::post('vip_member', 'addon\zzhc\app\adminapi\controller\vip\Member@add');
    //编辑办卡会员
    Route::put('vip_member/:id', 'addon\zzhc\app\adminapi\controller\vip\Member@edit');
    //删除办卡会员
    Route::delete('vip_member/:id', 'addon\zzhc\app\adminapi\controller\vip\Member@del');

    //办卡订单列表
    Route::get('vip/order', 'addon\zzhc\app\adminapi\controller\vip\Order@lists');
    //办卡订单详情
    Route::get('vip/order/:id', 'addon\zzhc\app\adminapi\controller\vip\Order@info');
    //添加办卡订单
    Route::post('vip/order', 'addon\zzhc\app\adminapi\controller\vip\Order@add');
    //编辑办卡订单
    Route::put('vip/order/:id', 'addon\zzhc\app\adminapi\controller\vip\Order@edit');
    //删除办卡订单
    Route::delete('vip/order/:id', 'addon\zzhc\app\adminapi\controller\vip\Order@del');

    //VIP会员操作日志列表
    Route::get('vip_log', 'addon\zzhc\app\adminapi\controller\vip\Log@lists');
    //VIP会员操作日志详情
    Route::get('vip_log/:id', 'addon\zzhc\app\adminapi\controller\vip\Log@info');
    //添加VIP会员操作日志
    Route::post('vip_log', 'addon\zzhc\app\adminapi\controller\vip\Log@add');
    //编辑VIP会员操作日志
    Route::put('vip_log/:id', 'addon\zzhc\app\adminapi\controller\vip\Log@edit');
    //删除VIP会员操作日志
    Route::delete('vip_log/:id', 'addon\zzhc\app\adminapi\controller\vip\Log@del');

    //领券记录列表
    Route::get('coupon_member', 'addon\zzhc\app\adminapi\controller\coupon\Member@lists');
    //领券记录详情
    Route::get('coupon_member/:id', 'addon\zzhc\app\adminapi\controller\coupon\Member@info');
    //添加领券记录
    Route::post('coupon_member', 'addon\zzhc\app\adminapi\controller\coupon\Member@add');
    //编辑领券记录
    Route::put('coupon_member/:id', 'addon\zzhc\app\adminapi\controller\coupon\Member@edit');
    //删除领券记录
    Route::delete('coupon_member/:id', 'addon\zzhc\app\adminapi\controller\coupon\Member@del');

})->middleware([
    AdminCheckToken::class,
    AdminCheckRole::class,
    AdminLog::class
]);
