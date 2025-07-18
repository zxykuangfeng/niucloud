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
            ['link_type', 1],
            
        ]);
        $service = new ToutiaoOpenService();
        return success($service->getAuthLink($params));
    }



     /**
     * 获取抖音小程序二维码
     * @return Response
     */
    public function getQrcode(): Response
    {
        
        // dd(2222);
        $params = $this->request->params([
            ['authorizer_access_token', ''],
            ['version', 'latest'],
            ['path', ''],
        ]);
  
        $accessToken = $params['authorizer_access_token'];    
        // dd($accessToken);
        unset($params['authorizer_access_token']);
        $service = new ToutiaoOpenService();
        $file = $service->getQrcode($accessToken, $params);
        return success(['path' => $file]);
    }
    
    
    /**
     * 获取已授权抖音小程序二维码
     * @return Response
     */
    public function getToutiaoQrcode(): Response
    {
        
        // dd(3333);
        $params = $this->request->params([
            ['version', 'latest'],
            ['path', ''],
        ]);
        $service = new ToutiaoOpenService();
        $file = $service->getToutiaoQrcode($params['version'], $params['path']);
        return success(['path' => $file]);
    }
    
     /**
     * 提审已授权抖音小程序
     * @return Response
     */
    public function auditToutiaoPackage(): Response
    {
        $params = $this->request->params([
            ['host_names', []],
            ['audit_note', ''],
            ['audit_way', 0],
        ]);
        $service = new ToutiaoOpenService();
        $res = $service->auditToutiaoPackage($params['host_names'], $params['audit_note'], $params['audit_way']);
        return success($res);
    }
}