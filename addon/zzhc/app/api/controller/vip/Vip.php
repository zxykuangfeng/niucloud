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

namespace addon\zzhc\app\api\controller\vip;

use core\base\BaseApiController;
use addon\zzhc\app\service\api\vip\VipService;


/**
 * VIP套餐控制器
 * Class Vip
 * @package addon\zzhc\app\api\controller\vip
 */
class Vip extends BaseApiController
{

    /**
     * 获取VIP配置
     * @return \think\Response
     */
    public function getConfig()
    {
        return success((new VipService())->getVipConfig());
    }

    /**
    * 获取VIP套餐列表
    * @return \think\Response
    */
    public function lists(){
        return success((new VipService())->getLists());
    }

    /**
     * 会员优惠券列表
     * @return Response
     */
    public function memberVip()
    {
        return success(( new VipService() )->getMemberVip());
    }
    
}
