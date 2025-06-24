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

namespace addon\zzhc\app\service\core\order;

use addon\zzhc\app\model\order\OrderLog;
use core\base\BaseCoreService;

/**
 *  订单日志服务层
 */
class CoreOrderLogService extends BaseCoreService
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new OrderLog();
    }


    /**
     * 订单日志
     * @param $data
     * @return true
     */
    public function add(array $data)
    {
        $this->model->create($data);
        return true;
    }
    

}
