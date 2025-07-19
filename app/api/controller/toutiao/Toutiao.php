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

namespace app\api\controller\toutiao;

use app\service\api\toutiao\ToutiaoAuthService;
use core\base\BaseApiController;
use think\Response;

class Toutiao extends BaseApiController
{

    /**
     * 授权登录
     * @return Response
     */
    public function login()
    {
        $data = $this->request->params([
            [ 'code', '' ],
            [ 'nickname', '' ],
            [ 'headimg', '' ],
            [ 'mobile', '' ],
            [ 'mobile_code', '' ]
        ]);
        $toutiao_auth_service = new ToutiaoAuthService();
        
        
        $userInfo = $toutiao_auth_service->login($data); // ✅ 正确
         
        $openid = $userInfo['openid'] ?? '';
        $member_id = $userInfo['member_id'] ?? ''; 
        $token = $userInfo['token'] ?? ''; 
        return success([
        'openid'    => $userInfo['openid'] ?? '',
        'memberid'  => $userInfo['member_id'] ?? '',
        'token' => $userInfo['token'] ?? '', 
    ]);   
        
    }

    /**
     * 注册
     * @return Response
     */
    public function register()
    {
        $data = $this->request->params([
            [ 'openid', '' ],
            [ 'unionid', '' ],
            [ 'mobile_code', '' ],
            [ 'mobile', '' ],
        ]);

        $toutiao_auth_service = new ToutiaoAuthService();
        return success($toutiao_auth_service->register($data[ 'openid' ], $data[ 'mobile' ], $data[ 'mobile_code' ], $data[ 'unionid' ]));
    }


    /**
     * 更新openid
     * @return Response
     */
    public function updateOpenid()
    {
        $data = $this->request->params([ [ 'code', '' ] ]);
        $toutiao_auth_service = new ToutiaoAuthService();
        return success($toutiao_auth_service->updateOpenid($data[ 'code' ]));
    }

}