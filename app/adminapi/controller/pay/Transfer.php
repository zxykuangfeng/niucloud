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

namespace app\adminapi\controller\pay;

use app\dict\pay\TransferDict;
use app\service\admin\pay\PayService;
use app\service\admin\pay\TransferService;
use core\base\BaseAdminController;

class Transfer extends BaseAdminController
{
    /**
     * 转账方式
     * @return \think\Response
     */
    public function getWechatTransferScene(){
        return success(data:(new TransferService())->getWechatTransferScene());
    }

    /**
     * 设置场景id
     * @param $scene
     * @return void
     */
    public function setSceneId($scene){
        $data = $this->request->params([
            ['scene_id', ''],
        ]);
        return success(data:(new TransferService())->setSceneId($scene, $data));
    }

    /**
     * 设置业务转账场景配置
     * @param $type
     * @return \think\Response
     */
    public function setTradeScene($type){
        $data = $this->request->params([
            ['scene', ''],
            ['infos', []],
            ['perception', ''],
        ]);
        return success(data:(new TransferService())->setTradeScene($type, $data));
    }
}