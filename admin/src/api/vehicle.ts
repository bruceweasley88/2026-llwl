import request from '@/utils/request'

// 车辆表列表
export function apiVehicleLists(params: any) {
    return request.get({ url: '/vehicle/lists', params })
}

// 添加车辆表
export function apiVehicleAdd(params: any) {
    return request.post({ url: '/vehicle/add', params })
}

// 编辑车辆表
export function apiVehicleEdit(params: any) {
    return request.post({ url: '/vehicle/edit', params })
}

// 删除车辆表
export function apiVehicleDelete(params: any) {
    return request.post({ url: '/vehicle/delete', params })
}

// 车辆表详情
export function apiVehicleDetail(params: any) {
    return request.get({ url: '/vehicle/detail', params })
}