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
 * Route验证器
 * Class RouteValidate
 * @package app\adminapi\validate
 */
class RouteValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        'route_name' => 'require',
        'start_point' => 'require',
        'end_point' => 'require',
        'distance_km' => 'require'
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'route_name' => '路线名称',
        'start_point' => '起点',
        'end_point' => '终点',
        'distance_km' => '线路距离',
        'toll' => '高速费',
        'Parking_fee' => '停车费',
        'Reference_fuel_consumption' => '基准油耗',
        'Fuel_consumption' => '油耗',
        'customer_id' => '客户ID'
    ];


    /**
     * @notes 添加场景
     * @return RouteValidate
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public function sceneAdd()
    {
        return $this->only(['route_name','start_point','end_point','distance_km','toll','Parking_fee','Reference_fuel_consumption','Fuel_consumption','customer_id']);
    }


    /**
     * @notes 编辑场景
     * @return RouteValidate
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public function sceneEdit()
    {
        return $this->only(['id','route_name','start_point','end_point','distance_km','toll','Parking_fee','Reference_fuel_consumption','Fuel_consumption','customer_id']);
    }


    /**
     * @notes 删除场景
     * @return RouteValidate
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return RouteValidate
     * @author likeadmin
     * @date 2025/12/30 23:36
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}