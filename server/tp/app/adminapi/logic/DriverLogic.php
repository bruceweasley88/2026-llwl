<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\adminapi\logic;


use app\common\model\Driver;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * Driver逻辑
 * Class DriverLogic
 * @package app\adminapi\logic
 */
class DriverLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public static function add(array $params): bool
    {
        // 检查是否已存在同名司机
        $exists = Driver::where('name', $params['name'])->find();
        if ($exists) {
            self::setError('该司机名称已存在');
            return false;
        }

        Db::startTrans();
        try {
            Driver::create([
                'name' => $params['name'],
                'sex' => $params['sex'],
                'phone' => $params['phone'],
                'passwrod' => $params['passwrod'],
                'trans_company_id' => $params['trans_company_id'],
                'status' => $params['status'],
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 编辑
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public static function edit(array $params): bool
    {
        // 检查是否已存在同名司机（排除当前编辑的司机）
        $exists = Driver::where('name', $params['name'])
            ->where('id', '<>', $params['id'])
            ->find();
        if ($exists) {
            self::setError('该司机名称已存在');
            return false;
        }

        Db::startTrans();
        try {
            Driver::where('id', $params['id'])->update([
                'name' => $params['name'],
                'phone' => $params['phone'],
                'sex' => $params['sex'],
                'passwrod' => $params['passwrod'],
                'trans_company_id' => $params['trans_company_id'],
                'status' => $params['status'],
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public static function delete(array $params): bool
    {
        return Driver::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2025/12/30 23:43
     */
    public static function detail($params): array
    {
        return Driver::findOrEmpty($params['id'])->toArray();
    }
}