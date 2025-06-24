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
use addon\zzhc\app\service\admin\staff\WorkService;


/**
 * 考勤管理控制器
 * Class Work
 * @package addon\zzhc\app\adminapi\controller\staff
 */
class Work extends BaseAdminController
{
   /**
    * 获取考勤管理列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["store_id",""],
             ["staff_id",""],
             ["status",""],
             ["create_time",["",""]]
        ]);
        return success((new WorkService())->getPage($data));
    }

    /**
     * 考勤管理详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new WorkService())->getInfo($id));
    }

    /**
     * 添加考勤管理
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["store_id",""],
             ["staff_id",0],
             ["work_status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\staff\Work.add');
        $id = (new WorkService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 考勤管理编辑
     * @param $id  考勤管理id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["store_id",""],
             ["staff_id",0],
             ["work_status",""],
             ["exp_duration",0],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\staff\Work.edit');
        (new WorkService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 考勤管理删除
     * @param $id  考勤管理id
     * @return \think\Response
     */
    public function del(int $id){
        (new WorkService())->del($id);
        return success('DELETE_SUCCESS');
    }

    
}
