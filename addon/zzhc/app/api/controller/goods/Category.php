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
use addon\zzhc\app\service\api\goods\CategoryService;


/**
 * 项目分类控制器
 * Class Category
 * @package addon\zzhc\app\api\controller\goods
 */
class Category extends BaseApiController
{
   /**
    * 获取项目分类列表
    * @return \think\Response
    */
    public function lists(){
        return success((new CategoryService())->getAll());
    }
    
}
