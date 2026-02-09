import request from '@/utils/request'

// 司机表列表
export function apiDriverLists(params: any) {
    return request.get({ url: '/driver/lists', params })
}

// 添加司机表
export function apiDriverAdd(params: any) {
    return request.post({ url: '/driver/add', params })
}

// 编辑司机表
export function apiDriverEdit(params: any) {
    return request.post({ url: '/driver/edit', params })
}

// 删除司机表
export function apiDriverDelete(params: any) {
    return request.post({ url: '/driver/delete', params })
}

// 司机表详情
export function apiDriverDetail(params: any) {
    return request.get({ url: '/driver/detail', params })
}