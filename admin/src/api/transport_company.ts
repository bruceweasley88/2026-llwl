import request from '@/utils/request'

// 运输公司表列表
export function apiTransportCompanyLists(params: any) {
    return request.get({ url: '/transport_company/lists', params })
}

// 添加运输公司表
export function apiTransportCompanyAdd(params: any) {
    return request.post({ url: '/transport_company/add', params })
}

// 编辑运输公司表
export function apiTransportCompanyEdit(params: any) {
    return request.post({ url: '/transport_company/edit', params })
}

// 删除运输公司表
export function apiTransportCompanyDelete(params: any) {
    return request.post({ url: '/transport_company/delete', params })
}

// 运输公司表详情
export function apiTransportCompanyDetail(params: any) {
    return request.get({ url: '/transport_company/detail', params })
}