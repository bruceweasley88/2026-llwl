<template>
	<view class="search-picker">
		<view class="picker-trigger" @tap="handleOpenPicker" :class="{ disabled: disabled }">
			<text v-if="selectedOption" class="selected-text">{{ displayValue }}</text>
			<text v-else class="placeholder">{{ placeholder }}</text>
			<uni-icons type="down" size="14" color="#999" v-if="!disabled"></uni-icons>
		</view>

		<!-- 弹出层 -->
		<view class="picker-popup" v-if="showPicker" @tap="handleClosePicker">
			<view class="picker-content" @tap.stop>
				<!-- 搜索框 -->
				<view class="search-box">
					<uni-icons type="search" size="18" color="#999"></uni-icons>
					<input
						class="search-input"
						v-model="searchKeyword"
						placeholder="搜索..."
						:focus="showPicker"
					/>
					<uni-icons
						v-if="searchKeyword"
						type="clear"
						size="16"
						color="#999"
						@tap="searchKeyword = ''"
					></uni-icons>
				</view>

				<!-- 选项列表 -->
				<scroll-view class="option-list" scroll-y>
					<view
						class="option-item"
						v-for="(item, index) in filteredOptions"
						:key="index"
						@tap="handleSelect(item)"
					>
						<text class="option-text">{{ item[labelKey] }}</text>
						<uni-icons
							v-if="selectedValue === item[valueKey]"
							type="checkmarkempty"
							size="18"
							color="#1976d2"
						></uni-icons>
					</view>
					<view v-if="filteredOptions.length === 0" class="empty-tip">
						<text>无匹配结果</text>
					</view>
				</scroll-view>

				<!-- 取消按钮 -->
				<view class="cancel-btn" @tap="handleClosePicker">
					<text>取消</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
export default {
	name: 'SearchPicker',
	props: {
		// v-model 绑定的值
		value: {
			type: [String, Number],
			default: ''
		},
		// 选项数组
		options: {
			type: Array,
			default: () => []
		},
		// 显示的字段名
		labelKey: {
			type: String,
			default: 'text'
		},
		// 值的字段名
		valueKey: {
			type: String,
			default: 'value'
		},
		// 占位文字
		placeholder: {
			type: String,
			default: '请选择'
		},
		// 是否禁用
		disabled: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return {
			showPicker: false,
			searchKeyword: ''
		}
	},
	computed: {
		// 过滤后的选项
		filteredOptions() {
			if (!this.searchKeyword) {
				return this.options
			}
			const keyword = this.searchKeyword.toLowerCase()
			return this.options.filter(item => {
				const text = String(item[this.labelKey]).toLowerCase()
				return text.includes(keyword)
			})
		},
		// 当前选中的选项
		selectedOption() {
			return this.options.find(item => item[this.valueKey] === this.selectedValue)
		},
		// 显示的值
		displayValue() {
			return this.selectedOption ? this.selectedOption[this.labelKey] : ''
		},
		// 选中的值
		selectedValue() {
			return this.value
		}
	},
	methods: {
		handleOpenPicker() {
			if (this.disabled) return
			this.showPicker = true
			this.searchKeyword = ''
		},
		handleClosePicker() {
			this.showPicker = false
			this.searchKeyword = ''
		},
		handleSelect(item) {
			this.$emit('input', item[this.valueKey])
			this.$emit('change', item)
			this.handleClosePicker()
		}
	}
}
</script>

<style lang="scss" scoped>
.search-picker {
	flex: 1;
	display: flex;
}

.picker-trigger {
	flex: 1;
	display: flex;
	align-items: center;
	justify-content: flex-end;
	gap: 8rpx;

	&.disabled {
		opacity: 0.6;
	}
}

.selected-text {
	font-size: 30rpx;
	color: #333;
}

.placeholder {
	color: #999;
	font-size: 30rpx;
}

.picker-popup {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: rgba(0, 0, 0, 0.5);
	z-index: 1000;
	display: flex;
	align-items: flex-end;
}

.picker-content {
	width: 100%;
	max-height: 70vh;
	background: #ffffff;
	border-radius: 24rpx 24rpx 0 0;
	display: flex;
	flex-direction: column;
	overflow: hidden;
}

.search-box {
	display: flex;
	align-items: center;
	gap: 16rpx;
	padding: 24rpx 32rpx;
	border-bottom: 1rpx solid #f0f0f0;
	background: #ffffff;

	.search-input {
		flex: 1;
		font-size: 28rpx;
		color: #333;
	}
}

.option-list {
	flex: 1;
	max-height: 50vh;
}

.option-item {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 28rpx 32rpx;
	border-bottom: 1rpx solid #f5f5f5;
	transition: background 0.2s ease;

	&:active {
		background: #f5f7fa;
	}

	&:last-child {
		border-bottom: none;
	}
}

.option-text {
	font-size: 30rpx;
	color: #333;
}

.empty-tip {
	padding: 80rpx 32rpx;
	text-align: center;

	text {
		font-size: 28rpx;
		color: #999;
	}
}

.cancel-btn {
	padding: 24rpx;
	border-top: 1rpx solid #f0f0f0;
	text-align: center;

	text {
		font-size: 32rpx;
		color: #1976d2;
		font-weight: 500;
	}
}
</style>
