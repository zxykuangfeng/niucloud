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

namespace app\adminapi\controller\addon;

use app\service\admin\upgrade\UpgradeRecordsService;
use app\service\admin\upgrade\UpgradeService;
use core\base\BaseAdminController;
use think\Response;

class Upgrade extends BaseAdminController
{
    /**
     * 更新插件
     * @param $app_key
     * @return Response
     */
    public function upgrade($addon = '')
    {
        $data = $this->request->params([
            ['is_need_backup', true],
            ['is_need_cloudbuild', true],
        ]);
        return success(data: ( new UpgradeService() )->upgrade($addon, $data));
    }

    /**
     * 执行升级
     * @param $app_key
     * @return Response
     */
    public function execute()
    {
        return success(data: ( new UpgradeService() )->execute());
    }

    /**
     * 获取升级内容
     * @param $addon
     * @return Response
     */
    public function getUpgradeContent($addon = '')
    {
        return success(data: ( new UpgradeService() )->getUpgradeContent($addon));
    }

    /**
     * 获取正在进行的升级任务
     * @return Response
     */
    public function getUpgradeTask()
    {
        return success(data: ( new UpgradeService() )->getUpgradeTask());
    }

    /**
     * 升级前环境检测
     * @param $addon
     * @return Response
     */
    public function upgradePreCheck($addon = '')
    {
        return success(data: ( new UpgradeService() )->upgradePreCheck($addon));
    }

    /**
     * 清除
     * @return Response
     */
    public function clearUpgradeTask()
    {
        return success(data: ( new UpgradeService() )->clearUpgradeTask(0, 1));
    }

    public function operate($operate) {
        return success(( new UpgradeService() )->operate($operate));
    }

    /**
     * 获取升级记录分页列表
     * @return Response
     */
    public function getRecords()
    {
        $data = $this->request->params([
            [ "name", "" ],
        ]);
        return success(( new UpgradeRecordsService() )->getPage($data));
    }

    public function delRecords() {
        $data = $this->request->params([
            [ 'ids', '' ],
        ]);
        ( new UpgradeRecordsService() )->del($data['ids']);
        return success('DELETE_SUCCESS');
    }
}
