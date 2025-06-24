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

namespace addon\zzhc\app\adminapi\controller;

use addon\zzhc\app\service\admin\StatService;
use core\base\BaseAdminController;

/**
 * 数据统计
 */
class Stat extends BaseAdminController
{
    /**
     * 统计数据
     * @return \think\Response
     */
    public function info() {
        return success(data: (new StatService())->getStatData());
    }

    
}
