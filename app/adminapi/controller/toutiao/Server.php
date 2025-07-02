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

namespace app\adminapi\controller\toutiao;

use app\service\admin\toutiao\ToutiaoServerService;
use core\base\BaseAdminController;
use think\Response;

class Server extends BaseAdminController
{
    /**
     * 今日头条开放平台回调接收
     * @return Response
     */
    public function receiveTicket()
    {
        ob_clean();
        $result = (new ToutiaoServerService())->receiveTicket();
        return response($result)->header([
            'Content-Type' => 'text/plain;charset=utf-8'
        ]);
    }
}