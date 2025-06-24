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

namespace addon\zzhc\app\service\api\vip;

use addon\zzhc\app\model\vip\Vip;
use addon\zzhc\app\service\core\vip\CoreVipConfigService;
use addon\zzhc\app\service\core\vip\CoreVipMemberService;

use core\base\BaseApiService;


/**
 * VIP套餐服务层
 * Class VipService
 * @package addon\zzhc\app\service\api\vip
 */
class VipService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Vip();
    }
    
    public function getVipConfig()
    {
        return (new CoreVipConfigService())->getConfig($this->site_id);
    }

    
    /**
     * 获取VIP套餐列表
     * @param array $where
     * @return array
     */
    public function getLists()
    {
        $field = 'vip_id,site_id,vip_name,days,price,sort,status,create_time,update_time';
        $order = 'sort desc';

        $list = $this->model->where([ [ 'site_id' ,"=", $this->site_id ],[ 'status' ,"=", 'up' ] ])->field($field)->order($order)->select()->toArray();
        return $list;
    }

    /**
     * 获取VIP套餐信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id)
    {
        $field = 'vip_id,site_id,vip_name,days,price,sort,status,create_time,update_time';

        $info = $this->model->field($field)->where([['vip_id', "=", $id],['status', "=", 'up']])->findOrEmpty()->toArray();
        return $info;
    }

    /**
     * 会员办卡信息
     */
    public function getMemberVip()
    {
        return (new CoreVipMemberService())->getInfo($this->site_id,$this->member_id);
    }
    
}
