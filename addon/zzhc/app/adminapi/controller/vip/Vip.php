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
use addon\zzhc\app\service\admin\vip\VipService;


/**
 * VIP套餐控制器
 * Class Vip
 * @package addon\zzhc\app\adminapi\controller\vip
 */
class Vip extends BaseAdminController
{
   /**
    * 获取VIP套餐列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["vip_name",""]
        ]);
        return success((new VipService())->getPage($data));
    }

    /**
     * VIP套餐详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $id){
        return success((new VipService())->getInfo($id));
    }

    /**
     * 添加VIP套餐
     * @return \think\Response
     */
    public function add(){
        $data = $this->request->params([
             ["vip_name",""],
             ["days",0],
             ["price",0.00],
             ["sort",0],
             ["status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\vip\Vip.add');
        $id = (new VipService())->add($data);
        return success('ADD_SUCCESS', ['id' => $id]);
    }

    /**
     * VIP套餐编辑
     * @param $id  VIP套餐id
     * @return \think\Response
     */
    public function edit(int $id){
        $data = $this->request->params([
             ["vip_name",""],
             ["days",0],
             ["price",0.00],
             ["sort",0],
             ["status",""],

        ]);
        $this->validate($data, 'addon\zzhc\app\validate\vip\Vip.edit');
        (new VipService())->edit($id, $data);
        return success('EDIT_SUCCESS');
    }

    /**
     * VIP套餐删除
     * @param $id  VIP套餐id
     * @return \think\Response
     */
    public function del(int $id){
        (new VipService())->del($id);
        return success('DELETE_SUCCESS');
    }

    /**
     * VIP设置配置
     * @return \think\Response
     */
    public function setConfig()
    {
        $data = $this->request->params([
            ["is_enable",1],
            ["discount",""],
            ["banner",""],
            ["statement",""],
        ]);

        (new VipService())->setVipConfig($data);
        return success('SUCCESS');
    }

    /**
     * 获取VIP配置
     * @return \think\Response
     */
    public function getConfig()
    {
        return success((new VipService())->getVipConfig());
    }


    
}
