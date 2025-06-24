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

namespace addon\zzhc\app\service\core\vip;

use addon\zzhc\app\model\vip\Member;
use app\service\core\member\CoreMemberService;
use core\base\BaseAdminService;
use addon\zzhc\app\dict\vip\LogDict;
use think\facade\Db;
use core\exception\CommonException;
use addon\zzhc\app\model\vip\Log;

/**
 * 办卡会员服务层
 * Class MemberService
 * @package addon\zzhc\app\service\admin\vip
 */
class CoreVipMemberService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Member();
    }

   

    /**
     * 添加|延期
     * @param array $data
     * @return mixed
     */
    public function change(array $where)
    {

        $memberInfo = (new CoreMemberService())->getInfoByMemberId($where['site_id'],$where['member_id']);

        $vipMemberInfo = $this->model->where([['site_id', "=", $where['site_id']],['member_id', "=", $where['member_id']]])->findOrEmpty()->toArray();


        $buyTime = intval($where['days']) * 60 * 60 * 24;

        Db::startTrans();
        try {
             
            if(empty($vipMemberInfo)){
                $type = LogDict::ADD;
                $overdueTime = time() + $buyTime;
                $vipMemberInfo = $this->model->create([
                    'site_id' => $where['site_id'],
                    'member_id' => $memberInfo['member_id'],
                    'headimg' => $memberInfo['headimg'],
                    'nickname' => $memberInfo['nickname'],
                    'mobile' => $memberInfo['mobile'],
                    'overdue_time' => $overdueTime,
                ]);
            }else{
                $type = LogDict::CHANGE;
                if($vipMemberInfo['overdue_time'] > time()){
                    $overdueTime = $vipMemberInfo['overdue_time'] + $buyTime;
                }
                $this->model->where([['id', '=', $vipMemberInfo['id']]])->update([
                    'overdue_time'=>$overdueTime,
                    'headimg' => $memberInfo['headimg'],
                    'nickname' => $memberInfo['nickname'],
                    'mobile' => $memberInfo['mobile'],
                ]);
            }

            //添加日志
            (new Log())->create([
                'site_id' => $where['site_id'],
                'vip_member_id' => $vipMemberInfo['id'],
                'main_type' => $where['main_type'],
                'main_id' => $where['main_id'],
                'days' => $where['days'],
                'type' => $type,
                'content' => $where['content']
            ]);

            Db::commit();

            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new CommonException($e->getMessage());
        }

    }

    /**
     * 获取办卡会员信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $site_id,int $member_id)
    {
        $field = 'id,site_id,member_id,headimg,nickname,mobile,overdue_time,create_time,update_time';

        $info = $this->model->field($field)->where([['site_id', "=", $site_id],['member_id', "=", $member_id]])->findOrEmpty()->toArray();
        return $info;
    }

    
    
}
