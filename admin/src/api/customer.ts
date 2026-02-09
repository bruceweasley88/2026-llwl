import request from '@/utils/request'

// 客户表列表
export function apiCustomerLists(params: any) {
    return request.get({ url: '/customer/lists', params })
}

// 添加客户表
export function apiCustomerAdd(params: any) {
    return request.post({ url: '/customer/add', params })
}

// 编辑客户表
export function apiCustomerEdit(params: any) {
    return request.post({ url: '/customer/edit', params })
}

// 删除客户表
export function apiCustomerDelete(params: any) {
    return request.post({ url: '/customer/delete', params })
}

// 客户表详情
export function apiCustomerDetail(params: any) {
    return request.get({ url: '/customer/detail', params })
}