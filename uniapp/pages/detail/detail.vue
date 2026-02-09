<template>
	<view class="detail-page">
		<!-- 状态卡片（仅编辑模式） -->
		<view class="status-card" :class="statusCardClass" v-if="mode === 'edit'">
			<uni-icons :type="getStatusIcon(orderStatus)" size="40" color="#ffffff"></uni-icons>
			<view class="status-info">
				<view class="status-text">{{ orderStatus }}</view>
				<view class="status-desc">{{ getStatusDesc(orderStatus) }}</view>
			</view>
		</view>

		<!-- 提成卡片（已完成状态显示） -->
		<view class="commission-card" v-if="orderStatus === '已完成'">
			<view class="commission-icon">
				<uni-icons type="wallet" size="36" color="#43a047"></uni-icons>
			</view>
			<view class="commission-info">
				<view class="commission-label">提成金额</view>
				<view class="commission-value">¥{{ commissionAmount }}</view>
			</view>
		</view>

		<!-- 表单区域 -->
		<view class="form-section">
			<!-- 客户选择 -->
			<view class="form-item">
				<view class="form-label"><text class="required">*</text>客户</view>
				<picker mode="selector" :range="options.customers" range-key="customer_name" :value="customerIndex"
					@change="handleCustomerChange" :disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled || customerIndex === -1 }">
						<text v-if="customerIndex >= 0">{{ options.customers[customerIndex].customer_name }}</text>
						<text v-else class="placeholder">请选择客户</text>
						<uni-icons type="down" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 订单车型（按车型去重） -->
			<view class="form-item">
				<view class="form-label"><text class="required">*</text>订单车型</view>
				<search-picker style="text-align: right;" v-model="formData.vehicle_id" :options="vehicleListGroupType" label-key="vehicle_type"
					value-key="id" placeholder="请选择车型" :disabled="isDisabled" @change="handleVehicleTypeChange">
				</search-picker>
			</view>

			<view class="form-item">
				<view class="form-label"><text class="required">*</text>出发车辆</view>
				<picker mode="selector" :range="options.vehicles" range-key="display_name" :value="startVehicleIndex"
					@change="handleStartVehicleChange" :disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled || startVehicleIndex === -1 }">
						<text v-if="startVehicleIndex >= 0">{{ options.vehicles[startVehicleIndex].display_name }}</text>
						<text v-else class="placeholder">请选择车辆</text>
						<uni-icons type="down" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 项目选择 -->
			<view class="form-item">
				<view class="form-label"><text class="required">*</text>项目</view>
				<picker mode="selector" :range="options.projectTypes" range-key="type_name" :value="projectTypeIndex"
					@change="handleProjectTypeChange" :disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled || projectTypeIndex === -1 }">
						<text v-if="projectTypeIndex >= 0">{{ options.projectTypes[projectTypeIndex].type_name }}</text>
						<text v-else class="placeholder">请选择项目</text>
						<uni-icons type="down" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 路线选择（带搜索功能） -->
			<view class="form-item">
				<view class="form-label"><text class="required"></text>路线</view>
				<search-picker v-model="formData.route_id" :options="filteredRoutes" label-key="route_name" value-key="id"
					placeholder="请选择路线" :disabled="isDisabled" @change="handleRouteChange">
				</search-picker>
			</view>

			<!-- 调度单号 -->
			<view class="form-item">
				<view class="form-label">调度单号</view>
				<input class="form-input" v-model="formData.dispatch_order_number" placeholder="请输入调度单号" :disabled="isDisabled"
					:placeholder-class="isDisabled ? '' : 'input-placeholder'" />
			</view>

			<!-- 时间 -->
			<view class="form-item">
				<view class="form-label"><text class="required">*</text>时间</view>
				<picker mode="date" :value="formData.time" @change="handleTimeChange" :disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled || !formData.time }">
						<text v-if="formData.time">{{ formData.time }}</text>
						<text v-else class="placeholder">请选择时间</text>
						<uni-icons type="calendar" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 高速费 -->
			<view class="form-item">
				<view class="form-label">高速费</view>
				<input class="form-input" v-model="formData.toll" type="digit" placeholder="请输入高速费" :disabled="isDisabled"
					:placeholder-class="isDisabled ? '' : 'input-placeholder'" />
			</view>

			<!-- 停车费 -->
			<view class="form-item">
				<view class="form-label">停车费</view>
				<input class="form-input" v-model="formData.parking_fee" type="digit" placeholder="请输入停车费"
					:disabled="isDisabled" :placeholder-class="isDisabled ? '' : 'input-placeholder'" />
			</view>

			<!-- 加油量 -->
			<view class="form-item">
				<view class="form-label">加油量</view>
				<input class="form-input" v-model="formData.fuel_amount" type="digit" placeholder="请输入加油量"
					:disabled="isDisabled" :placeholder-class="isDisabled ? '' : 'input-placeholder'" />
			</view>

			<!-- 加油费用 -->
			<view class="form-item">
				<view class="form-label">电/油费用</view>
				<input class="form-input" v-model="formData.fuel_costs" type="digit" placeholder="请输入电/油费用"
					:disabled="isDisabled" :placeholder-class="isDisabled ? '' : 'input-placeholder'" />
			</view>

			<!-- 维修费用 -->
			<view class="form-item">
				<view class="form-label">维修费用</view>
				<input class="form-input" v-model="formData.maintenance_cost" type="digit" placeholder="请输入维修费用"
					:disabled="isDisabled" :placeholder-class="isDisabled ? '' : 'input-placeholder'" />
			</view>

			<!-- 其他费用 -->
			<view class="form-item">
				<view class="form-label">其他费用</view>
				<input class="form-input" v-model="formData.other_expenses" type="digit" placeholder="请输入其他费用"
					:disabled="isDisabled" :placeholder-class="isDisabled ? '' : 'input-placeholder'" />
			</view>

			<!-- 高速费是否报销 -->
			<view class="form-item" v-if="showTollReim">
				<view class="form-label">高速费报销</view>
				<picker mode="selector" :range="reimbursementOptions" range-key="text"
					:value="getReimbursementIndex(formData.is_reim_toll)" @change="handleReimTollChange" :disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled }">
						<text>{{ getReimbursementText(formData.is_reim_toll) }}</text>
						<uni-icons type="down" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 停车费是否报销 -->
			<view class="form-item" v-if="showParkingFeeReim">
				<view class="form-label">停车费报销</view>
				<picker mode="selector" :range="reimbursementOptions" range-key="text"
					:value="getReimbursementIndex(formData.is_reim_parking_fee)" @change="handleReimParkingFeeChange"
					:disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled }">
						<text>{{ getReimbursementText(formData.is_reim_parking_fee) }}</text>
						<uni-icons type="down" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 加油费是否报销 -->
			<view class="form-item" v-if="showFuelCostsReim">
				<view class="form-label">电/油费报销</view>
				<picker mode="selector" :range="reimbursementOptions" range-key="text"
					:value="getReimbursementIndex(formData.is_reim_fuel_costs)" @change="handleReimFuelCostsChange"
					:disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled }">
						<text>{{ getReimbursementText(formData.is_reim_fuel_costs) }}</text>
						<uni-icons type="down" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 维修费是否报销 -->
			<view class="form-item" v-if="showMaintenanceCostReim">
				<view class="form-label">维修费报销</view>
				<picker mode="selector" :range="reimbursementOptions" range-key="text"
					:value="getReimbursementIndex(formData.is_reim_maintenance_cost)" @change="handleReimMaintenanceCostChange"
					:disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled }">
						<text>{{ getReimbursementText(formData.is_reim_maintenance_cost) }}</text>
						<uni-icons type="down" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 其他费是否报销 -->
			<view class="form-item" v-if="showOtherExpensesReim">
				<view class="form-label">其他费报销</view>
				<picker mode="selector" :range="reimbursementOptions" range-key="text"
					:value="getReimbursementIndex(formData.is_reim_other_expenses)" @change="handleReimOtherExpensesChange"
					:disabled="isDisabled">
					<view class="picker-view" :class="{ disabled: isDisabled }">
						<text>{{ getReimbursementText(formData.is_reim_other_expenses) }}</text>
						<uni-icons type="down" size="14" color="#999" v-if="!isDisabled"></uni-icons>
					</view>
				</picker>
			</view>

			<!-- 备注 -->
			<view class="form-item">
				<view class="form-label">备注</view>
				<input class="form-input" v-model="formData.note" placeholder="请输入备注" :disabled="isDisabled"
					:placeholder-class="isDisabled ? '' : 'input-placeholder'" :maxlength="200" />
			</view>

			<!-- 订单图片 -->
			<view class="form-item-full">
				<view class="form-label-block">订单图片</view>
				<image-upload v-model="formData.order_img" :disabled="isDisabled" :maxCount="9" />
			</view>
		</view>

		<!-- 底部按钮区域 -->
		<view class="button-section">
			<view class="delete-btn" v-if="mode === 'edit' && !isDisabled" @tap="handleDelete">
				删除
			</view>
			<view class="submit-btn" :class="{ disabled: isDisabled || isLoading }" @tap="handleSubmit"
				v-if="orderStatus !== '已完成'">
				{{ isLoading ? '提交中...' : (mode === 'edit' ? '保存' : '提交') }}
			</view>
		</view>
	</view>
</template>

<script>
import { getFormOptions, getOrderDetail, saveOrder, deleteOrder } from '@/api/index.js'
import ImageUpload from '@/components/imageUpload.vue'
import SearchPicker from '@/components/searchPicker.vue'

export default {
	components: {
		ImageUpload,
		SearchPicker
	},
	data() {
		return {
			mode: 'add', // add 或 edit
			orderId: null,
			orderStatus: '',
			isLoading: false,
			isDisabled: false,
			commissionAmount: '0.00',

			// 报销选项
			reimbursementOptions: [
				{ value: 1, text: '是' },
				{ value: 0, text: '否' }
			],

			// 下拉选项
			options: {
				customers: [],
				vehicles: [],
				projectTypes: [],
				routes: []
			},

			// 过滤后的路线列表
			filteredRoutes: [],

			// picker 索引
			customerIndex: -1,
			startVehicleIndex: -1,
			projectTypeIndex: -1,
			routeIndex: -1,

			// 表单数据
			formData: {
				customer_id: '',
				start_vehicle_id: '',
				vehicle_id: '',
				project_type_id: '',
				route_id: '',
				dispatch_order_number: '',
				time: '',
				note: '',
				toll: '',
				parking_fee: '',
				fuel_amount: '',
				fuel_costs: '',
				maintenance_cost: '',
				other_expenses: '',
				is_reim_toll: 0,
				is_reim_parking_fee: 0,
				is_reim_fuel_costs: 0,
				is_reim_maintenance_cost: 0,
				is_reim_other_expenses: 0,
				order_img: ''
			}
		}
	},
	computed: {
		statusCardClass() {
			const classMap = {
				'处理中': 'status-card-processing',
				'已完成': 'status-card-completed'
			}
			return classMap[this.orderStatus] || ''
		},
		// 按车型分组的车辆列表（去重，优先保留当前选中的车辆）
		vehicleListGroupType() {
			const vehicles = this.options.vehicles || []
			const currentVehicleId = this.formData.vehicle_id

			// 找到当前选中车辆的车型
			const currentVehicle = vehicles.find(v => v.id == currentVehicleId)
			const currentVehicleType = currentVehicle?.vehicle_type

			const result = []
			const seenTypes = new Set()

			// 优先添加当前选中的车辆（如果存在）
			if (currentVehicle && currentVehicleType) {
				result.push(currentVehicle)
				seenTypes.add(currentVehicleType)
			}

			// 添加其他车型的第一个车辆
			for (const vehicle of vehicles) {
				if (!seenTypes.has(vehicle.vehicle_type)) {
					result.push(vehicle)
					seenTypes.add(vehicle.vehicle_type)
				}
			}

			return result
		},
		// 判断是否显示高速费报销
		showTollReim() {
			return this.formData.toll && parseFloat(this.formData.toll) > 0
		},
		// 判断是否显示停车费报销
		showParkingFeeReim() {
			return this.formData.parking_fee && parseFloat(this.formData.parking_fee) > 0
		},
		// 判断是否显示电/油费报销
		showFuelCostsReim() {
			return this.formData.fuel_costs && parseFloat(this.formData.fuel_costs) > 0
		},
		// 判断是否显示维修费报销
		showMaintenanceCostReim() {
			return this.formData.maintenance_cost && parseFloat(this.formData.maintenance_cost) > 0
		},
		// 判断是否显示其他费报销
		showOtherExpensesReim() {
			return this.formData.other_expenses && parseFloat(this.formData.other_expenses) > 0
		}
	},
	watch: {
		// 监听高速费变化，清空时自动设置报销为否
		'formData.toll'(newVal) {
			if (!newVal || parseFloat(newVal) <= 0) {
				this.formData.is_reim_toll = 0
			}
		},
		// 监听停车费变化
		'formData.parking_fee'(newVal) {
			if (!newVal || parseFloat(newVal) <= 0) {
				this.formData.is_reim_parking_fee = 0
			}
		},
		// 监听电/油费用变化
		'formData.fuel_costs'(newVal) {
			if (!newVal || parseFloat(newVal) <= 0) {
				this.formData.is_reim_fuel_costs = 0
			}
		},
		// 监听维修费用变化
		'formData.maintenance_cost'(newVal) {
			if (!newVal || parseFloat(newVal) <= 0) {
				this.formData.is_reim_maintenance_cost = 0
			}
		},
		// 监听其他费用变化
		'formData.other_expenses'(newVal) {
			if (!newVal || parseFloat(newVal) <= 0) {
				this.formData.is_reim_other_expenses = 0
			}
		}
	},
	onLoad(options) {
		// 判断模式
		if (options.id) {
			this.mode = 'edit'
			this.orderId = options.id
		} else {
			this.mode = 'add'
			// 设置默认时间为今天
			const today = new Date()
			const year = today.getFullYear()
			const month = String(today.getMonth() + 1).padStart(2, '0')
			const day = String(today.getDate()).padStart(2, '0')
			this.formData.time = `${year}-${month}-${day}`
		}

		// 加载数据（先加载选项，再加载详情）
		this.loadFormOptions().then(() => {
			if (this.mode === 'edit') {
				this.loadOrderDetail(this.orderId)
			} else {
				// 添加模式：从缓存读取记忆
				this.loadSavedSelections()
			}
		})
	},

	methods: {
		// 从缓存加载保存的选择（添加模式记忆功能）
		loadSavedSelections() {
			try {
				const saved = uni.getStorageSync('order_form_memory')
				if (saved) {
					// 恢复保存的ID
					if (saved.customer_id) this.formData.customer_id = saved.customer_id
					if (saved.vehicle_id) this.formData.vehicle_id = saved.vehicle_id
					if (saved.project_type_id) this.formData.project_type_id = saved.project_type_id
					if (saved.route_id) this.formData.route_id = saved.route_id

					// 设置picker索引
					this.setPickerIndexes()
				}
			} catch (error) {
				console.error('读取缓存失败:', error)
			}
		},

		// 保存当前选择到缓存（添加模式提交时调用）
		saveSelectionsToCache() {
			try {
				const memory = {
					customer_id: this.formData.customer_id,
					vehicle_id: this.formData.vehicle_id,
					project_type_id: this.formData.project_type_id,
					route_id: this.formData.route_id,
				}
				uni.setStorageSync('order_form_memory', memory)
			} catch (error) {
				console.error('保存缓存失败:', error)
			}
		},

		// 加载下拉选项
		async loadFormOptions() {
			try {
				const res = await getFormOptions()
				// 处理车辆显示名称，非正常状态添加标识
				this.options = {
					...res.data,
					vehicles: res.data.vehicles.map(v => ({
						...v,
						display_name: v.vehicle_status === '正常'
							? `${v.license_plate}`
							: `${v.license_plate} (${v.vehicle_status})`
					}))
				}
				// 初始化过滤后的路线列表为所有路线
				this.filteredRoutes = this.options.routes || []
			} catch (error) {
				console.error('加载选项失败:', error)
				uni.showToast({
					title: '加载选项失败',
					icon: 'none'
				})
			}
		},

		// 加载订单详情
		async loadOrderDetail(id) {
			try {
				const res = await getOrderDetail(id)
				if (res.data && res.data.length > 0) {
					const order = res.data[0]
					this.orderStatus = order.status
					// 设置提成金额
					this.commissionAmount = (parseFloat(order.driver_commission) || 0).toFixed(2)

					// 填充表单数据
					this.formData.customer_id = order.customer_id
					this.formData.start_vehicle_id = order.start_vehicle_id
					this.formData.vehicle_id = order.vehicle_id
					this.formData.project_type_id = order.project_type_id
					this.formData.route_id = order.route_id
					this.formData.dispatch_order_number = order.dispatch_order_number || ''
					this.formData.time = order.time || ''
					this.formData.note = order.note || ''
					this.formData.toll = order.toll || ''
					this.formData.parking_fee = order.parking_fee || '0'
					this.formData.fuel_amount = order.fuel_amount || '0'
					this.formData.fuel_costs = order.fuel_costs || '0'
					this.formData.maintenance_cost = order.maintenance_cost || '0'
					this.formData.other_expenses = order.other_expenses || '0'
					this.formData.is_reim_toll = order.is_reim_toll || 0
					this.formData.is_reim_parking_fee = order.is_reim_parking_fee || 0
					this.formData.is_reim_fuel_costs = order.is_reim_fuel_costs || 0
					this.formData.is_reim_maintenance_cost = order.is_reim_maintenance_cost || 0
					this.formData.is_reim_other_expenses = order.is_reim_other_expenses || 0
					this.formData.order_img = order.order_img || ''

					// 设置 picker 索引
					this.setPickerIndexes()

					// 检查是否禁用
					this.checkDisabled()
				}
			} catch (error) {
				console.error('加载订单详情失败:', error)
				uni.showToast({
					title: error.msg || '加载失败',
					icon: 'none'
				})
			}
		},

		// 设置 picker 索引
		setPickerIndexes() {
			this.customerIndex = this.options.customers.findIndex(item => item.id == this.formData.customer_id)
			this.startVehicleIndex = this.options.vehicles.findIndex(item => item.id == this.formData.start_vehicle_id)
			this.projectTypeIndex = this.options.projectTypes.findIndex(item => item.id == this.formData.project_type_id)
			this.routeIndex = this.filteredRoutes.findIndex(item => item.id == this.formData.route_id)
			// vehicle_id 直接绑定，不需要设置 index
		},

		// 检查是否禁用
		checkDisabled() {
			this.isDisabled = this.orderStatus !== '处理中'
		},

		// 获取状态图标
		getStatusIcon(status) {
			const iconMap = {
				'处理中': 'loop',
				'已完成': 'checkmarkempty'
			}
			return iconMap[status] || 'info'
		},

		// 获取状态描述
		getStatusDesc(status) {
			const descMap = {
				'处理中': '订单正在处理中',
				'已完成': '订单已完成'
			}
			return descMap[status] || ''
		},

		// picker 变化事件
		handleCustomerChange(e) {
			this.customerIndex = e.detail.value
			const selectedCustomer = this.options.customers[e.detail.value]
			this.formData.customer_id = selectedCustomer.id

			// 过滤路线：只显示与当前客户ID匹配的路线
			this.filteredRoutes = this.options.routes.filter(route => route.customer_id == this.formData.customer_id)

			// 清除路线选择
			this.formData.route_id = ''
			this.routeIndex = -1
		},

		handleVehicleTypeChange(item) {
			// searchPicker 已经设置了 formData.vehicle_id = item.id
			// 这里只需要检查车辆状态
			if (item.vehicle_status !== '正常') {
				uni.showToast({
					title: `该车辆状态为"${item.vehicle_status}"，无法使用`,
					icon: 'none',
					duration: 2500
				})
				// 清除选择
				this.formData.vehicle_id = ''
			}
		},
		handleStartVehicleChange(e) {
			this.startVehicleIndex = e.detail.value
			const selectedVehicle = this.options.vehicles[e.detail.value]
			this.formData.start_vehicle_id = selectedVehicle.id

			// 检查车辆状态
			if (selectedVehicle.vehicle_status !== '正常') {
				uni.showToast({
					title: `该车辆状态为"${selectedVehicle.vehicle_status}"，无法使用`,
					icon: 'none',
					duration: 2500
				})
				// 清除选择
				this.formData.start_vehicle_id = ''
				this.startVehicleIndex = -1
			}
		},

		handleProjectTypeChange(e) {
			this.projectTypeIndex = e.detail.value
			this.formData.project_type_id = this.options.projectTypes[e.detail.value].id
		},

		handleRouteChange(item) {
			this.formData.route_id = item.id
			// 设置 picker 索引
			this.routeIndex = this.filteredRoutes.findIndex(route => route.id == item.id)
		},

		handleTimeChange(e) {
			this.formData.time = e.detail.value
		},

		handleDispatchOrderNumberBlur() {
	
		},

		// 报销选择器变化事件
		handleReimTollChange(e) {
			this.formData.is_reim_toll = this.reimbursementOptions[e.detail.value].value
		},

		handleReimParkingFeeChange(e) {
			this.formData.is_reim_parking_fee = this.reimbursementOptions[e.detail.value].value
		},
		handleReimFuelCostsChange(e) {
			this.formData.is_reim_fuel_costs = this.reimbursementOptions[e.detail.value].value
		},

		handleReimMaintenanceCostChange(e) {
			this.formData.is_reim_maintenance_cost = this.reimbursementOptions[e.detail.value].value
		},

		handleReimOtherExpensesChange(e) {
			this.formData.is_reim_other_expenses = this.reimbursementOptions[e.detail.value].value
		},

		// 获取报销文本
		getReimbursementText(value) {
			const option = this.reimbursementOptions.find(item => item.value === value)
			return option ? option.text : '否'
		},

		// 获取报销选项索引
		getReimbursementIndex(value) {
			return this.reimbursementOptions.findIndex(item => item.value === value)
		},

		// 表单验证
		validateForm() {
			if (!this.formData.customer_id) {
				uni.showToast({ title: '请选择客户', icon: 'none' })
				return false
			}
			if (!this.formData.vehicle_id) {
				uni.showToast({ title: '请选择车辆', icon: 'none' })
				return false
			}
			// 检查车辆状态
			const selectedVehicle = this.options.vehicles.find(v => v.id == this.formData.vehicle_id)
			if (selectedVehicle && selectedVehicle.vehicle_status !== '正常') {
				uni.showToast({ title: `该车辆状态为"${selectedVehicle.vehicle_status}"，无法使用`, icon: 'none' })
				return false
			}
			if (!this.formData.start_vehicle_id) {
				uni.showToast({ title: '请选择出发车辆', icon: 'none' })
				return false
			}
			// 检查出发车辆状态
			const selectedStartVehicle = this.options.vehicles.find(v => v.id == this.formData.start_vehicle_id)
			if (selectedStartVehicle && selectedStartVehicle.vehicle_status !== '正常') {
				uni.showToast({ title: `该出发车辆状态为"${selectedStartVehicle.vehicle_status}"，无法使用`, icon: 'none' })
				return false
			}
			if (!this.formData.project_type_id) {
				uni.showToast({ title: '请选择项目', icon: 'none' })
				return false
			}

			if (!this.formData.time) {
				uni.showToast({ title: '请选择时间', icon: 'none' })
				return false
			}
			return true
		},

		// 提交表单
		async handleSubmit() {
			if (this.isDisabled) return
			if (this.isLoading) return
			if (!this.validateForm()) return
			await new Promise(resolve => setTimeout(resolve, 1))
			this.isLoading = true

			try {
				// 辅助函数：将空值转为0，保留有效数值
				const toNumber = (val) => {
					if (val === '' || val === null || val === undefined) return 0
					const num = parseFloat(val)
					return isNaN(num) ? 0 : num
				}

				// 处理空值，设为0或默认值
				const data = {
					...this.formData,
					dispatch_order_number: this.formData.dispatch_order_number ? this.formData.dispatch_order_number : '空',
					toll: toNumber(this.formData.toll),
					parking_fee: toNumber(this.formData.parking_fee),
					fuel_amount: toNumber(this.formData.fuel_amount),
					fuel_costs: toNumber(this.formData.fuel_costs),
					maintenance_cost: toNumber(this.formData.maintenance_cost),
					other_expenses: toNumber(this.formData.other_expenses),
					is_reim_toll: this.formData.is_reim_toll ? 1 : 0,
					is_reim_parking_fee: this.formData.is_reim_parking_fee ? 1 : 0,
					is_reim_fuel_costs: this.formData.is_reim_fuel_costs ? 1 : 0,
					is_reim_maintenance_cost: this.formData.is_reim_maintenance_cost ? 1 : 0,
					is_reim_other_expenses: this.formData.is_reim_other_expenses ? 1 : 0,
					order_img: this.formData.order_img || '',
					id: this.mode === 'edit' ? this.orderId : undefined
				}

				const res = await saveOrder(data)

				// 添加模式：保存当前选择到缓存
				if (this.mode === 'add') {
					this.saveSelectionsToCache()
				}

				uni.showToast({
					title: res.msg || '操作成功',
					icon: 'success'
				})

				setTimeout(() => {
					uni.navigateBack()
				}, 1500)

			} catch (error) {
				console.error('提交失败:', error)
				uni.showToast({
					title: error.msg || '操作失败',
					icon: 'none'
				})
			} finally {
				this.isLoading = false
			}
		},

		// 删除订单
		handleDelete() {
			uni.showModal({
				title: '提示',
				content: '确定要删除此订单吗？',
				success: async (res) => {
					if (res.confirm) {
						try {
							await deleteOrder(this.orderId)
							uni.showToast({
								title: '删除成功',
								icon: 'success'
							})
							setTimeout(() => {
								uni.navigateBack()
							}, 1500)
						} catch (error) {
							console.error('删除失败:', error)
							uni.showToast({
								title: error.msg || '删除失败',
								icon: 'none'
							})
						}
					}
				}
			})
		}
	}
}
</script>

<style lang="scss" scoped>
.detail-page {
	min-height: 100vh;
	background: #f5f7fa;
	padding-bottom: 140rpx;
}

.form-section {
	padding: 20rpx 30rpx;
}

.form-item {
	background: #ffffff;
	border-radius: 16rpx;
	padding: 28rpx 32rpx;
	margin-bottom: 20rpx;
	display: flex;
	align-items: center;
	justify-content: space-between;
}

.form-label {
	font-size: 30rpx;
	color: #333;
	font-weight: 500;
	min-width: 140rpx;
	display: flex;
	align-items: center;

	.required {
		color: #ff4d4f;
		margin-right: 4rpx;
	}
}

// 订单图片上传（占据整行）
.form-item-full {
	background: #ffffff;
	border-radius: 16rpx;
	padding: 28rpx 32rpx;
	margin-bottom: 20rpx;
}

.form-label-block {
	font-size: 30rpx;
	color: #333;
	font-weight: 500;
	margin-bottom: 20rpx;
}

.form-input {
	flex: 1;
	text-align: right;
	font-size: 30rpx;
	color: #333;

	&:disabled {
		color: #999;
	}
}

.picker-view {
	flex: 1;
	text-align: right;
	font-size: 30rpx;
	color: #333;
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8rpx;

	&.disabled {
		color: #999;
	}

	.placeholder {
		color: #999;
	}
}

.input-placeholder {
	color: #999;
}

.status-card {
	margin: 20rpx 30rpx;
	padding: 32rpx;
	border-radius: 20rpx;
	display: flex;
	align-items: center;
	gap: 24rpx;
	box-shadow: 0 8rpx 24rpx rgba(0, 0, 0, 0.12);

	&.status-card-processing {
		background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
	}

	&.status-card-completed {
		background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
	}
}

.status-info {
	flex: 1;
	display: flex;
	flex-direction: column;
	gap: 8rpx;
}

.status-text {
	font-size: 40rpx;
	font-weight: 700;
	color: #ffffff;
}

.status-desc {
	font-size: 26rpx;
	color: rgba(255, 255, 255, 0.9);
}

// 提成卡片
.commission-card {
	margin: 0 30rpx 20rpx;
	padding: 32rpx;
	background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
	border-radius: 20rpx;
	display: flex;
	align-items: center;
	gap: 24rpx;
	box-shadow: 0 4rpx 16rpx rgba(67, 160, 71, 0.15);
	border: 2rpx solid #43a047;
}

.commission-icon {
	display: flex;
	align-items: center;
	justify-content: center;
}

.commission-info {
	flex: 1;
	display: flex;
	flex-direction: column;
	gap: 4rpx;
}

.commission-label {
	font-size: 26rpx;
	color: #2e7d32;
	font-weight: 500;
}

.commission-value {
	font-size: 44rpx;
	font-weight: 700;
	color: #43a047;
}

.button-section {
	position: fixed;
	bottom: 0;
	left: 0;
	right: 0;
	background: #ffffff;
	padding: 20rpx 30rpx;
	padding-bottom: calc(20rpx + env(safe-area-inset-bottom));
	box-shadow: 0 -4rpx 20rpx rgba(0, 0, 0, 0.08);
	display: flex;
	gap: 20rpx;
	z-index: 999;
}

.delete-btn {
	flex: 1;
	height: 88rpx;
	background: #ffffff;
	border: 2rpx solid #ff4d4f;
	color: #ff4d4f;
	font-size: 32rpx;
	font-weight: 500;
	border-radius: 16rpx;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s ease;

	&:active {
		background: #fff1f0;
	}
}

.submit-btn {
	flex: 2;
	height: 88rpx;
	background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
	color: #ffffff;
	font-size: 32rpx;
	font-weight: 500;
	border-radius: 16rpx;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s ease;

	&:active {
		opacity: 0.8;
	}

	&.disabled {
		opacity: 0.5;
		background: #999;
	}
}
</style>
