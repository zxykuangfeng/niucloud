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

namespace addon\zzhc\app\adminapi\controller\coupon;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\coupon\MemberService;


/**
 * 领券记录控制器
 * Class Member
 * @package addon\zzhc\app\adminapi\controller\coupon
 */
class Member extends BaseAdminController
{
   /**
    * 获取领券记录列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["name",""],
             ["expire_time",""],
             ["status",""]
        ]);
        return success((new MemberService())->getPage($data));
    }

    /**
     * 领券记录详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new MemberService())->getInfo($id));
    }

    /**
     * 添加领券记录
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["coupon_id",0],
             ["name",""],
             ["member_id",0],
             ["expire_time",0],
             ["use_time",0],
             ["money",0.00],
             ["atleast",0.00],
             ["receive_type",""],
             ["status",0]
        ]);
        $this->validate($data, 'addon\zzhc\app\validate\coupon\Member.add');
        $id = (new MemberService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 领券记录编辑
     * @param $id  领券记录id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["coupon_id",0],
             ["name",""],
             ["member_id",0],
             ["expire_time",0],
             ["use_time",0],
             ["money",0.00],
             ["atleast",0.00],
             ["receive_type",""],
             ["status",0]
        ]);
        $this->validate($data, 'addon\zzhc\app\validate\coupon\Member.edit');
        (new MemberService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 领券记录删除
     * @param $id  领券记录id
     * @return \think\Response
     */
    public function del(int $id){
        (new MemberService())->del($id);
        return success('DELETE_SUCCESS');
    }

    
}
