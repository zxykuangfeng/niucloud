<?php
// +----------------------------------------------------------------------
// | Niucloud-api 企业快速开发的多应用管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace addon\zzhc\app\service\api\staff;

use addon\zzhc\app\model\staff\Work;

use core\base\BaseApiService;


/**
 * 考勤管理服务层
 * Class WorkService
 * @package addon\zzhc\app\service\api\staff
 */
class WorkService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Work();
    }

    /**
     * 获取考勤管理信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'id,site_id,store_id,staff_id,status,duration,create_time,update_time';

        $info = $this->model->field($field)->where([['id', "=", $id]])->findOrEmpty()->toArray();
        return $info;
    }

    
    
}
