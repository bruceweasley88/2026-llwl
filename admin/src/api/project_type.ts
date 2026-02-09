import request from '@/utils/request'

// 项目表列表
export function apiProjectTypeLists(params: any) {
    return request.get({ url: '/project_type/lists', params })
}

// 添加项目表
export function apiProjectTypeAdd(params: any) {
    return request.post({ url: '/project_type/add', params })
}

// 编辑项目表
export function apiProjectTypeEdit(params: any) {
    return request.post({ url: '/project_type/edit', params })
}

// 删除项目表
export function apiProjectTypeDelete(params: any) {
    return request.post({ url: '/project_type/delete', params })
}

// 项目表详情
export function apiProjectTypeDetail(params: any) {
    return request.get({ url: '/project_type/detail', params })
}