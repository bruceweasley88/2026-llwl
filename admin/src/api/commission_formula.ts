import request from '@/utils/request'

// 提成公式列表
export function apiCommissionFormulaLists(params: any) {
    return request.get({ url: '/commission_formula/lists', params })
}

// 添加提成公式
export function apiCommissionFormulaAdd(params: any) {
    return request.post({ url: '/commission_formula/add', params })
}

// 编辑提成公式
export function apiCommissionFormulaEdit(params: any) {
    return request.post({ url: '/commission_formula/edit', params })
}

// 删除提成公式
export function apiCommissionFormulaDelete(params: any) {
    return request.post({ url: '/commission_formula/delete', params })
}

// 提成公式详情
export function apiCommissionFormulaDetail(params: any) {
    return request.get({ url: '/commission_formula/detail', params })
}