<template>
	<view class="driver-home">
		<!-- 顶部统计卡片区域 -->
		<view class="stats-container">
			<!-- 待处理订单卡片 -->
			<view class="stat-card pending">
				<uni-icons type="list" size="30" color="#ffffff" class="stat-icon"></uni-icons>
				<view class="stat-info">
					<view class="stat-value">{{ stats.pending }}</view>
					<view class="stat-label">待处理</view>
				</view>
			</view>

			<!-- 已完成订单卡片 -->
			<view class="stat-card completed">
				<uni-icons type="checkmarkempty" size="30" color="#ffffff" class="stat-icon"></uni-icons>
				<view class="stat-info">
					<view class="stat-value">{{ stats.completed }}</view>
					<view class="stat-label">已完成</view>
				</view>
			</view>
		</view>

		<!-- 订单列表区域 -->
		<view class="orders-section">
			<view class="section-header">
				<view class="section-title">我的订单</view>
				<view class="add-btn" @tap="handleAddOrder">
					<uni-icons type="plus" size="18" color="#1976d2"></uni-icons>
					<text class="add-text">添加订单</text>
				</view>
			</view>

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
import { getOrderCount, getOrderList } from '@/api/index.js'

export default {
	data() {
		return {
			stats: {
				pending: 0,
				completed: 0
			},
			orderList: []
		}
	},
	onLoad() {
		// 检查登录状态
		const token = uni.getStorageSync('token')
		if (!token) {
			uni.reLaunch({
				url: '/pages/login/login'
			})
			return
		}
	},

	onShow() {
		this.loadData()
	},
	methods: {
		async loadData() {
			try {
				// 获取统计数据
				const countRes = await getOrderCount()
				this.stats.pending = countRes.data['处理中'] || 0
			this.stats.completed = countRes.data['已完成'] || 0

				// 获取最近一个月的订单列表
				const now = new Date()
				const year = now.getFullYear()
				const month = String(now.getMonth() + 1).padStart(2, '0')
				const startTime = `${year - 1}-${month}-01`
				const endTime = `${year}-${month}-31`

				const listRes = await getOrderList({
					start_time: startTime,
					end_time: endTime
				})
				this.orderList = listRes.data || []
			} catch (error) {
				console.error('加载数据失败:', error)
			}
		},

		// 点击订单卡片
		handleOrderClick(item) {
			uni.navigateTo({
				url: `/pages/detail/detail?id=${item.id}`
			})
		},

		// 添加订单
		handleAddOrder() {
			uni.navigateTo({
				url: '/pages/detail/detail'
			})
		}
	}
}
</script>

<style lang="scss" scoped>
	.driver-home {
		background: linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%);
		padding-bottom: 40rpx;
	}

	// 统计卡片区域
	.stats-container {
		display: flex;
		justify-content: space-between;
		padding: 40rpx 30rpx;
		gap: 20rpx;
	}

	.stat-card {
		flex: 1;
		display: flex;
		align-items: center;
		padding: 40rpx 30rpx;
		border-radius: 20rpx;
		box-shadow: 0 8rpx 24rpx rgba(0, 0, 0, 0.08);

		&.pending {
			background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
		}

		&.completed {
			background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
		}
	}

	.stat-icon {
		margin-right: 20rpx;
		opacity: 0.9;
	}

	.stat-info {
		display: flex;
		flex-direction: column;
	}

	.stat-value {
		font-size: 56rpx;
		font-weight: 700;
		color: #ffffff;
		line-height: 1;
		margin-bottom: 8rpx;
	}

	.stat-label {
		font-size: 26rpx;
		color: rgba(255, 255, 255, 0.95);
		font-weight: 500;
	}

	// 订单列表区域
	.orders-section {
		padding: 0 30rpx;
	}

	.section-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 24rpx;
	}

	.section-title {
		font-size: 36rpx;
		font-weight: 600;
		color: #1a1a1a;
		padding-left: 8rpx;
	}

	.add-btn {
		display: flex;
		align-items: center;
		gap: 6rpx;
		padding: 12rpx 24rpx;
		background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
		border-radius: 32rpx;
		transition: all 0.3s ease;

		&:active {
			opacity: 0.7;
		}

		.add-text {
			font-size: 26rpx;
			color: #1976d2;
			font-weight: 500;
		}
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
