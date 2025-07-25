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

namespace app\service\api\toutiao;

use app\dict\member\MemberLoginTypeDict;
use app\dict\member\MemberRegisterTypeDict;
use app\service\api\login\LoginService;
use app\service\api\login\RegisterService;
use app\service\api\member\MemberConfigService;
use app\service\api\member\MemberService;
use app\service\core\toutiao\CoreToutiaoAuthService;
use core\base\BaseApiService;
use core\exception\ApiException;
use core\exception\AuthException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;


/**
 * 抖音小程序配置模型
 * Class ToutiaoAuthService
 * @package app\service\api\toutiao
 */
class ToutiaoAuthService extends BaseApiService
{

    public $core_toutiao_serve_service;

    public function __construct()
    {
        parent::__construct();
        $this->core_toutiao_serve_service = new CoreToutiaoAuthService();
    }

  /**
 * 通过 code 获取抖音小程序用户 openid 和 unionid
 *
 * @param string $code
 * @return array
 * @throws \Exception
 */
public function getUserInfoByCode(string $code): array
{
    // dd($this->site_id);
    // 获取 session 信息（含 openid 和 session_key 等）
    $result = $this->core_toutiao_serve_service->session($this->site_id, $code);

    // 这里你也可以加异常处理
    if (empty($result) || !isset($result['openid'])) {
        throw new \Exception('获取抖音 openid 失败');
    }

    // 提取 openid 和 unionid（如果有）
    $openid = $result['openid'] ?? '';
    $unionid = $result['unionid'] ?? '';
    // dd($unionid);
   return [ 'openid' => $openid, 'unionid' => $unionid ]; // 将重要信息返回给前端保存

}

    /**
     * 登录
     * @param $data
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws InvalidConfigException
     * @throws ModelNotFoundException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function login($data)
    {

      $user_info = $this->getUserInfoByCode($data['code']);
    //   dd($user_info);
        $openid = $user_info['openid'] ?? '';
        $unionid = $user_info['unionid'] ?? '';
        // dd($openid);
        $member_service = new MemberService();
        $member_info = $member_service->findMemberInfo([ 'douyin_openid' => $openid, 'site_id' => $this->site_id ]);
        if ($member_info->isEmpty() && !empty($unionid)) {
            $member_info = $member_service->findMemberInfo([ 'wx_unionid' => $unionid, 'site_id' => $this->site_id ]);
            if (!$member_info->isEmpty()) {
                $member_info->douyin_openid = $openid;
            }
        }
        // dd($member_info);
        $config = ( new MemberConfigService() )->getLoginConfig();
        $is_auth_register = $config[ 'is_auth_register' ];
        $is_force_access_user_info = $config[ 'is_force_access_user_info' ];
        $is_bind_mobile = $config[ 'is_bind_mobile' ];
        $is_mobile = $config[ 'is_mobile' ];
        // dd($member_info);
        if ($member_info->isEmpty()) {
        
            // 开启自动注册会员
            if ($is_auth_register) {
//   dd(222);
                // 开启强制获取会员信息并且开启强制绑定手机号，必须获取全部信息才能进行注册
                if ($is_force_access_user_info && $is_bind_mobile) {
                    // dd(22211);
                    if (!empty($data[ 'nickname' ]) && !empty($data[ 'headimg' ]) && !empty($data[ 'mobile' ])) {
                        return $this->register($openid, $data[ 'mobile' ], $data[ 'mobile_code' ], $unionid, $data[ 'nickname' ], $data[ 'headimg' ]);
                    } else {
                        return [ 'openid' => $openid, 'unionid' => $unionid ]; // 将重要信息返回给前端保存
                    }
                } else if ($is_force_access_user_info) {
                    // 开启强制获取会员信息时，必须获取到昵称和头像才能进行注册
                    if (!empty($data[ 'nickname' ]) && !empty($data[ 'headimg' ])) {
                        return $this->register($openid, '', '', $unionid, $data[ 'nickname' ], $data[ 'headimg' ]);
                    } else {
                        return [ 'openid' => $openid, 'unionid' => $unionid ]; // 将重要信息返回给前端保存
                    }
                } else if ($is_bind_mobile) {
                    // 开启强制绑定手机号，必须获取手机号才能进行注册
                    if (!empty($data[ 'mobile' ]) || !empty($data[ 'mobile_code' ])) {
                        return $this->register($openid, $data[ 'mobile' ], $data[ 'mobile_code' ], $unionid);
                    } else {
                        return [ 'openid' => $openid, 'unionid' => $unionid ]; // 将重要信息返回给前端保存
                    }
                } else if (!$is_force_access_user_info && !$is_bind_mobile) {
                    // 关闭强制获取用户信息、并且关闭强制绑定手机号的情况下允许注册
                    return $this->register($openid, '', '', $unionid);
                }

            } else {
                // 关闭自动注册，但是开启了强制绑定手机号，必须获取手机号才能进行注册
                if ($is_bind_mobile) {
                    if (!empty($data[ 'mobile' ]) || !empty($data[ 'mobile_code' ])) {
                        return $this->register($openid, $data[ 'mobile' ], $data[ 'mobile_code' ], $unionid);
                    } else {
                        return [ 'openid' => $openid, 'unionid' => $unionid ]; // 将重要信息返回给前端保存
                    }
                } else if($is_mobile) {
                    if (!empty($data[ 'mobile' ]) || !empty($data[ 'mobile_code' ])) {
                        return $this->register($openid, $data[ 'mobile' ], $data[ 'mobile_code' ], $unionid);
                    } else {
                        return [ 'openid' => $openid, 'unionid' => $unionid ]; // 将重要信息返回给前端保存
                    }
                }
            }
        } else {
            // 可能会更新用户和粉丝表
            $login_service = new LoginService();
            // 开启自动注册会员,获取到昵称和头像进行修改
            if ($is_auth_register) {
                if ($is_force_access_user_info) {
                    if (!empty($data[ 'nickname' ])) {
                        $member_info[ 'nickname' ] = $data[ 'nickname' ];
                    }
                    if (!empty($data[ 'headimg' ])) {
                        $member_info[ 'headimg' ] = $data[ 'headimg' ];
                    }
                }
                if ($is_bind_mobile) {
//                    if (!empty($data[ 'mobile' ])) {
//                        $member_info[ 'mobile' ] = $data[ 'mobile' ];
//                    }
                }
            }
            // dd(111);
            return $login_service->login($member_info, MemberLoginTypeDict::DOUYIN);
        }
    }

    /**
     * 注册
     * @param string $openid
     * @param string $mobile
     * @param string $mobile_code
     * @param string $wx_unionid
     * @param string $nickname
     * @param string $headimg
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function register(string $openid, string $mobile = '', string $mobile_code = '', string $wx_unionid = '', $nickname = '', $headimg = '')
    {
// dd($openid);
        if (empty($openid)) throw new AuthException('AUTH_LOGIN_TAG_NOT_EXIST');
        if (empty($mobile)) {
            if (!empty($mobile_code)) {
                $result = $this->core_toutiao_serve_service->getUserPhoneNumber($this->site_id, $mobile_code);
                if (empty($result)) throw new ApiException('WECHAT_EMPOWER_NOT_EXIST');
                $phone_info = $result[ 'phone_info' ];
                $mobile = $phone_info[ 'purePhoneNumber' ];
                if (empty($mobile)) throw new ApiException('WECHAT_EMPOWER_NOT_EXIST');
            }
            $is_verify_mobile = false;
        } else {
            $is_verify_mobile = true;
        }
        $member_service = new MemberService();
        $member_info = $member_service->findMemberInfo([ 'douyin_openid' => $openid, 'site_id' => $this->site_id ]);
        if (!$member_info->isEmpty()) throw new AuthException('MEMBER_IS_EXIST');//账号已存在, 不能在注册

        if (!empty($wx_unionid)) {
            $member_info = $member_service->findMemberInfo([ 'wx_unionid' => $wx_unionid, 'site_id' => $this->site_id ]);
            if (!$member_info->isEmpty()) throw new AuthException('MEMBER_IS_EXIST');//账号已存在, 不能在注册
        }
        //  dd($openid);
        $register_service = new RegisterService();
        return $register_service->register($mobile ?? '',
            [
                'douyin_openid' => $openid,
                'wx_unionid' => $wx_unionid,
                'nickname' => $nickname,
                'headimg' => $headimg,
            ],
            MemberRegisterTypeDict::DOUYIN,
            $is_verify_mobile ?? false
        );

    }

    /**
     * 更新openid（用于账号密码或手机号注册时未正常获取到openid时再次获取）
     * @param string $code
     * @return true
     */
    public function updateOpenid(string $code)
    {
        [
            $openid,
            $unionid,
//            $avatar,
//            $nickname,
//            $sex
        ] = $this->getUserInfoByCode($code);
        $member_service = new MemberService();
        $member = $member_service->findMemberInfo([ 'douyin_openid' => $openid, 'site_id' => $this->site_id ]);
        if (!$member->isEmpty()) throw new AuthException('MEMBER_OPENID_EXIST');//openid已存在

        $member_info = $member_service->findMemberInfo([ 'member_id' => $this->member_id, 'site_id' => $this->site_id ]);
        if ($member_info->isEmpty()) throw new AuthException('MEMBER_NOT_EXIST');//账号不存在
        $member_service->editByFind($member_info, [ 'douyin_openid' => $openid ]);
        return true;
    }

}