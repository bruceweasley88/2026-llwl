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


use app\common\model\DetailsProject;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * DetailsProject逻辑
 * Class DetailsProjectLogic
 * @package app\adminapi\logic
 */
class DetailsProjectLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            DetailsProject::create([
                'customer_id' => $params['customer_id'],
                'driver_id' => $params['driver_id'],
                'start_vehicle_id' => $params['start_vehicle_id'],
                'vehicle_id' => $params['vehicle_id'],
                'project_type_id' => $params['project_type_id'],
                'route_id' => $params['route_id'],
                'dispatch_order_number' => $params['dispatch_order_number'],
                'time' => $params['time'],
                'note' => $params['note'],
                'status' => $params['status'],
                'order_img' => $params['order_img'],
                'parking_fee' => $params['parking_fee'],
                'toll' => $params['toll'],
                'fuel_amount' => $params['fuel_amount'],
                'fuel_costs' => $params['fuel_costs'],
                'maintenance_cost' => $params['maintenance_cost'],
                'other_expenses' => $params['other_expenses'],
                'is_reim_toll' => $params['is_reim_toll'],
                'is_reim_parking_fee' => $params['is_reim_parking_fee'],
                'is_reim_fuel_costs' => $params['is_reim_fuel_costs'],
                'is_reim_maintenance_cost' => $params['is_reim_maintenance_cost'],
                'is_reim_other_expenses' => $params['is_reim_other_expenses'],
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
     * @date 2025/12/31 00:12
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            DetailsProject::where('id', $params['id'])->update([
                'customer_id' => $params['customer_id'],
                'driver_id' => $params['driver_id'],
                'start_vehicle_id' => $params['start_vehicle_id'],
                'vehicle_id' => $params['vehicle_id'],
                'project_type_id' => $params['project_type_id'],
                'route_id' => $params['route_id'],
                'dispatch_order_number' => $params['dispatch_order_number'],
                'time' => $params['time'],
                'note' => $params['note'],
                'status' => $params['status'],
                'order_img' => $params['order_img'],
                'parking_fee' => $params['parking_fee'],
                'toll' => $params['toll'],
                'fuel_amount' => $params['fuel_amount'],
                'fuel_costs' => $params['fuel_costs'],
                'maintenance_cost' => $params['maintenance_cost'],
                'other_expenses' => $params['other_expenses'],
                'is_reim_toll' => $params['is_reim_toll'],
                'is_reim_parking_fee' => $params['is_reim_parking_fee'],
                'is_reim_fuel_costs' => $params['is_reim_fuel_costs'],
                'is_reim_maintenance_cost' => $params['is_reim_maintenance_cost'],
                'is_reim_other_expenses' => $params['is_reim_other_expenses'],
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
     * @date 2025/12/31 00:12
     */
    public static function delete(array $params): bool
    {
        return DetailsProject::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public static function detail($params): array
    {
        return DetailsProject::findOrEmpty($params['id'])->toArray();
    }
}