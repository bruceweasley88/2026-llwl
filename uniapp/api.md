# 全局公共参数

**全局Header参数**

| 参数名 | 示例值 | 参数类型 | 是否必填 | 参数描述 |
| --- | --- | ---- | ---- | ---- |
| token | 0d5661809b7a1f5a98a5d9a7a8239900 | string | 是 | - |

**全局Query参数**

| 参数名 | 示例值 | 参数类型 | 是否必填 | 参数描述 |
| --- | --- | ---- | ---- | ---- |
| 暂无参数 |

**全局Body参数**

| 参数名 | 示例值 | 参数类型 | 是否必填 | 参数描述 |
| --- | --- | ---- | ---- | ---- |
| 暂无参数 |

**全局认证方式**

> 无需认证

# 状态码说明

| 状态码 | 中文描述 |
| --- | ---- |
| 暂无参数 |

# 登录

> 创建人: 黄工

> 更新人: 黄工

> 创建时间: 2026-01-03 16:06:55

> 更新时间: 2026-01-03 16:06:55

```text
暂无描述
```

**接口状态**

> 开发中

**接口URL**

> /index/driver/login?name=小红&password=456789

**请求方式**

> GET

**Content-Type**

> none

**请求Query参数**

| 参数名 | 示例值 | 参数类型 | 是否必填 | 参数描述 |
| --- | --- | ---- | ---- | ---- |
| name | 小红 | string | 是 | - |
| password | 456789 | string | 是 | - |

**认证方式**

> 继承父级

**响应示例**

* 成功(200)

```javascript
{
	"code": 1,
	"show": 0,
	"msg": "",
	"data": {
		"token": "0d5661809b7a1f5a98a5d9a7a8239900",
		"driver_id": 2,
		"driver": {
			"id": 2,
			"name": "小红",
			"sex": "女",
			"passwrod": "456789",
			"trans_company_id": 7,
			"status": "正常",
			"Starting_price": "1"
		},
		"name": "小红"
	}
}
```

* 失败(404)

```javascript
暂无数据
```

**Query**

# 获取登录信息

> 创建人: 黄工

> 更新人: 黄工

> 创建时间: 2026-01-03 16:08:55

> 更新时间: 2026-01-03 16:08:55

```text
暂无描述
```

**接口状态**

> 开发中

**接口URL**

> /index/driver/getInfo

**请求方式**

> GET

**Content-Type**

> none

**认证方式**

> 继承父级

**响应示例**

* 成功(200)

```javascript
暂无数据
```

* 失败(404)

```javascript
暂无数据
```

**Query**

# 获取订单列表与详情

> 创建人: 黄工

> 更新人: 黄工

> 创建时间: 2026-01-03 16:09:41

> 更新时间: 2026-01-03 16:12:23

```text
暂无描述
```

**接口状态**

> 开发中

**接口URL**

> /index/driver/getDetailsList?id=查看单条记录&start_time=区间&end_time=区间

**请求方式**

> GET

**Content-Type**

> none

**请求Query参数**

| 参数名 | 示例值 | 参数类型 | 是否必填 | 参数描述 |
| --- | --- | ---- | ---- | ---- |
| id | 查看单条记录 | string | 否 | - |
| start_time | 区间 | string | 否 | - |
| end_time | 区间 | string | 否 | - |

**认证方式**

> 继承父级

**响应示例**

* 成功(200)

```javascript
{
	"code": 1,
	"show": 0,
	"msg": "",
	"data": [
		{
			"id": 7,
			"customer_id": 10,
			"driver_id": 2,
			"vehicle_id": 7,
			"project_type_id": 5,
			"transport_company_id": 7,
			"route_id": 4,
			"dispatch_order_number": 1,
			"number_of_outlets": 1,
			"starting_price": "2",
			"time": "2026-01-01",
			"note": "1",
			"unit_price": "12",
			"status": "处理中",
			"order_img": "default.png",
			"commission_formula_id": 1,
			"driver_name": "小红",
			"driver_Starting_price": "1",
			"vehicle_vehicle_type": "唐骏欧铃",
			"customer_name": "西岭凡",
			"project_type_type_name": "冷藏",
			"commission_formula_name": "通用计算公式",
			"commission_formula_formula": "10 + (起步价 + 单价) * 门店数",
			"customer_contact_phone": "12312312312",
			"customer_contact_person": "张三",
			"route_route_name": "广佛",
			"route_start_point": "广州",
			"route_end_point": "佛山",
			"route_distance_km": "12",
			"route_toll": "111",
			"route_Reference_fuel_consumption": "10",
			"route_Parking_fee": "20",
			"route_Fuel_consumption": "11",
			"driver_commission": 24
		}
	]
}
```

* 失败(404)

```javascript
暂无数据
```

**Query**

# 获取订单数量统计

> 创建人: 黄工

> 更新人: 黄工

> 创建时间: 2026-01-03 16:12:49

> 更新时间: 2026-01-03 16:12:49

```text
暂无描述
```

**接口状态**

> 开发中

**接口URL**

> /index/driver/getOrderCount

**请求方式**

> GET

**Content-Type**

> none

**认证方式**

> 继承父级

**响应示例**

* 成功(200)

```javascript
{
	"code": 1,
	"show": 0,
	"msg": "",
	"data": {
		"处理中": 1
	}
}
```

* 失败(404)

```javascript
暂无数据
```

**Query**
