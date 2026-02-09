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


use app\common\model\Route;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * Route逻辑
 * Class RouteLogic
 * @package app\adminapi\logic
 */
class RouteLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            Route::create([
                'route_name' => $params['route_name'],
                'start_point' => $params['start_point'],
                'end_point' => $params['end_point'],
                'distance_km' => $params['distance_km'],
                'commission_formula_id' => $params['commission_formula_id'],
                'amount_formula_id' => $params['amount_formula_id'],
                'store_count' => $params['store_count'],
                'fuel_price' => $params['fuel_price'],
                'customer_id' => $params['customer_id'],
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
     * @date 2025/12/30 23:36
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            Route::where('id', $params['id'])->update([
                'route_name' => $params['route_name'],
                'start_point' => $params['start_point'],
                'end_point' => $params['end_point'],
                'distance_km' => $params['distance_km'],
                'commission_formula_id' => $params['commission_formula_id'],
                'amount_formula_id' => $params['amount_formula_id'],
                'store_count' => $params['store_count'],
                'fuel_price' => $params['fuel_price'],
                'customer_id' => $params['customer_id'],
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
     * @date 2025/12/30 23:36
     */
    public static function delete(array $params): bool
    {
        return Route::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public static function detail($params): array
    {
        return Route::findOrEmpty($params['id'])->toArray();
    }
}
