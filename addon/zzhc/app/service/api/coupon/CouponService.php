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

namespace addon\zzhc\app\service\api\coupon;


use addon\zzhc\app\model\coupon\Coupon;
use addon\zzhc\app\model\coupon\Member as CouponMember;
use app\model\member\Member;
use core\base\BaseApiService;
use core\exception\CommonException;
use app\service\api\member\MemberService;
use think\facade\Db;

/**
 *  优惠券服务层
 */
class CouponService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Coupon();
    }

    /**
     * 获取优惠券列表
     * @return array
     */
    public function getPage()
    {
        
        $field = 'coupon_id,site_id,name,count,money,lead_count,used_count,atleast,is_show,validity_type,end_usetime,fixed_term,max_fetch,sort,create_time,update_time';
        $order = 'sort desc,create_time desc';

        $where[] = [ 'site_id', '=', $this->site_id ];
        $where[] = [ 'is_show', '=', 1 ];

        $search_model = $this->model
                ->field($field)
                ->where($where)
                ->order($order);
        $list = $this->pageQuery($search_model);
        $coupon_member = new CouponMember();
        foreach ($list[ 'data' ] as $k => &$v) {
            $member_info = ( new Member() )->where([ [ 'site_id', '=', $this->site_id ], [ 'member_id', '=', $this->member_id ] ])->field('member_id')->findOrEmpty()->toArray();
            if ($member_info) {

                $coupon_member_count = $coupon_member->where([ [ 'site_id', '=', $this->site_id ],[ 'member_id', '=', $this->member_id ], [ 'coupon_id', '=', $v[ 'coupon_id' ] ] ])->count();
                if ($coupon_member_count) {
                    $v[ 'is_receive' ] = 1;
                    $v[ 'member_receive_count' ] = $coupon_member_count;
                } else {
                    $v[ 'is_receive' ] = 0;
                    $v[ 'member_receive_count' ] = 0;
                }
            } else {
                $v[ 'member_receive_count' ] = 0;
                $v[ 'is_receive' ] = 0;
            }
        }
        return $list;
    }
    
    /**
     * 优惠券领取
     */
    public function receive($data)
    {

        $member_id = $this->member_id;
        $coupon_id = $data[ 'coupon_id' ];
        $number = $data[ 'number' ];
        $type = $data[ 'type' ];
        $coupon_member_model = new CouponMember();
        Db::startTrans();
        try {
            
            //判断是否已经领取过
            $member_coupon_count = $coupon_member_model->where([ [ 'coupon_id', '=', $coupon_id ], [ 'site_id', '=', $this->site_id ], [ 'member_id', '=', $member_id ] ])->count();
            //判断优惠券数量是否充足
            $info = $this->model->where([ [ 'coupon_id', '=', $coupon_id ], [ 'site_id', '=', $this->site_id ] ])->findOrEmpty()->toArray();
            if (empty($info)) {
                throw new CommonException('优惠券不存在');
            }
            if ($member_coupon_count == $info[ 'max_fetch' ]) {
                throw new CommonException('超过可领取数量');
            }

            if ($info[ 'count' ] == $info[ 'lead_count' ]) {
                throw new CommonException('优惠券已被领完');
            }
            $time = time();
            if ($info['receive_time_type'] == 1 && $info[ 'start_time' ] > 1) {
                if ($time < $info[ 'start_time' ]) {
                    throw new CommonException('优惠券不在领取时间范围内');
                }
                if ($time > $info[ 'end_time' ]) {
                    throw new CommonException('优惠券不在领取时间范围内');
                }
            }

            $coupon_data = [
                'lead_count' => $info[ 'lead_count' ] + $number,
            ];

            if ($info[ 'validity_type' ] == 0) {
                $expire_time = 86400 * $info[ 'fixed_term' ] + time();
            } else if ($info[ 'validity_type' ] == 1) {
                $expire_time = strtotime($info[ 'end_usetime' ]);
            } else{
                $expire_time = 0;
            }

            $memberInfo = (new MemberService())->getInfo();

            $member_coupon_data = [
                'site_id' => $this->site_id,
                'coupon_id' => $coupon_id,
                'member_id' => $member_id,
                'create_time' => time(),
                'expire_time' => $expire_time,
                'receive_type' => $type,
                'name' => $info[ 'name' ],
                'money' => $info[ 'money' ],
                'atleast' => $info['atleast'],
                'nickname' => $memberInfo['nickname'],
                'headimg' => $memberInfo['headimg'],
                'mobile' => $memberInfo['mobile'],
            ];
            $this->model->where([ [ 'coupon_id', '=', $coupon_id ] ])->update($coupon_data);
            $coupon_member_model->create($member_coupon_data);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw new CommonException($e->getMessage());
        }

    }

    /**
     * 会员优惠券列表
     */
    public function getMemberPage($data)
    {
        if (!empty($data[ 'status' ])) {
            if($data[ 'status' ] == 1){
                $where[] = [ 'use_time', '=', 0 ];
                $where[] = [ 'expire_time', '>', time() ];
            }

            if($data[ 'status' ] == 2){
                $where[] = [ 'use_time', '>', 0 ];
            }
            if($data[ 'status' ] == 3){
                $where[] = [ 'use_time', '=', 0 ];
                $where[] = [ 'expire_time', '<', time() ];
            }
        }
        $where[] = [ 'member_id', '=', $this->member_id ];
        $coupon_member_model = new CouponMember();
        $search_model = $coupon_member_model
            ->where($where)
            ->order([ 'id desc' ]);
        $list = $this->pageQuery($search_model);
        return $list;
    }
    
}
