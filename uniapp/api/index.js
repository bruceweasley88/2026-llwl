// 接口基础配置
export const BASE_URL = 'https://test.gzsczh.com/'

// 获取 token
const getToken = () => {
	return uni.getStorageSync('token') || ''
}

// 封装请求函数
const request = (url, method = 'GET', data = {}) => {
	return new Promise((resolve, reject) => {
		uni.request({
			url: BASE_URL + url,
			method,
			data,
			header: {
				'token': getToken()
			},
			success: (res) => {
				// 未登录统一处理
				if (res.data.code === 0 && res.data.show === 1 && res.data.msg.indexOf('登录已过期') > -1) {
					uni.removeStorageSync('token')
					uni.removeStorageSync('userInfo')
					uni.removeStorageSync('driver_id')
					uni.showToast({
						title: '请先登录',
						icon: 'none',
						duration: 2000
					})
					setTimeout(() => {
						uni.reLaunch({
							url: '/pages/login/login'
						})
					}, 2000)
					reject(res.data)
					return
				}

				// 正常响应
				if (res.data.code === 1) {
					resolve(res.data)
				} else {
					reject(res.data)
				}
			},
			fail: (err) => {
				reject(err)
			}
		})
	})
}

// 登录接口
export const login = (name, password) => {
	return request('/index/driver/login', 'GET', { name, password })
}

// 获取用户信息
export const getInfo = () => {
	return request('/index/driver/getInfo', 'GET')
}

// 获取订单列表
export const getOrderList = (params) => {
	return request('/index/driver/getDetailsList', 'GET', params)
}

// 获取订单统计
export const getOrderCount = () => {
	return request('/index/driver/getOrderCount', 'GET')
}

// 获取表单下拉选项
export const getFormOptions = () => {
	return request('/index/driver/getFormOptions', 'GET')
}

// 获取订单详情
export const getOrderDetail = (id) => {
	return request('/index/driver/getDetailsList', 'GET', { id })
}

// 保存订单
export const saveOrder = (data) => {
	return request('/index/driver/saveOrder', 'POST', data)
}

// 删除订单
export const deleteOrder = (id) => {
	return request('/index/driver/deleteOrder', 'POST', { id })
}
