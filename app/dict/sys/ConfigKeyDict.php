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

namespace app\dict\sys;

class ConfigKeyDict
{
    public const NIUCLOUD_CONFIG = 'NIUCLOUD_CONFIG';//牛云配置
    public const WECHAT = 'WECHAT';//微信公众号

    public const WEAPP = 'weapp';//微信小程序
    //抖音小程序
    public const TOUTIAO = 'toutiao';
    public const WECHAT_PAY = 'wechat_pay';//微信支付
    public const ALIPAY = 'alipay';//支付宝支付
    public const OFFLINE_PAY = 'offline_pay';//线下支付
    public const UPLOAD = 'upload';//上传配置
    public const DIY_BOTTOM = 'diy_bottom';//底部导航配置

    public const MEMBER_CASH_OUT = 'member_cash_out';//会员提现

    public const ADMIN_LOGIN = 'admin_login';//管理端登录注册设置
    public const ALIAPP = 'aliapp';//支付宝小程序

    public const H5 = 'h5';//h5

    public const WXOPLATFORM = 'WXOPLATFORM'; // 微信开放平台

     /**
     * 今日头条 component_ticket
     */
    public const TOUTIAO_TICKET = 'toutiao_ticket';

    public const WEAPP_AUTHORIZATION_INFO = 'weapp_authorization_info';

    public const WECHAT_AUTHORIZATION_INFO = 'wechat_authorization_info';

    public const WECHAT_TRANSFER_SCENE_CONFIG = 'WECHAT_TRANSFER_SCENE_CONFIG';//微信转账场景配置

    public const SMS = 'SMS';//短信配置
}
