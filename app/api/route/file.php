<?php
// +----------------------------------------------------------------------
// | Niucloud-admin 企业快速开发的saas管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

use app\api\middleware\ApiChannel;
use app\api\middleware\ApiCheckToken;
use app\api\middleware\ApiLog;
use think\facade\Route;


/**
 * 会员个人信息管理
 */
Route::group('file', function() {

    /***************************************************** 会员管理 ****************************************************/
    //上传图片
    Route::post('image', 'upload.Upload/image');
    //上传视频
    Route::post('video', 'upload.Upload/video');
    //拉取图片
    Route::post('image/fetch', 'upload.Upload/imageFetch');

})->middleware(ApiChannel::class)
    ->middleware(ApiCheckToken::class, true)
    ->middleware(ApiLog::class);

/**
 * 会员个人信息管理
 */
Route::group('file', function() {

    //base64图片
    Route::post('image/base64', 'upload.Upload/imageBase64');

})->middleware(ApiChannel::class)
    ->middleware(ApiCheckToken::class, false)
    ->middleware(ApiLog::class);
