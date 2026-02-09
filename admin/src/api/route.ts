import request from '@/utils/request'

// 路线表列表
export function apiRouteLists(params: any) {
    return request.get({ url: '/route/lists', params })
}

// 添加路线表
export function apiRouteAdd(params: any) {
    return request.post({ url: '/route/add', params })
}

// 编辑路线表
export function apiRouteEdit(params: any) {
    return request.post({ url: '/route/edit', params })
}

// 删除路线表
export function apiRouteDelete(params: any) {
    return request.post({ url: '/route/delete', params })
}

// 路线表详情
export function apiRouteDetail(params: any) {
    return request.get({ url: '/route/detail', params })
}