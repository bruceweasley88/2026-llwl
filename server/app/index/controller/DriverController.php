<?php

namespace app\index\controller;

use app\BaseController;
use app\common\service\JsonService;
use think\facade\Db;
use think\facade\Request;
use think\facade\Cache;
use app\adminapi\lists\DetailsProjectLists;


/**
 * passwrod写错了
 */
class DriverController extends BaseController
{
    /**
     * 司机登录
     */
    public function login()
    {
        $name = input('name');
        $password = input('password');

        if (empty($name) || empty($password)) {
            return JsonService::fail('用户名和密码不能为空');
        }

        $driver = Db::name('driver')
            ->where('name', $name)
            ->find();

        if (!$driver) {
            return JsonService::fail('用户名或密码错误');
        }

        if ($driver['passwrod'] !== $password) {
            return JsonService::fail('用户名或密码错误');
        }
        if ($driver['status'] !== '正常') {
            return JsonService::fail('账号已停用');
        }

        // 生成token
        $token = $this->generateToken($driver['id'], $driver['name']);

        // 缓存token信息 (7天有效期)
        $cacheKey = 'driver_token_' . $token;
        Cache::set($cacheKey, $driver, 7 * 24 * 3600);

        return JsonService::data([
            'token' => $token,
            'driver_id' => $driver['id'],
            'driver' => $driver,
            'name' => $driver['name']
        ], '登录成功');
    }

    /**
     * 获取当前登录信息
     */
    public function getInfo()
    {
        $driver = $this->getCurrentDriver();
        if (!is_array($driver)) {
            return $driver;
        }
        return JsonService::data($driver, "成功");
    }

    /**
     * 获取当前登录司机的全部明细列表
     */
    public function getDetailsList()
    {
        $driver = $this->getCurrentDriver();
        if (!is_array($driver)) {
            return $driver;
        }

        // 获取查询参数
        $startTime = Request::param('start_time');
        $endTime = Request::param('end_time');
        $id = Request::param('id');

        // 构建查询条件
        $where = [
            ['la_details_project.driver_id', '=', $driver['id']],
            ['la_details_project.status', 'in', ['已完成', '处理中']],
        ];

        // ID查询（固定ID）
        if ($id) {
            $where[] = ['la_details_project.id', '=', $id];
            // ID查询返回单条数据，没有就返回空数组
            $list = DetailsProjectLists::getDetailsProjectList(
                [],
                $where,
                0,
                1
            );
            return JsonService::data($list);
        }

        // 时间区间查询
        if ($startTime && $endTime) {
            $where[] = ['la_details_project.time', '>=', $startTime];
            $where[] = ['la_details_project.time', '<=', $endTime];
        } elseif ($startTime) {
            $where[] = ['la_details_project.time', '>=', $startTime];
        } elseif ($endTime) {
            $where[] = ['la_details_project.time', '<=', $endTime];
        }

        // 调用静态方法获取列表
        $list = DetailsProjectLists::getDetailsProjectList(
            [],
            $where,
            0,
            9999
        );

        return JsonService::data($list, "成功");
    }

    /**
     * 获取司机全部订单数量（按状态统计）
     */
    public function getOrderCount()
    {
        $driver = $this->getCurrentDriver();
        if (!is_array($driver)) {
            return $driver;
        }

        // 按状态分组统计
        $result = Db::name('details_project')
            ->where('driver_id', $driver['id'])
            ->field('status, COUNT(*) as count')
            ->group('status')
            ->select()
            ->toArray();

        // 转换为 {"处理中":1, "已完成": 2} 格式
        $statusCount = [];
        foreach ($result as $item) {
            $statusCount[$item['status']] = $item['count'];
        }

        return JsonService::data($statusCount, "成功");
    }

    /**
     * 获取当前登录司机信息
     * @return array|object 成功返回司机信息数组，失败返回错误响应对象
     */
    private function getCurrentDriver()
    {
        $token = Request::header('token');

        if (empty($token)) {
            return JsonService::fail('未登录');
        }

        // 调试：记录 token
        \think\facade\Log::write('Token: ' . $token, 'info');

        $driver = Cache::get('driver_token_' . $token);

        // 调试：记录缓存结果
        \think\facade\Log::write('Cache key: driver_token_' . $token . ', Result: ' . var_export($driver, true), 'info');

        if (!$driver) {
            return JsonService::fail('登录已过期，请重新登录');
        }

        // 从数据库获取最新信息
        $currentDriver = Db::name('driver')
            ->where('id', $driver['id'])
            ->find();

        if (!$currentDriver) {
            return JsonService::fail('用户不存在');
        }

        if ($currentDriver['status'] !== '正常') {
            return JsonService::fail('账号已停用');
        }

        // 移除密码字段
        unset($currentDriver['passwrod']);

        return $currentDriver;
    }

    /**
     * 获取表单下拉选项（客户、车辆、项目、路线）
     */
    public function getFormOptions()
    {
        $driver = $this->getCurrentDriver();
        if (!is_array($driver)) {
            return $driver;
        }

        // 获取客户列表（无status字段，全部查询）
        $customers = Db::name('customer')
            ->field('id, customer_name')
            ->select()
            ->toArray();

        // 获取车辆列表（有vehicle_status字段，过滤正常状态）
        $vehicles = Db::name('vehicle')
            ->field('id, vehicle_type, license_plate, vehicle_status')
            ->select()
            ->toArray();

        // 获取项目类型列表（无status字段，全部查询）
        $projectTypes = Db::name('project_type')
            ->field('id, type_name')
            ->select()
            ->toArray();

        // 获取路线列表（无status字段，全部查询）
        $routes = Db::name('route')
            ->field('id, route_name, start_point, end_point,customer_id')
            ->select()
            ->toArray();

        return JsonService::data([
            'customers' => $customers,
            'vehicles' => $vehicles,
            'projectTypes' => $projectTypes,
            'routes' => $routes
        ], '成功');
    }

    /**
     * 保存订单（新增或编辑）
     */
    public function saveOrder()
    {
        $driver = $this->getCurrentDriver();
        if (!is_array($driver)) {
            return $driver;
        }

        // 获取请求数据
        $id = input('id'); // 有 id 则是编辑
        $data = [
            'customer_id' => input('customer_id'),
            'driver_id' => $driver['id'], // 当前登录司机
            'start_vehicle_id' => input('start_vehicle_id'),
            'vehicle_id' => input('vehicle_id'),
            'project_type_id' => input('project_type_id'),
            'route_id' => input('route_id'),
            'dispatch_order_number' => input('dispatch_order_number'),
            'time' => input('time'),
            'note' => input('note'),
            'status' => '处理中',
            'order_img' => input('order_img'),
            'toll' => input('toll', 0),
            'parking_fee' => input('parking_fee', 0),
            'fuel_amount' => input('fuel_amount', 0),
            'fuel_costs' => input('fuel_costs', 0),
            'maintenance_cost' => input('maintenance_cost', 0),
            'other_expenses'=> input('other_expenses', 0),
            'is_reim_toll' => input('is_reim_toll', 0),
            'is_reim_parking_fee' => input('is_reim_parking_fee',0), 
            'is_reim_fuel_costs' => input('is_reim_fuel_costs', 0),
            'is_reim_maintenance_cost' => input('is_reim_maintenance_cost', 0),
            'is_reim_other_expenses' => input('is_reim_other_expenses', 0),
            // 'fuel_price' => input('fuel_price', 0),
        ];

        //验证必填字段
        $required = ['customer_id', 'vehicle_id', 'project_type_id', 
                     'dispatch_order_number', 'time', 'toll', 'parking_fee','start_vehicle_id'];
        foreach ($required as $field) {
            // 使用严格判断，避免 "0" 被 empty() 视为空
            if (!isset($data[$field]) || $data[$field] === '' || $data[$field] === null) {
                return JsonService::fail('请填写所有必填字段');
            }
        }

        // 编辑模式：检查状态
        if ($id) {
            $existingOrder = Db::name('details_project')->where('id', $id)->find();
            if (!$existingOrder) {
                return JsonService::fail('订单不存在');
            }
            if ($existingOrder['driver_id'] != $driver['id']) {
                return JsonService::fail('无权操作此订单');
            }
            if ($existingOrder['status'] !== '处理中') {
                return JsonService::fail('当前状态不允许编辑');
            }
            // 更新
            Db::name('details_project')->where('id', $id)->update($data);
            return JsonService::data(['id' => $id], '更新成功');
        } else {
            // 新增
            $id = Db::name('details_project')->insertGetId($data);
            return JsonService::data(['id' => $id], '添加成功');
        }
    }

    /**
     * 删除订单
     */
    public function deleteOrder()
    {
        $driver = $this->getCurrentDriver();
        if (!is_array($driver)) {
            return $driver;
        }

        $id = input('id');
        if (!$id) {
            return JsonService::fail('订单ID不能为空');
        }

        $order = Db::name('details_project')->where('id', $id)->find();
        if (!$order) {
            return JsonService::fail('订单不存在');
        }

        if ($order['driver_id'] != $driver['id']) {
            return JsonService::fail('无权操作此订单');
        }

        if ($order['status'] !== '处理中') {
            return JsonService::fail('当前状态不允许删除');
        }

        Db::name('details_project')->where('id', $id)->delete();
        return JsonService::data([], '删除成功');
    }

    /**
     * 生成token
     */
    private function generateToken($driverId, $name)
    {
        $timestamp = time();
        $secret = 'driver_secret_key_2026';
        $data = $driverId . '|' . $name . '|' . $timestamp;
        return md5($data . $secret); //. base64_encode($data);
    }

}