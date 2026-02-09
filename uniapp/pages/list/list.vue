<template>
	<view class="order-list-page">
		<!-- 年月选择器 -->
		<view class="filter-section">
			<view class="filter-card">
				<picker
					mode="date"
					fields="month"
					:value="selectedMonth"
					@change="handleMonthChange"
					class="month-picker"
				>
					<view class="picker-display">
						<uni-icons type="calendar" size="20" color="#1976d2" class="picker-icon"></uni-icons>
						<text class="picker-text">{{ displayMonth }}</text>
						<uni-icons type="down" size="14" color="#1976d2" class="picker-arrow"></uni-icons>
					</view>
				</picker>
			</view>
		</view>

		<!-- 订单列表区域 -->
		<view class="orders-section">
			<view class="section-title">订单列表</view>

			<!-- 订单卡片列表 -->
			<view class="order-list" v-if="orderList.length > 0">
				<view class="order-card" v-for="(item, index) in orderList" :key="index" @tap="handleOrderClick(item)">
					<view class="order-header">
						<view class="route-tag"><uni-icons type="cart" size="16" color="#1976d2"></uni-icons> {{ item.route_route_name || '未选择' }}</view>
						<view class="order-status" :class="item.status === '处理中' ? 'status-pending' : 'status-completed'">
							{{ item.status }}
						</view>
					</view>

					<view class="route-info">
						<view class="location">
							<uni-icons type="location" size="18" color="#1976d2" class="location-icon"></uni-icons>
							<text class="location-text">{{ item.route_start_point || '未选择' }}</text>
						</view>
						<view class="route-arrow"><uni-icons type="down" size="20" color="#1976d2"></uni-icons></view>
						<view class="location">
							<uni-icons type="location-filled" size="18" color="#1976d2" class="location-icon"></uni-icons>
							<text class="location-text">{{ item.route_end_point || '未选择' }}</text>
						</view>
					</view>

					<view class="order-details">
						<view class="detail-item">
							<uni-icons type="calendar" size="16" color="#666666" class="detail-icon"></uni-icons>
							<text class="detail-text">{{ item.time }}</text>
						</view>
						<view class="detail-item">
							<uni-icons type="shop" size="16" color="#666666" class="detail-icon"></uni-icons>
							<text class="detail-text">{{ item.customer_name }}</text>
						</view>
						<view class="detail-item">
							<uni-icons type="person" size="16" color="#666666" class="detail-icon"></uni-icons>
							<text class="detail-text">{{ item.customer_contact_person }}</text>
						</view>
						<view class="detail-item">
							<uni-icons type="phone" size="16" color="#666666" class="detail-icon"></uni-icons>
							<text class="detail-text phone-number">{{ item.customer_contact_phone }}</text>
						</view>
					</view>
				</view>
			</view>

			<!-- 空状态提示 -->
			<view class="empty-state" v-else>
				<uni-icons type="inbox" size="80" color="#cccccc"></uni-icons>
				<text class="empty-text">暂无订单</text>
			</view>
		</view>
	</view>
</template>

<script>
import { getOrderList } from '@/api/index.js'

export default {
	data() {
		return {
			selectedMonth: '',
			orderList: []
		}
	},
	computed: {
		displayMonth() {
			if (!this.selectedMonth) {
				return '选择年月'
			}
			const [year, month] = this.selectedMonth.split('-')
			return `${year}年${month}月`
		}
	},
	onLoad() {
		this.initCurrentMonth()
	},

	onShow() {
		this.loadOrderList()
	},
	methods: {
		initCurrentMonth() {
			const now = new Date()
			const year = now.getFullYear()
			const month = String(now.getMonth() + 1).padStart(2, '0')
			this.selectedMonth = `${year}-${month}`
		},

		handleMonthChange(e) {
			this.selectedMonth = e.detail.value
			this.loadOrderList()
		},

		async loadOrderList() {
			if (!this.selectedMonth) return

			try {
				const [year, month] = this.selectedMonth.split('-')
				// 获取当月第一天和最后一天
				const startTime = `${year}-${month}-01`
				const lastDay = new Date(year, month, 0).getDate()
				const endTime = `${year}-${month}-${String(lastDay).padStart(2, '0')}`

				const res = await getOrderList({
					start_time: startTime,
					end_time: endTime
				})
				this.orderList = res.data || []
			} catch (error) {
				console.error('加载订单列表失败:', error)
				uni.showToast({
					title: '加载失败',
					icon: 'none'
				})
			}
		},

		// 点击订单卡片
		handleOrderClick(item) {
			uni.navigateTo({
				url: `/pages/detail/detail?id=${item.id}`
			})
		}
	}
}
</script>

<style lang="scss" scoped>
.order-list-page {
	min-height: 100vh;
	background: linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%);
	padding-bottom: 0;
}

// 筛选区域
.filter-section {
	padding: 40rpx 30rpx 20rpx;
}

.filter-card {
	background: #ffffff;
	border-radius: 20rpx;
	box-shadow: 0 4rpx 20rpx rgba(0, 0, 0, 0.06);
	overflow: hidden;
}

.month-picker {
	width: 100%;
}

.picker-display {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 32rpx;
	transition: background 0.3s ease;

	&:active {
		background: #f8f9fa;
	}
}

.picker-icon {
	margin-right: 12rpx;
}

.picker-text {
	flex: 1;
	font-size: 32rpx;
	font-weight: 600;
	color: #1976d2;
	text-align: left;
}

.picker-arrow {
	margin-left: 12rpx;
}

// 订单列表区域
.orders-section {
	padding: 20rpx 30rpx 40rpx;
}

.section-title {
	font-size: 36rpx;
	font-weight: 600;
	color: #1a1a1a;
	margin-bottom: 24rpx;
	padding-left: 8rpx;
}

.order-list {
	display: flex;
	flex-direction: column;
	gap: 24rpx;
}

// 订单卡片
.order-card {
	background: #ffffff;
	border-radius: 20rpx;
	padding: 32rpx;
	box-shadow: 0 4rpx 20rpx rgba(0, 0, 0, 0.06);
	transition: all 0.3s ease;

	&:active {
		transform: translateY(-2rpx);
		box-shadow: 0 8rpx 28rpx rgba(0, 0, 0, 0.1);
	}
}

.order-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 24rpx;
}

.route-tag {
	font-size: 28rpx;
	font-weight: 600;
	color: #1976d2;
	background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
	padding: 12rpx 24rpx;
	border-radius: 12rpx;
}

.order-status {
	font-size: 24rpx;
	font-weight: 600;
	padding: 8rpx 20rpx;
	border-radius: 20rpx;

	&.status-pending {
		color: #fb8c00;
		background: #fff3e0;
	}

	&.status-completed {
		color: #43a047;
		background: #e8f5e9;
	}
}

// 路线信息
.route-info {
	display: flex;
	align-items: center;
	justify-content: space-between;
	margin-bottom: 24rpx;
	padding: 20rpx;
	background: #f8fbff;
	border-radius: 16rpx;
}

.location {
	flex: 1;
	display: flex;
	align-items: center;
	gap: 8rpx;
}

.location-icon {
	flex-shrink: 0;
}

.location-text {
	font-size: 28rpx;
	font-weight: 500;
	color: #333333;
	word-break: break-all;
}

.route-arrow {
	margin: 0 16rpx;
	flex-shrink: 0;
}

// 订单详情
.order-details {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20rpx 24rpx;
	padding-top: 24rpx;
	border-top: 2rpx solid #f0f0f0;
}

.detail-item {
	display: flex;
	align-items: center;
	gap: 8rpx;
}

.detail-icon {
	flex-shrink: 0;
}

.detail-text {
	font-size: 26rpx;
	color: #666666;
	line-height: 1.4;

	&.phone-number {
		color: #1976d2;
		font-weight: 500;
	}
}

// 空状态
.empty-state {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	padding: 120rpx 60rpx;
}

.empty-text {
	font-size: 28rpx;
	color: #999999;
	margin-top: 24rpx;
}
</style>
