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

namespace app\service\api\login;

use app\dict\member\MemberLoginTypeDict;
use app\dict\member\MemberRegisterTypeDict;
use app\job\member\SetMemberNoJob;
use app\model\member\Member;
use app\service\api\captcha\CaptchaService;
use app\service\api\member\MemberConfigService;
use app\service\api\member\MemberService;
use core\base\BaseApiService;
use core\exception\AuthException;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 登录服务层
 * Class BaseService
 * @package app\service
 */
class RegisterService extends BaseApiService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new Member();
    }

    /**
     * 会员公共注册
     * @param string $mobile
     * @param $data
     * @param string $type
     * @param bool $is_verify_mobile
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function register(string $mobile, $data, string $type, bool $is_verify_mobile = true)
    {
       
        //校验注册方式
        if (empty(MemberRegisterTypeDict::getType()[ $type ]))
            throw new AuthException('REG_CHANNEL_NOT_EXIST');  
        $data = $this->bindByMobile($mobile, $data, $type, $is_verify_mobile); 
        
      
        $member_service = new MemberService();
        if (!is_array($data)) {
            $member_id = $data;
        } else {
            if (empty($data[ 'nickname' ])) {
                if (!empty($data[ 'username' ])) {
                    $data[ 'nickname' ] = $data[ 'username' ];
                } elseif (!empty($mobile)) {
                    $data[ 'nickname' ] = substr_replace($mobile, '****', 3, 4);
                } else {
                    $data[ 'nickname' ] = $this->createName();
                }
            }
            $data[ 'register_channel' ] = $this->channel;
            $data[ 'register_type' ] = $type;
            $data[ 'site_id' ] = $this->site_id;
            $pid = $this->request->get('pid', $this->request->post('pid', 0));
            if ($pid > 0) {
                $p_member_info = $member_service->findMemberInfo([ 'member_id' => $pid, 'site_id' => $this->site_id ]);
                if (!$p_member_info->isEmpty()) $data[ 'pid' ] = $pid;//设置上级推荐人
            }
            $member_id = ( new MemberService() )->add($data);
            $data[ 'member_id' ] = $member_id;
            event('MemberRegister', $data);
            SetMemberNoJob::dispatch([ 'site_id' => $this->site_id, 'member_id' => $member_id ]);
        }
        $member_info = $member_service->findMemberInfo([ 'member_id' => $member_id, 'site_id' => $this->site_id ]);
        if ($member_info->isEmpty()) throw new AuthException('MEMBER_NOT_EXIST');//账号不存在
        return ( new LoginService() )->login($member_info, $type);
    }

    /**
     * 随机创建一个昵称
     * @return string
     */
    public function createName()
    {
        $microtime = substr(microtime(true), strpos(microtime(true), '.') + 1);
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $username = '';
        for ($i = 0; $i < 2; $i++) {
            $username .= $chars[ random_int(0, ( strlen($chars) - 1 )) ];
        }

        return $microtime . strtoupper(base_convert(time() - 1420070400, 10, 36)) . $username;

    }

    /**
     * 账号注册
     * @param string $username
     * @param string $password
     * @param $mobile
     * @return array
     */
    public function account(string $username, string $password, $mobile, $wx_openid)
    {
        //todo  校验验证码  可以加try catch  后续
        ( new CaptchaService() )->check();

        //登录注册配置
        $config = ( new MemberConfigService() )->getLoginConfig();
        $is_username = $config[ 'is_username' ];
        //未开启账号密码登录注册
        if ($is_username != 1) throw new AuthException('MEMBER_USERNAME_LOGIN_NOT_OPEN');
        $member_service = new MemberService();
        $member_info = $member_service->findMemberInfo([ 'username' => $username, 'site_id' => $this->site_id ]);
        if (!$member_info->isEmpty()) throw new AuthException('MEMBER_IS_EXIST');//账号已存在

        $password_hash = create_password($password);
        $data = array(
            'username' => $username,
            'password' => $password_hash,
        );
        if (!empty($wx_openid)) {
            // 检测openid是否被使用
            $member_service = new MemberService();
            $member_info = $member_service->findMemberInfo([ 'wx_openid' => $wx_openid, 'site_id' => $this->site_id ]);
            if (empty($member_info->toArray())) {
                $data[ 'wx_openid' ] = $wx_openid;
            }
        }
        return $this->register($mobile, $data, MemberRegisterTypeDict::USERNAME);
    }

    /**
     * 手机号注册
     * @param $mobile
     * @return array
     */
    public function mobile($mobile)
    {
        //登录注册配置
        $config = ( new MemberConfigService() )->getLoginConfig();
        $is_mobile = $config[ 'is_mobile' ];
        $is_bind_mobile = $config[ 'is_bind_mobile' ];
        //未开启账号密码登录注册
        if ($is_mobile != 1 && $is_bind_mobile != 1) throw new AuthException('MOBILE_LOGIN_UNOPENED');
        $member_service = new MemberService();
        $member_info = $member_service->findMemberInfo([ 'mobile' => $mobile, 'site_id' => $this->site_id ]);
        if (!$member_info->isEmpty()) throw new AuthException('MEMBER_IS_EXIST');//账号已存在

        $data = array(
            'mobile' => $mobile,
        );
        return $this->register($mobile, $data, MemberRegisterTypeDict::MOBILE);
    }

    /**
     * 校验是否启用第三方登录注册
     * @return true
     */
    public function checkAuth()
    {
        $config = ( new MemberConfigService() )->getLoginConfig();
        $is_auth_register = $config[ 'is_auth_register' ];
        if ($is_auth_register != 1) throw new AuthException('AUTH_LOGIN_NOT_OPEN');//手机号已存在
        return true;
    }

    /**
     * 通过手机号尝试绑定已存在会员,没有就绑定数据(todo  仅限注册使用)
     * @param string $mobile
     * @param array $data
     * @param string $type
     * @param bool $is_verify
     * @return array|mixed
     */
    public function bindByMobile($mobile, array $data, string $type, bool $is_verify = true)
    {
        //   dd($type);
        $config = ( new MemberConfigService() )->getLoginConfig();
        $is_bind_mobile = $config[ 'is_bind_mobile' ];

        $with_field = match ( $type ) {
            MemberLoginTypeDict::USERNAME => 'username',
            MemberLoginTypeDict::MOBILE   => 'mobile',
            MemberLoginTypeDict::WECHAT   => 'wx_openid',
            MemberLoginTypeDict::WEAPP    => 'weapp_openid',
            MemberLoginTypeDict::DOUYIN   => 'douyin_openid',
        };
        if ($type == MemberLoginTypeDict::MOBILE || $type == MemberLoginTypeDict::WEAPP || $is_bind_mobile == 1) {
            //增加判断，否则公众号第三方注册会提示手机号必须填写
            if ($type == MemberLoginTypeDict::MOBILE || ( $type == MemberLoginTypeDict::USERNAME && $is_bind_mobile == 1 )) {
                if (empty($mobile)) throw new AuthException('MOBILE_NEEDED');//必须填写
                //todo  校验手机号验证码
                if ($is_verify) {
                    ( new LoginService() )->checkMobileCode($mobile);
                }
            }
            if (!empty($mobile)) {
                $member_service = new MemberService();
                $member = $member_service->findMemberInfo([ 'mobile' => $mobile, 'site_id' => $this->site_id ]);
                if (!$member->isEmpty()) {
                    if ($type == MemberLoginTypeDict::MOBILE) {
                        throw new AuthException('MOBILE_IS_EXIST');//手机号注册时发现手机号已存在账号
                    } else {
                        if ($member->$with_field != '') throw new AuthException('MOBILE_IS_EXIST');//手机号已存在
                        foreach ($data as $k => $v) {
                            $member->$k = $v;
                        }
                        $member->save();
                        return $member->member_id;
                    }
                }
                $data[ 'mobile' ] = $mobile;
            }
        }
        return $data;
    }

}
