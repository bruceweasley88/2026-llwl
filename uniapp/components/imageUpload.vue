<template>
	<view class="image-upload-container">
		<view class="image-list">
			<!-- 已上传的图片 -->
			<view class="image-item" v-for="(item, index) in imageList" :key="index">
				<image class="uploaded-image" :src="getImageUrl(item)" mode="aspectFill"></image>
				<!-- 删除按钮 -->
				<view class="delete-btn" v-if="!disabled" @tap="handleDelete(index)">
					<uni-icons type="closeempty" size="16" color="#ffffff"></uni-icons>
				</view>
			</view>

			<!-- 上传按钮 -->
			<view class="upload-btn" v-if="!disabled && imageList.length < maxCount" @tap="handleChooseImage">
				<uni-icons type="plus" size="40" color="#cccccc"></uni-icons>
				<text class="upload-text">上传图片</text>
			</view>
		</view>
	</view>
</template>

<script>
import { BASE_URL } from '@/api/index.js'

export default {
	name: 'ImageUpload',
	props: {
		// v-model 绑定的值（逗号分隔的字符串）
		value: {
			type: String,
			default: ''
		},
		// 是否禁用
		disabled: {
			type: Boolean,
			default: false
		},
		// 最大上传数量
		maxCount: {
			type: Number,
			default: 9
		}
	},
	data() {
		return {
			imageList: []
		}
	},
	watch: {
		value: {
			handler(newVal) {
				this.parseValue(newVal)
			},
			immediate: true
		}
	},
	methods: {
		// 解析 value 为图片数组
		parseValue(val) {
			if (!val) {
				this.imageList = []
			} else {
				this.imageList = val.split(',').filter(item => item.trim() !== '')
			}
		},

		// 获取完整图片URL
		getImageUrl(url) {
			if (!url) return ''
			// 如果已经是完整URL，直接返回
			if (url.startsWith('http://') || url.startsWith('https://')) {
				return url
			}
			// 拼接基础URL
			return BASE_URL + url
		},

		// 选择图片
		handleChooseImage() {
			const remainCount = this.maxCount - this.imageList.length
			uni.chooseImage({
				count: remainCount,
				sizeType: ['original', 'compressed'],
				sourceType: ['album', 'camera'],
				success: (res) => {
					const tempFilePaths = res.tempFilePaths
					this.uploadImages(tempFilePaths)
				}
			})
		},

		// 上传图片
		async uploadImages(filePaths) {
			uni.showLoading({
				title: '上传中...'
			})

			try {
				const uploadPromises = filePaths.map(filePath => this.uploadSingleImage(filePath))
				const results = await Promise.all(uploadPromises)

				// 过滤出上传成功的图片
				const successImages = results.filter(result => result && !result.error)

				if (successImages.length > 0) {
					// 合并已有图片和新上传的图片
					const newImageList = [...this.imageList, ...successImages.map(item => item.url)]
					const newValue = newImageList.join(',')
					this.$emit('input', newValue)
					uni.showToast({
						title: '上传成功',
						icon: 'success'
					})
				}
			} catch (error) {
				console.error('上传失败:', error)
				uni.showToast({
					title: '上传失败',
					icon: 'none'
				})
			} finally {
				uni.hideLoading()
			}
		},

		// 上传单个图片
		uploadSingleImage(filePath) {
			return new Promise((resolve) => {
				// 检查文件大小（2MB限制）
				uni.getFileInfo({
					filePath: filePath,
					success: (info) => {
						const maxSize = 2 * 1024 * 1024 // 2MB
						if (info.size > maxSize) {
							uni.showToast({
								title: '图片大小不能超过2MB',
								icon: 'none'
							})
							resolve({ error: true })
							return
						}

						// 直接上传文件，由后端验证类型
						uni.uploadFile({
							url: BASE_URL + '/index/index/upload',
							filePath: filePath,
							name: 'file',
							header: {
								'token': uni.getStorageSync('token') || ''
							},
							success: (uploadRes) => {
								try {
									const data = JSON.parse(uploadRes.data)
									if (data.code === 1) {
										// 后端返回格式: { code: 1, msg: '上传成功', data: ['/img/filename.jpg'] }
										const imgUrl = Array.isArray(data.data) ? data.data[0] : data.data
										resolve({ url: imgUrl })
									} else {
										uni.showToast({
											title: data.msg || '上传失败',
											icon: 'none'
										})
										resolve({ error: true })
									}
								} catch (e) {
									console.error('解析上传结果失败:', e)
									resolve({ error: true })
								}
							},
							fail: (err) => {
								console.error('上传请求失败:', err)
								resolve({ error: true })
							}
						})
					},
					fail: () => {
						resolve({ error: true })
					}
				})
			})
		},

		// 删除图片
		handleDelete(index) {
			uni.showModal({
				title: '提示',
				content: '确定删除这张图片吗？',
				success: (res) => {
					if (res.confirm) {
						this.imageList.splice(index, 1)
						const newValue = this.imageList.join(',')
						this.$emit('input', newValue)
					}
				}
			})
		}
	}
}
</script>

<style lang="scss" scoped>
.image-upload-container {
	width: 100%;
}

.image-list {
	display: flex;
	flex-wrap: wrap;
	gap: 20rpx;
}

// 图片项（圆角正方形）
.image-item {
	position: relative;
	width: 200rpx;
	height: 200rpx;
	border-radius: 16rpx;
	overflow: hidden;
	box-shadow: 0 4rpx 12rpx rgba(0, 0, 0, 0.1);
}

.uploaded-image {
	width: 100%;
	height: 100%;
	display: block;
}

// 删除按钮（右上角）
.delete-btn {
	position: absolute;
	top: 0;
	right: 0;
	width: 48rpx;
	height: 48rpx;
	background: rgba(0, 0, 0, 0.6);
	border-bottom-left-radius: 16rpx;
	display: flex;
	align-items: center;
	justify-content: center;

	&:active {
		opacity: 0.8;
	}
}

// 上传按钮（圆角正方形）
.upload-btn {
	width: 200rpx;
	height: 200rpx;
	border-radius: 16rpx;
	border: 2rpx dashed #cccccc;
	background: #fafafa;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 12rpx;
	transition: all 0.3s ease;

	&:active {
		background: #f0f0f0;
		border-color: #999999;
	}
}

.upload-text {
	font-size: 24rpx;
	color: #999999;
}
</style>
