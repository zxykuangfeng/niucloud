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
use addon\zzhc\app\service\admin\vip\LogService;


/**
 * VIP会员操作日志控制器
 * Class Log
 * @package addon\zzhc\app\adminapi\controller\vip
 */
class Log extends BaseAdminController
{
   /**
    * 获取VIP会员操作日志列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["content",["",""]]
        ]);
        return success((new LogService())->getPage($data));
    }

    /**
     * VIP会员操作日志详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new LogService())->getInfo($id));
    }

    /**
     * 添加VIP会员操作日志
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["vip_member_id",0],
             ["main_type",""],
             ["main_id",0],
             ["days",0],
             ["type",""],
             ["content",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\vip\Log.add');
        $id = (new LogService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * VIP会员操作日志编辑
     * @param $id  VIP会员操作日志id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["vip_member_id",0],
             ["main_type",""],
             ["main_id",0],
             ["days",0],
             ["type",""],
             ["content",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\vip\Log.edit');
        (new LogService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * VIP会员操作日志删除
     * @param $id  VIP会员操作日志id
     * @return \think\Response
     */
    public function del(int $id){
        (new LogService())->del($id);
        return success('DELETE_SUCCESS');
    }

    
}
