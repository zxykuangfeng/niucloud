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

namespace addon\zzhc\app\service\admin\vip;

use addon\zzhc\app\model\vip\Vip;
use addon\zzhc\app\service\core\vip\CoreVipConfigService;

use core\base\BaseAdminService;


/**
 * VIP套餐服务层
 * Class VipService
 * @package addon\zzhc\app\service\admin\vip
 */
class VipService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Vip();
    }

    /**
     * 获取VIP套餐列表
     * @param array $where
     * @return array
     */
    public function getPage(array $where = [])
    {
        $field = 'vip_id,site_id,vip_name,days,price,sort,status,create_time,update_time';
        $order = 'sort desc';

        $search_model = $this->model->where([ [ 'site_id' ,"=", $this->site_id ] ])->withSearch(["vip_name"], $where)->field($field)->order($order);
        $list = $this->pageQuery($search_model);
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

        $info = $this->model->field($field)->where([['vip_id', "=", $id]])->findOrEmpty()->toArray();
        return $info;
    }

    /**
     * 添加VIP套餐
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['site_id'] = $this->site_id;
        $res = $this->model->create($data);
        return $res->vip_id;

    }

    /**
     * VIP套餐编辑
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function edit(int $id, array $data)
    {

        $this->model->where([['vip_id', '=', $id],['site_id', '=', $this->site_id]])->update($data);
        return true;
    }

    public function setVipConfig(array $data)
    {
        $data['site_id'] = $this->site_id;
        return (new CoreVipConfigService())->setConfig($data);
    }
    
    public function getVipConfig()
    {
        return (new CoreVipConfigService())->getConfig($this->site_id);
    }


    /**
     * 删除VIP套餐
     * @param int $id
     * @return bool
     */
    public function del(int $id)
    {
        $model = $this->model->where([['vip_id', '=', $id],['site_id', '=', $this->site_id]])->find();
        $res = $model->delete();
        return $res;
    }
    
}
