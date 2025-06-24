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

namespace addon\zzhc\app\model\staff;

use core\base\BaseModel;

use addon\zzhc\app\model\store\Store;
use app\model\member\Member;
use addon\zzhc\app\dict\staff\StaffDict;

/**
 * 员工模型
 * Class Staff
 * @package addon\zzhc\app\model\staff
 */
class Staff extends BaseModel
{

    

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'staff_id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'zzhc_staff';

    // 设置json类型字段
    protected $json = [ 'staff_role'];
    protected $jsonAssoc = true;

    public function getStaffRoleNameAttr($value, $data)
    {
        if($data){
            $value = [];
            foreach($data['staff_role'] as $role){
                $value[] = StaffDict::getRole($role);
            }
            return $value;
        }
    }
    

    /**
     * 搜索器:员工上班门店
     * @param $value
     * @param $data
     */
    public function searchStoreIdAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("store_id", $value);
        }
    }
    
    /**
     * 搜索器:员工员工姓名
     * @param $value
     * @param $data
     */
    public function searchStaffNameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("staff_name", $value);
        }
    }
    
    /**
     * 搜索器:员工联系电话
     * @param $value
     * @param $data
     */
    public function searchStaffMobileAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("staff_mobile", $value);
        }
    }

    public function getStaffImageArrAttr($value, $data)
    {
        if (isset($data[ 'staff_image' ]) && $data[ 'staff_image' ] != '') {
            return explode(',', $data[ 'staff_image' ]);
        }
        return [];
    }
    
    public function member(){
        return $this->hasOne(Member::class, 'member_id', 'member_id')->withField('member_id, member_no, username, mobile, nickname, headimg')->joinType('left');
    }
    
    public function store(){
       return $this->hasOne(Store::class, 'store_id', 'store_id')->joinType('left')->withField('store_name,store_id')->bind(['store_id_name'=>'store_name']);
    }

    public function work(){
        return $this->hasOne(Work::class, 'id', 'work_id')->joinType('left');
    }

}
