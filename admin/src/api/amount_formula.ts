import request from '@/utils/request'

// 单价公式列表
export function apiAmountFormulaLists(params: any) {
    return request.get({ url: '/amount_formula/lists', params })
}

// 添加单价公式
export function apiAmountFormulaAdd(params: any) {
    return request.post({ url: '/amount_formula/add', params })
}

// 编辑单价公式
export function apiAmountFormulaEdit(params: any) {
    return request.post({ url: '/amount_formula/edit', params })
}

// 删除单价公式
export function apiAmountFormulaDelete(params: any) {
    return request.post({ url: '/amount_formula/delete', params })
}

// 单价公式详情
export function apiAmountFormulaDetail(params: any) {
    return request.get({ url: '/amount_formula/detail', params })
}