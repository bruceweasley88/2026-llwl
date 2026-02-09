import request from '@/utils/request'

// 明细表列表
export function apiDetailsProjectLists(params: any) {
    return request.get({ url: '/details_project/lists', params })
}

// 添加明细表
export function apiDetailsProjectAdd(params: any) {
    return request.post({ url: '/details_project/add', params })
}

// 编辑明细表
export function apiDetailsProjectEdit(params: any) {
    return request.post({ url: '/details_project/edit', params })
}

// 删除明细表
export function apiDetailsProjectDelete(params: any) {
    return request.post({ url: '/details_project/delete', params })
}

// 明细表详情
export function apiDetailsProjectDetail(params: any) {
    return request.get({ url: '/details_project/detail', params })
}