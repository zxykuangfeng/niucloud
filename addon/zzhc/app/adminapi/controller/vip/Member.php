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

namespace addon\zzhc\app\adminapi\controller\vip;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\vip\MemberService;


/**
 * 办卡会员控制器
 * Class Member
 * @package addon\zzhc\app\adminapi\controller\vip
 */
class Member extends BaseAdminController
{
   /**
    * 获取办卡会员列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["nickname",""],
             ["mobile",""],
             ["overdue_time",["",""]]
        ]);
        return success((new MemberService())->getPage($data));
    }

    /**
     * 办卡会员详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new MemberService())->getInfo($id));
    }

    /**
     * 添加办卡会员
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["member_id",0],
             ["headimg",""],
             ["nickname",""],
             ["mobile",""],
             ["overdue_time",0],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\vip\Member.add');
        $id = (new MemberService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 办卡会员编辑
     * @param $id  办卡会员id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["member_id",0],
             ["headimg",""],
             ["nickname",""],
             ["mobile",""],
             ["overdue_time",0],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\vip\Member.edit');
        (new MemberService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 办卡会员删除
     * @param $id  办卡会员id
     * @return \think\Response
     */
    public function del(int $id){
        (new MemberService())->del($id);
        return success('DELETE_SUCCESS');
    }

    
}
