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

namespace app\model\sys;

use app\dict\sys\UpgradeDict;
use core\base\BaseModel;

/**
 * 备份记录表模型
 */
class SysBackupRecords extends BaseModel
{

    /**
     * 数据表主键
     * @var string
     *
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'sys_backup_records';

    protected $type = [
        'create_time' => 'timestamp',
        'complete_time' => 'timestamp'
    ];

    // 设置json类型字段
    protected $json = [ 'fail_reason' ];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    /**
     * 状态字段转化
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getStatusNameAttr($value, $data)
    {
        if (empty($data[ 'status' ])) return '';
        return UpgradeDict::getStatus($data[ 'status' ]);
    }

    /**
     * 备份目录
     * @param $value
     * @param $data
     * @return string
     */
    public function getBackupDirAttr($value, $data)
    {
        if (empty($data[ 'backup_key' ])) return '';
        return 'upgrade' . DIRECTORY_SEPARATOR . $data[ 'backup_key' ];
    }

    /**
     * 备份源码目录
     * @param $value
     * @param $data
     * @return string
     */
    public function getBackupCodeDirAttr($value, $data)
    {
        if (empty($data[ 'backup_key' ])) return '';

        $root_path = dirname(root_path()) . DIRECTORY_SEPARATOR;
        $upgrade_dir = $root_path . 'upgrade' . DIRECTORY_SEPARATOR;
        return $upgrade_dir . $data[ 'backup_key' ] . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR;
    }

    /**
     * 备份数据库目录
     * @param $value
     * @param $data
     * @return string
     */
    public function getBackupSqlDirAttr($value, $data)
    {
        if (empty($data[ 'backup_key' ])) return '';
        $root_path = dirname(root_path()) . DIRECTORY_SEPARATOR;
        $upgrade_dir = $root_path . 'upgrade' . DIRECTORY_SEPARATOR;
        return $upgrade_dir . $data[ 'backup_key' ] . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR;
    }

    /**
     * 搜索器:备份内容
     * @param $query
     * @param $value
     * @param $data
     */
    public function searchContentAttr($query, $value, $data)
    {
        if ($value != '') {
            $query->where("content", 'like', '%' . $this->handelSpecialCharacter($value) . '%');
        }
    }
}
