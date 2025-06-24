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

namespace addon\zzhc\app\model\order;

use core\base\BaseModel;
use addon\zzhc\app\dict\order\OrderDict;
use addon\zzhc\app\model\store\Store;
use app\model\member\Member;
use app\dict\common\ChannelDict;

/**
 * 预约订单模型
 * Class Order
 * @package addon\zzhc\app\model\order
 */
class Order extends BaseModel
{

    

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'order_id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'zzhc_order';

    /**
     * 订单状态
     * @param $value
     * @param $data
     * @return mixed|string
     */
    public function getStatusNameAttr($value, $data)
    {
        if (empty($data['status']))
            return '';
        return OrderDict::getStatus()[$data['status']] ?? '';
    }
    
    /**
     * 来源渠道
     * @param $value
     * @param $data
     * @return mixed|string|void
     */
    public function getOrderFromNameAttr($value, $data)
    {
        if (empty($data['order_from']))
            return '';
        return ChannelDict::getType()[$data['order_from']] ?? '';
    }

    /**
     * 搜索器:预约订单归属门店
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
     * 搜索器:预约订单订单编号
     * @param $value
     * @param $data
     */
    public function searchOrderNoAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("order_no", $value);
        }
    }
    
    /**
     * 搜索器:预约订单会员昵称
     * @param $value
     * @param $data
     */
    public function searchNicknameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("nickname", $value);
        }
    }
    
    /**
     * 搜索器:预约订单会员手机号
     * @param $value
     * @param $data
     */
    public function searchMobileAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("mobile", $value);
        }
    }
    
    /**
     * 搜索器:预约订单发型师姓名
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
     * 搜索器:预约订单项目名称
     * @param $value
     * @param $data
     */
    public function searchGoodsNameAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("goods_name", $value);
        }
    }
    
    /**
     * 搜索器:预约订单订单状态
     * @param $value
     * @param $data
     */
    public function searchStatusAttr($query, $value, $data)
    {
       if ($value) {
            $query->where("status", $value);
        }
    }
    
    /**
     * 搜索器:预约订单创建时间
     * @param $value
     * @param $data
     */
    public function searchCreateTimeAttr($query, $value, $data)
    {
        $start = empty($value[0]) ? 0 : strtotime($value[0]);
        $end = empty($value[1]) ? 0 : strtotime($value[1]);
        if ($start > 0 && $end > 0) {
             $query->where([["create_time", "between", [$start, $end]]]);
        } else if ($start > 0 && $end == 0) {
            $query->where([["create_time", ">=", $start]]);
        } else if ($start == 0 && $end > 0) {
            $query->where([["create_time", "<=", $end]]);
        }
    }
    
    public function store(){
        return $this->hasOne(Store::class, 'store_id', 'store_id')->joinType('left')->withField('store_id,store_logo,store_name,store_mobile,full_address,longitude,latitude')->joinType('left');
    }
    

    /**
     * 会员关联
     * @return HasOne
     */
    public function member()
    {
        return $this->hasOne(Member::class, 'member_id', 'member_id')->withField('member_id, member_no, username, mobile, nickname, headimg')->joinType('left');
    }
    
}
