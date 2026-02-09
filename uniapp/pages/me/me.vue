<template>
	<view class="me-page">
		<!-- 个人信息卡片 -->
		<view class="info-card">
			<view class="card-header">
				<text class="header-title">个人信息</text>
			</view>

			<view class="info-list">
				<view class="info-item">
					<view class="info-label">
						<uni-icons type="person" size="18" color="#666666" class="label-icon"></uni-icons>
						<text class="label-text">姓名</text>
					</view>
					<view class="info-value">{{ userInfo.name || '暂无' }}</view>
				</view>

				<view class="info-item">
					<view class="info-label">
						<uni-icons type="person-filled" size="18" color="#666666" class="label-icon"></uni-icons>
						<text class="label-text">性别</text>
					</view>
					<view class="info-value">{{ userInfo.sex || '暂无' }}</view>
				</view>

				<view class="info-item">
					<view class="info-label">
						<uni-icons type="phone" size="18" color="#666666" class="label-icon"></uni-icons>
						<text class="label-text">联系电话</text>
					</view>
					<view class="info-value phone-number">{{ userInfo.phone || '暂无' }}</view>
				</view>
			</view>
		</view>

		<!-- 退出登录按钮 -->
		<view class="logout-section">
			<button class="logout-button" @tap="handleLogout">
				<text>退出登录</text>
			</button>
		</view>
	</view>
</template>

<script>
import { getInfo } from '@/api/index.js'

export default {
	data() {
		return {
			userInfo: {
				name: '',
				sex: '',
				phone: ''
			}
		}
	},
	onLoad() {
		this.loadUserInfo()
	},
	methods: {
		async loadUserInfo() {
			try {
				const res = await getInfo()
				this.userInfo = {
					name: res.data.name,
					sex: res.data.sex,
					phone: res.data.phone || ''
				}
			} catch (error) {
				console.error('加载用户信息失败:', error)
			}
		},

		handleLogout() {
			uni.showModal({
				title: '提示',
				content: '确定要退出登录吗？',
				success: (res) => {
					if (res.confirm) {
						// 清除存储
						uni.removeStorageSync('token')
						uni.removeStorageSync('userInfo')
						uni.removeStorageSync('driver_id')

						// 跳转到登录页
						uni.reLaunch({
							url: '/pages/login/login'
						})
					}
				}
			})
		}
	}
}
</script>

<style lang="scss" scoped>
	.me-page {
		background: linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%);
		padding: 40rpx 30rpx 0;
	}

	// 个人信息卡片
	.info-card {
		background: #ffffff;
		border-radius: 20rpx;
		box-shadow: 0 4rpx 20rpx rgba(0, 0, 0, 0.06);
		overflow: hidden;
		margin-bottom: 40rpx;
	}

	.card-header {
		padding: 32rpx;
		border-bottom: 2rpx solid #f0f0f0;
	}

	.header-title {
		font-size: 36rpx;
		font-weight: 600;
		color: #1a1a1a;
	}

	.info-list {
		padding: 0 32rpx;
	}

	.info-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 32rpx 0;
		border-bottom: 2rpx solid #f5f5f5;

		&:last-child {
			border-bottom: none;
		}
	}

	.info-label {
		display: flex;
		align-items: center;
		gap: 16rpx;
	}

	.label-icon {
	}

	.label-text {
		font-size: 30rpx;
		color: #666666;
		font-weight: 500;
	}

	.info-value {
		font-size: 30rpx;
		color: #333333;
		font-weight: 500;

		&.phone-number {
			color: #1976d2;
		}
	}

	// 退出登录区域
	.logout-section {
		margin-top: 40rpx;
		padding-bottom: 40rpx;
	}

	.logout-button {
		width: 100%;
		height: 96rpx;
		background: #ffffff;
		border: 3rpx solid #f44336;
		border-radius: 16rpx;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 12rpx;
		font-size: 32rpx;
		color: #f44336;
		font-weight: 600;
		box-shadow: 0 4rpx 16rpx rgba(244, 67, 54, 0.1);
		transition: all 0.3s ease;

		&:active {
			background: #fff5f5;
			transform: translateY(2rpx);
			box-shadow: 0 2rpx 8rpx rgba(244, 67, 54, 0.15);
		}

		&::after {
			border: none;
		}
	}

	.logout-icon {
		font-size: 32rpx;
	}
</style>