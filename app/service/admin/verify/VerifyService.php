<?php
// +----------------------------------------------------------------------
// | Niucloud-admin 企业快速开发的saas管理平台
// +----------------------------------------------------------------------
// | 官方网址：https://www.niucloud.com
// +----------------------------------------------------------------------
// | niucloud团队 版权所有 开源版本可自由商用
// +----------------------------------------------------------------------
// | Author: Niucloud Team
// +----------------------------------------------------------------------

namespace app\service\admin\verify;

use app\model\verify\Verify;
use core\base\BaseAdminService;

/**
 * 订单核销
 * Class VerifyService
 * @package app\service\admin\verify
 */
class VerifyService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Verify();
    }

    /**
     * 获取核销记录列表
     * @param array $where
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPage(array $where = [])
    {
        $search_model = $this->model->where([ [ 'site_id', '=', $this->site_id ] ])->withSearch([ 'code', 'type', 'create_time', 'verifier_member_id' ], $where)
            ->with([ 'member' => function($query) {
                $query->field('member_id, nickname, mobile, headimg');
            } ])->field('*')->order('create_time desc')->append([ 'type_name' ]);
        $list = $this->pageQuery($search_model);
        return $list;
    }

    /**
     * 获取核销信息
     * @param string $verify_code
     * @return array
     */
    public function getDetail(string $verify_code)
    {
        $info = $this->model->where([
            [ 'site_id', '=', $this->site_id ],
            [ 'code', '=', $verify_code ]
        ])->field('*')
            ->with([ 'member' => function($query) {
                $query->field('member_id, nickname, mobile, headimg');
            } ])->append([ 'type_name' ])->findOrEmpty()->toArray();

        $info[ 'verify_info' ] = event('VerifyInfo', $info);
        return $info;

    }

}
