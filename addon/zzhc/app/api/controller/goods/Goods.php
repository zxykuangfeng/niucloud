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

namespace addon\zzhc\app\api\controller\goods;

use core\base\BaseApiController;
use addon\zzhc\app\service\api\goods\GoodsService;


/**
 * 项目控制器
 * Class Goods
 * @package addon\zzhc\app\api\controller\goods
 */
class Goods extends BaseApiController
{
   /**
    * 获取项目列表
    * @return \think\Response
    */
    public function lists(){
        $data = $this->request->params([
             ["category_id",""],
        ]);
        return success((new GoodsService())->getPage($data));
    }
   
}
