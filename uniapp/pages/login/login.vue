<template>
	<view class="login-container">
		<!-- 背景图片 -->
		<image class="login-bg" src="/static/loginbg.png" mode="aspectFill"></image>
		<view class="login-card">
			<!-- 标题 -->
			<view class="login-title">司机登录</view>

			<!-- 表单区域 -->
			<view class="form-container">
				<!-- 账号输入框 -->
				<view class="input-wrapper">
					<view class="input-icon">
						<uni-icons type="person" size="22" color="#0056b3"></uni-icons>
					</view>
					<input
						class="input-field"
						type="text"
						v-model="formData.account"
						placeholder="请输入账号"
						placeholder-class="input-placeholder"
						:maxlength="50"
					/>
				</view>

				<!-- 密码输入框 -->
				<view class="input-wrapper">
					<view class="input-icon">
						<uni-icons type="locked" size="22" color="#0056b3"></uni-icons>
					</view>
					<input
						class="input-field"
						type="password"
						v-model="formData.password"
						placeholder="请输入密码"
						placeholder-class="input-placeholder"
						:maxlength="20"
					/>
				</view>

				<!-- 登录按钮 -->
				<button
					class="login-button"
					:class="{ 'button-loading': isLoading }"
					@tap="handleLogin"
					:loading="isLoading"
					:disabled="isLoading"
				>
					{{ isLoading ? '登录中...' : '登录' }}
				</button>
			</view>
		</view>
	</view>
</template>

<script>
import { login } from '@/api/index.js'

export default {
	data() {
		return {
			formData: {
				account: '',
				password: ''
			},
			isLoading: false
		}
	},
	methods: {
		// 表单验证
		validateForm() {
			if (!this.formData.account.trim()) {
				uni.showToast({
					title: '请输入账号',
					icon: 'none',
					duration: 2000
				})
				return false
			}

			if (!this.formData.password.trim()) {
				uni.showToast({
					title: '请输入密码',
					icon: 'none',
					duration: 2000
				})
				return false
			}

			return true
		},

		// 登录处理
		async handleLogin() {
			if (!this.validateForm()) return
			if (this.isLoading) return

			this.isLoading = true

			try {
				const res = await login(this.formData.account, this.formData.password)

				// 保存 token 和用户信息
				uni.setStorageSync('token', res.data.token)
				uni.setStorageSync('userInfo', res.data.driver)
				uni.setStorageSync('driver_id', res.data.driver_id)

				uni.showToast({
					title: '登录成功',
					icon: 'success',
					duration: 1500
				})

				setTimeout(() => {
					uni.switchTab({
						url: '/pages/index/index'
					})
				}, 1500)

			} catch (error) {
				uni.showToast({
					title: error.msg || '登录失败，请重试',
					icon: 'none',
					duration: 2000
				})
			} finally {
				this.isLoading = false
			}
		}
	},

	onLoad(options) {
		console.log('登录页面加载', options)
	}
}
</script>

<style lang="scss" scoped>
	.login-container {
		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 100rpx 60rpx;
		box-sizing: border-box;
		position: relative;
		overflow: hidden;
	}

	.login-bg {
		position: absolute;
		top: -50rpx;
		left: -50rpx;
		right: -50rpx;
		bottom: -50rpx;
		width: calc(100% + 100rpx);
		height: calc(100% + 100rpx);
		filter: blur(30rpx);
		z-index: 0;
	}

	.login-card {
		width: 100%;
		background: rgba(255, 255, 255, 0.85);
		backdrop-filter: blur(20rpx);
		-webkit-backdrop-filter: blur(20rpx);
		border-radius: 24rpx;
		padding: 80rpx 60rpx;
		box-shadow: 0 20rpx 60rpx rgba(0, 0, 0, 0.15);
		position: relative;
		z-index: 1;

		// 微信小程序不支持 backdrop-filter，使用更高透明度降级
		// #ifdef MP-WEIXIN
		background: rgba(255, 255, 255, 0.92);
		// #endif
	}

	.login-title {
		font-size: 56rpx;
		font-weight: 600;
		color: #1a1a1a;
		text-align: center;
		margin-bottom: 80rpx;
		letter-spacing: 4rpx;
	}

	.form-container {
		width: 100%;
	}

	.input-wrapper {
		position: relative;
		display: flex;
		align-items: center;
		background: #f8f9fa;
		border: 2rpx solid #e0e0e0;
		border-radius: 16rpx;
		margin-bottom: 32rpx;
		transition: all 0.3s ease;

		&:focus-within {
			background: #ffffff;
			border-color: #0056b3;
			box-shadow: 0 0 0 4rpx rgba(0, 86, 179, 0.1);
		}
	}

	.input-icon {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 80rpx;
		height: 100rpx;
		color: #0056b3;
	}

	.input-field {
		flex: 1;
		height: 100rpx;
		font-size: 32rpx;
		color: #1a1a1a;
		background: transparent;
		padding: 0 20rpx;
		border: none;
		outline: none;
		appearance: none;
	}

	.input-placeholder {
		color: #999999;
		font-size: 28rpx;
	}

	.login-button {
		width: 100%;
		height: 100rpx;
		background: linear-gradient(135deg, #0056b3 0%, #004494 100%);
		color: #ffffff;
		font-size: 34rpx;
		font-weight: 600;
		border-radius: 16rpx;
		border: none;
		margin-top: 40rpx;
		transition: all 0.3s ease;
		box-shadow: 0 8rpx 24rpx rgba(0, 86, 179, 0.3);

		&:active {
			transform: translateY(2rpx);
			box-shadow: 0 4rpx 12rpx rgba(0, 86, 179, 0.3);
		}

		&.button-loading {
			opacity: 0.8;
		}
	}
</style>