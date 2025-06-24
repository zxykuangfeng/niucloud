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

namespace addon\zzhc\app\adminapi\controller\order;

use core\base\BaseAdminController;
use addon\zzhc\app\service\admin\order\OrderLogService;


/**
 * 订单操作日志控制器
 * Class OrderLog
 * @package addon\zzhc\app\adminapi\controller\order
 */
class OrderLog extends BaseAdminController
{
   /**
    * 获取订单操作日志列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["main_type",""],
             ["main_id",""],
             ["status",""],
             ["type",""],
             ["create_time",["",""]]
        ]);
        return success((new OrderLogService())->getPage($data));
    }

    /**
     * 订单操作日志详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new OrderLogService())->getInfo($id));
    }

    /**
     * 添加订单操作日志
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["order_id",0],
             ["main_type",""],
             ["main_id",0],
             ["status",0],
             ["type",""],
             ["content",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\order\OrderLog.add');
        $id = (new OrderLogService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * 订单操作日志编辑
     * @param $id  订单操作日志id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["main_type",""],
             ["main_id",0],
             ["status",0],
             ["type",""],
             ["content",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\order\OrderLog.edit');
        (new OrderLogService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * 订单操作日志删除
     * @param $id  订单操作日志id
     * @return \think\Response
     */
    public function del(int $id){
        (new OrderLogService())->del($id);
        return success('DELETE_SUCCESS');
    }

    
}
