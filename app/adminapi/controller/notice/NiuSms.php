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

namespace app\adminapi\controller\notice;

use app\dict\sys\SmsDict;
use app\service\admin\notice\NoticeService;
use app\service\admin\notice\NiuSmsService;
use app\service\admin\notice\SmsService;
use app\service\core\notice\CoreNiuSmsService;
use core\base\BaseAdminController;
use core\exception\AdminException;
use think\Response;

class NiuSms extends BaseAdminController
{
    /**
     * 启用牛云短信
     * @return Response
     */
    public function enable()
    {
        $params = $this->request->params([
            ['is_enable', 0],
        ]);
        (new NiuSmsService())->enableNiuSms($params['is_enable']);
        return success("SUCCESS");
    }


    /**
     * 获取基础信息
     * @return Response
     */
    public function getConfig()
    {
        return success((new NiuSmsService())->getConfig());
    }

    public function getSmsPackageList()
    {
        $params = $this->request->params([
            ['package_name', ''],
            ['sms_num', ''],
            ['price_start', ""],
            ['price_end', ""],
            ['original_price_start', ""],
            ['original_price_end', ""],
            ['time_start', ""],
            ['time_end', ""],
        ]);
        $list = (new NiuSmsService())->packageList($params);
        return success($list);
    }

    /*******牛云*******/
    public function sendMobileCode()
    {
        $params = $this->request->params([
            ['mobile', ''],
            ['captcha_key', ''],
            ['captcha_code', ''],
        ]);
        $data = (new NiuSmsService())->sendMobileCode($params);
        return success($data);
    }

    /**
     * 获取图形验证码
     * @return Response
     */
    public function captcha()
    {
        $data = (new NiuSmsService())->captcha();
        return success($data);
    }

    /**
     * 注册牛云短信子账号
     * @return Response
     */
    public function registerAccount()
    {
        $data = $this->request->params([
            ['username', ""],
            ['company', ""],
            ['mobile', ""],
            ['password', ""],
            ['remark', ""],
            ['key', ""],
            ['code', ""],
        ]);
        $res = (new NiuSmsService())->registerAccount($data);
        return success($res);
    }

    /**
     * 登录牛云短信子账号
     * @return Response
     */
    public function loginAccount()
    {
        $params = $this->request->params([
            ['username', ""],
            ['password', ""],
        ]);
        $data = (new NiuSmsService())->loginAccount($params);
        return success($data);
    }

    /**
     * 获取短信发送列表
     * @param $username
     * @return Response
     */
    public function accountSendList($username)
    {
        $params = $this->request->params([
            ['content',''],
            ['mobile',''],
            ['smsStatus','']
        ]);
        $data = (new NiuSmsService())->accountSendList($username,$params);
        return success($data);
    }

    /**
     * 获取子账户信息
     * @param $username
     * @return Response
     */
    public function accountInfo($username)
    {
        $data = (new NiuSmsService())->accountInfo($username);
        return success($data);
    }

    /**
     * 修改子账户信息
     * @param $username
     * @return Response
     */
    public function editAccount($username)
    {
        $params = $this->request->params([
            ['new_mobile', ""],
            ['mobile', ""],
            ['code', ""],
            ['key', ""],
            ['signature', ""],
        ]);
        $data = (new NiuSmsService())->editAccount($username, $params);
        return success($data);
    }

    /**
     * 重置密码
     * @param $username
     * @return Response
     */
    public function resetPassword($username)
    {
        $params = $this->request->params([
            ['mobile', ''],
            ['code', ''],
            ['key', ''],
        ]);
        $data = (new NiuSmsService())->resetPassword($username, $params);
        return success($data);
    }

    /**
     * 忘记密码
     * @param $username
     * @return Response
     */
    public function forgetPassword($username)
    {
        $params = $this->request->params([
            ['mobile', ''],
            ['code', ''],
            ['key', ''],
        ]);
        $data = (new NiuSmsService())->forgetPassword($username, $params);
        return success($data);
    }

    /**
     * 签名列表
     * @param $username
     * @return Response
     */
    public function signList($username)
    {
        $data = (new NiuSmsService())->getSignList($username);
        return success($data);
    }

    /**
     * 签名信息
     * @param $username
     * @return Response
     */
    public function signInfo($username)
    {
        $signature = $this->request->param('signature');
        $data = (new NiuSmsService())->signInfo($username, $signature);
        return success($data);
    }

    public function signCreateConfig()
    {
        return success((new NiuSmsService())->signCreateConfig());
    }

    /**
     * 签名创建、报备
     * @param $username
     * @return Response
     */
    public function signCreate($username)
    {
        $params = $this->request->params([
            ['signature', ""],
            ['contentExample', ""],
            ['companyName', ""],
            ['creditCode', ""],
            ['legalPerson', ""],
            ['principalName', ""],
            ['principalIdCard', ""],
            ['signSource', ""],
            ['signType', ""],
            ['imgUrl', ""],
            ['defaultSign', 0],
        ]);
        (new NiuSmsService())->signCreate($username, $params);
        return success("SUCCESS");
    }

    public function signDelete($username)
    {
        $params = $this->request->params([
            ['signatures', []]
        ]);
        $data = (new NiuSmsService())->signDelete($username, $params);
        return success($data);
    }

    /**
     * 同步模版状态
     * @param $sms_type
     * @param $username
     * @return Response
     */
    public function templateSync($sms_type, $username)
    {
        $data = (new NiuSmsService())->syncTemplateList($sms_type, $username);
        return success($data);
    }

    /**
     * 模版列表
     * @param $sms_type
     * @param $username
     * @return Response
     */
    public function templateList($sms_type, $username)
    {
        $data = (new NiuSmsService())->getTemplateList($sms_type, $username);
        return success($data);
    }

    /**
     * 模版信息
     * @param $sms_type
     * @param $username
     * @return Response
     */
    public function templateInfo($sms_type, $username)
    {
        $template_key = $this->request->param('template_key');
        $data = (new NiuSmsService())->templateInfo($sms_type, $username, $template_key);
        return success($data);
    }

    /**
     * 创建模版需要的配置
     * @return Response
     */
    public function templateCreateConfig()
    {
        return success((new NiuSmsService())->templateCreateConfig());
    }

    /**
     * 创建模版
     * @param $sms_type
     * @param $username
     * @return Response
     */
    public function templateCreate($sms_type, $username)
    {
        $params = $this->request->params([
            ['template_key', ""],
            ['template_type', ""],
            ['params_json', ""],
            ['template_id', ""],
        ]);
        $data = (new NiuSmsService())->templateUpsert($sms_type, $username, $params);
        return success($data);
    }

    /**
     * 创建订单
     * @param $username
     * @return Response
     */
    public function createOrder($username)
    {
        $params = $this->request->params([
            ['package_id', 0]
        ]);
        $data = (new NiuSmsService())->createOrder($username, $params['package_id']);
        return success($data);

    }

    /**
     * 订单计算
     * @param $username
     * @return Response
     */
    public function calculate($username)
    {
        $params = $this->request->params([
            ['package_id', 0]
        ]);
        $data = (new NiuSmsService())->calculate($username, $params['package_id']);
        return success($data);

    }

    /**
     * 获取支付信息
     * @param $username
     * @return Response
     */
    public function getPayInfo($username)
    {
        $params = $this->request->params([
            ['out_trade_no', '']
        ]);
        $data = (new NiuSmsService())->getPayInfo($username, $params);
        return success($data);
    }

    /**
     * 充值、订单列表
     * @param $username
     * @return Response
     */
    public function orderList($username)
    {
        $params = $this->request->params([
            ['out_trade_no', ''],
            ['order_status', ''],
            ['create_time_start', ''],
            ['create_time_end', ''],
        ]);
        $data = (new NiuSmsService())->orderList($username,$params);
        return success($data);
    }

    /**
     * 订单详情
     * @param $username
     * @return Response
     */
    public function orderInfo($username)
    {
        $params = $this->request->params([
            ['out_trade_no', '']
        ]);
        $data = (new NiuSmsService())->orderInfo($username, $params['out_trade_no']);
        return success($data);
    }

    /**
     * 订单状态
     * @param $username
     * @return Response
     */
    public function orderStatus($username)
    {
        $params = $this->request->params([
            ['out_trade_no', '']
        ]);
        $data = (new NiuSmsService())->orderStatus($username, $params['out_trade_no']);
        return success($data);
    }
}
