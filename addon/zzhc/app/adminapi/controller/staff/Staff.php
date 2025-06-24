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

namespace addon\zzhc\app\adminapi\controller\staff;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\staff\StaffService;
use addon\zzhc\app\dict\staff\StaffDict;


/**
 * 员工控制器
 * Class Staff
 * @package addon\zzhc\app\adminapi\controller\staff
 */
class Staff extends BaseAdminController
{
   /**
    * 获取员工列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["store_id",""],
             ["staff_name",""],
             ["staff_mobile",""]
        ]);
        return success((new StaffService())->getPage($data));
    }

    /**
     * 员工详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new StaffService())->getInfo($id));
    }

    /**
     * 添加员工
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["store_id",0],
             ["member_id",null],
             ["staff_role",""],
             ["staff_headimg",""],
             ["staff_name",""],
             ["staff_mobile",""],
             ["staff_position",""],
             ["staff_experience",""],
             ["staff_image",""],
             ["staff_content",""],
             ["sort",0],
             ["status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\staff\Staff.add');
        $id = (new StaffService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 员工编辑
     * @param $id  员工id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["store_id",0],
             ["member_id",null],
             ["staff_role",""],
             ["staff_headimg",""],
             ["staff_name",""],
             ["staff_mobile",""],
             ["staff_position",""],
             ["staff_experience",""],
             ["staff_image",""],
             ["staff_content",""],
             ["sort",0],
             ["status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\staff\Staff.edit');
        (new StaffService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 员工删除
     * @param $id  员工id
     * @return \think\Response
     */
    public function del(int $id){
        (new StaffService())->del($id);
        return success('DELETE_SUCCESS');
    }

     /**
     * 员工角色
     * @return \think\Response
     */
    public function getRole()
    {
        return success(StaffDict::getRole());
    }

    /**
     * 所有员工
     */
    public function getStaffAll(){
        return success(( new StaffService())->getStaffAll());
    }

}
