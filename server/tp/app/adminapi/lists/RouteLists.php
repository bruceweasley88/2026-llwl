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
use app\common\model\Route;
use app\common\lists\ListsSearchInterface;


/**
 * Route列表
 * Class RouteLists
 * @package app\adminapi\lists
 */
class RouteLists extends BaseAdminDataLists implements ListsSearchInterface
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public function setSearch(): array
    {
        return [
            '%like%' => ['route_name', 'start_point', 'end_point', 'distance_km'],
        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public function lists(): array
    {
        return Route::where($this->searchWhere)
            ->field("la_route.*")
            ->field('la_amount_formula.name as amount_formula_name')
            ->field('la_commission_formula.name as commission_formula_name')
            // 客户表字段
            ->field('la_customer.contact_phone as customer_contact_phone')
            ->field('la_customer.contact_person as customer_contact_person')
            ->field('la_customer.customer_name as customer_name')
            ->leftJoin('la_amount_formula', 'la_route.amount_formula_id = la_amount_formula.id')
            ->leftJoin('la_commission_formula', 'la_route.commission_formula_id = la_commission_formula.id')
            ->leftJoin('la_customer', 'la_route.customer_id = la_customer.id')
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public function count(): int
    {
        return Route::where($this->searchWhere)->count();
    }

}