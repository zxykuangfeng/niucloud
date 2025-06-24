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

namespace addon\zzhc\app\api\controller\staff;

use core\base\BaseApiController;
use addon\zzhc\app\service\api\staff\BarberService;


/**
 * 发型师控制器
 * Class Barber
 * @package addon\zzhc\app\api\controller\staff
 */
class Barber extends BaseApiController
{
   /**
    * 获取发型师列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["store_id",""],
        ]);
        return success((new BarberService())->getPage($data));
    }

    /**
     * 发型师详情
     * @param int $id
     * @return \think\Response
     */
    public function info(int $staff_id){
        return success((new BarberService())->getInfo($staff_id));
    }

}
