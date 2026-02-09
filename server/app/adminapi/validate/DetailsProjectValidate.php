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

namespace app\adminapi\validate;


use app\common\validate\BaseValidate;


/**
 * DetailsProject验证器
 * Class DetailsProjectValidate
 * @package app\adminapi\validate
 */
class DetailsProjectValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id' => 'require',
        'customer_id' => 'require',
        'driver_id' => 'require',
        'vehicle_id' => 'require',
        'project_type_id' => 'require',
        // 'route_id' => 'require',
        'time' => 'require',
        // 'note' => 'require',
        'status' => 'require',
        // 'order_img' => 'require',
        'parking_fee' => 'require',
        'toll' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'customer_id' => '客户管理',
        'driver_id' => '司机管理',
        'vehicle_id' => '车辆管理',
        'project_type_id' => '项目管理',
        'transport_company_id' => '运输公司管理',
        'route_id' => '路线管理',
        'dispatch_order_number' => '调度单号',
        'number_of_outlets' => '门店数',
        'starting_price' => '起步价',
        'time' => '时间',
        'note' => '备注',
        'unit_price' => '单价',
        'status' => '状态',
        'order_img' => '订单照片',
        'fuel_amount' => '加油量',
        'fuel_costs' => '加油费用',
        'maintenance_cost' => '维修费用',
        'other_expenses'=> '其它费用',
    ];


    /**
     * @notes 添加场景
     * @return DetailsProjectValidate
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public function sceneAdd()
    {
        return $this->only(['customer_id', 'driver_id', 'vehicle_id', 'project_type_id', 'transport_company_id', 'route_id', 'dispatch_order_number', 'number_of_outlets', 'starting_price', 'time', 'note', 'unit_price', 'status', 'order_img']);
    }


    /**
     * @notes 编辑场景
     * @return DetailsProjectValidate
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'customer_id', 'driver_id', 'vehicle_id', 'project_type_id', 'transport_company_id', 'route_id', 'dispatch_order_number', 'number_of_outlets', 'starting_price', 'time', 'note', 'unit_price', 'status', 'order_img']);
    }


    /**
     * @notes 删除场景
     * @return DetailsProjectValidate
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return DetailsProjectValidate
     * @author likeadmin
     * @date 2025/12/31 00:12
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }
}
