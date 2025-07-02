<?php
namespace app\api\controller\toutiao;

use app\service\api\toutiao\ToutiaoOpenService;
use core\base\BaseApiController;
use think\Response;

class Open extends BaseApiController
{
    /**
     * 获取抖音开放平台授权链接
     * @return Response
     */
    public function getAuthLink(): Response
    {
        $params = $this->request->params([
            ['link_type', 1],
            ['app_name', ''],
            ['app_icon', ''],
            ['redirect_uri', ''],
        ]);
        $service = new ToutiaoOpenService();
        return success($service->getAuthLink($params));
    }
}