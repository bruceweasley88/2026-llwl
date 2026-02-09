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

namespace app\adminapi\lists;


use app\adminapi\lists\BaseAdminDataLists;
use app\common\model\DetailsProject;
use app\common\lists\ListsSearchInterface;


/**
 * DetailsProject列表
 * Class DetailsProjectLists
 * @package app\adminapi\lists
 */
class DetailsProjectLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['dispatch_order_number', 'number_of_outlets', 'time', 'note', 'order_img'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public function lists(): array
    {
        // 自定义搜索条件
        $where = [];

        // 如果有客户名称搜索，那么就添加搜索条件
        if (input('customer_name')) {
            $where[] = ['la_customer.customer_name', 'like', '%' . input('customer_name') . '%'];
        }
        if (input('customer_contact_person')) {
            $where[] = ['la_customer.contact_person', 'like', '%' . input('customer_contact_person') . '%'];
        }
        if (input('driver_name')) {
            $where[] = ['la_driver.name', 'like', '%' . input('driver_name') . '%'];
        }
        if (input('status')) {
            $where[] = ['la_details_project.status', '=', input('status')];
        }

        $list = self::getDetailsProjectList(
            $this->searchWhere,
            $where,
            $this->limitOffset,
            $this->limitLength
        );

        return $list;
    }

    /**
     * @notes 获取项目明细列表（静态方法，可复用）
     * @param array $searchWhere 基础搜索条件
     * @param array $where 自定义搜索条件
     * @param int $offset 偏移量
     * @param int $limit 每页数量
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getDetailsProjectList($searchWhere = [], $where = [], $offset = 0, $limit = 10): array
    {
        // 查询
        $list = DetailsProject::where($searchWhere)
            // 自定义搜索条件
            ->where($where)
            // 查询全部la_details_project字段
            ->field('la_details_project.*')
            // 查询司机姓名
            ->field('la_driver.name as driver_name')
            ->field('la_vehicle.vehicle_type as vehicle_vehicle_type, la_vehicle.fuel as vehicle_fuel, la_vehicle.license_plate as vehicle_license_plate')
            ->field('la_vehicle.start_price as vehicle_start_price, la_vehicle.odometer_coefficient as vehicle_odometer_coefficient, la_vehicle.size as vehicle_size')

            ->field('la_start_vehicle.vehicle_type as start_vehicle_vehicle_type, la_start_vehicle.fuel as start_vehicle_fuel, la_start_vehicle.license_plate as start_vehicle_license_plate')
            ->field('la_start_vehicle.start_price as start_vehicle_start_price, la_start_vehicle.odometer_coefficient as start_vehicle_odometer_coefficient, la_start_vehicle.size as start_vehicle_size')


            ->field('la_customer.customer_name as customer_name')
            ->field('la_project_type.type_name as project_type_type_name')
            ->field('la_transport_company.company_name as transport_company_name')
            // 公式
            ->field('la_commission_formula.name as commission_formula_name, la_commission_formula.formula as commission_formula_formula')
            ->field('la_amount_formula.name as amount_formula_name, la_amount_formula.formula as amount_formula_formula')
            // 客户表字段
            ->field('la_customer.contact_phone as customer_contact_phone')
            ->field('la_customer.contact_person as customer_contact_person')
            // 路线表字段
            ->field('la_route.route_name as route_route_name')
            ->field('la_route.start_point as route_start_point')
            ->field('la_route.end_point as route_end_point')
            ->field('la_route.distance_km as route_distance_km')
            ->field('la_route.store_count as route_store_count')
            ->field('la_route.fuel_price as route_fuel_price')
            // 关联表
            ->leftJoin('la_driver', 'la_details_project.driver_id = la_driver.id')
            ->leftJoin('la_vehicle', 'la_details_project.vehicle_id = la_vehicle.id')
            ->leftJoin('la_vehicle la_start_vehicle','la_details_project.start_vehicle_id = la_start_vehicle.id')
            ->leftJoin('la_customer', 'la_details_project.customer_id = la_customer.id')
            ->leftJoin('la_project_type', 'la_details_project.project_type_id = la_project_type.id')
            ->leftJoin('la_route', 'la_details_project.route_id = la_route.id')
            ->leftJoin('la_commission_formula', 'la_route.commission_formula_id = la_commission_formula.id')
            ->leftJoin('la_amount_formula', 'la_route.amount_formula_id = la_amount_formula.id')
            ->leftJoin('la_transport_company', 'la_driver.trans_company_id = la_transport_company.id')
            ->limit($offset, $limit)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();

        foreach ($list as $key => $item) {

            // 单价
            if (empty($item['amount_formula_formula'])) {
                $list[$key]['amount'] = 0;
            } else {
                $list[$key]['amount'] = calculateFormula($item, $item['amount_formula_formula']);
            }

            // 司机提成
            if (empty($item['commission_formula_formula'])) {
                $list[$key]['driver_commission'] = 0;
            } else {
                $list[$key]['driver_commission'] = calculateFormula($list[$key], $item['commission_formula_formula']);
            }
        }
        return $list;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public function count(): int
    {
        return DetailsProject::where($this->searchWhere)->count();
    }
}
