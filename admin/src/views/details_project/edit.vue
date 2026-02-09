<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="800px" @confirm="handleSubmit"
            @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="客户" prop="customer_id">
                            <el-select v-model="formData.customer_id" clearable placeholder="请选择客户"
                                style="width: 100%;">
                                <el-option v-for="item in customerOptions" :key="item.id" :label="item.customer_name"
                                    :value="item.id" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="司机" prop="driver_id">
                            <el-select v-model="formData.driver_id" clearable placeholder="请选择司机" style="width: 100%;">
                                <el-option v-for="item in driverOptions" :key="item.id" :label="item.name"
                                    :value="item.id" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="出发车辆" prop="start_vehicle_id">
                            <el-select v-model="formData.start_vehicle_id" clearable placeholder="请选择出发车辆">
                                <el-option v-for="item in vehicleOptions" :key="item.id" :label="item.license_plate"
                                    :value="item.id" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="订单车型" prop="vehicle_id">
                            <el-select v-model="formData.vehicle_id" clearable placeholder="请选择订单车辆"
                                style="width: 100%;">
                                <el-option v-for="item in getvehicletListGroupType()" :key="item.id"
                                    :label="item.vehicle_type" :value="item.id" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="项目" prop="project_type_id">
                            <el-select v-model="formData.project_type_id" clearable placeholder="请选择项目"
                                style="width: 100%;">
                                <el-option v-for="item in projectOptions" :key="item.id" :label="item.type_name"
                                    :value="item.id" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="调度单号" prop="dispatch_order_number">
                            <el-input v-model="formData.dispatch_order_number" clearable placeholder="请输入调度单号" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="路线" prop="route_id">
                            <el-select v-model="formData.route_id" clearable placeholder="请输入路线" style="width: 100%;">
                                <el-option v-for="item in routeOptions" :key="item.id" :label="item.route_name"
                                    :value="item.id" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="高速费" prop="toll">
                            <el-input type="number" v-model="formData.toll" clearable placeholder="请输入高速费" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="是否报销" prop="is_reim_toll">
                            <el-select v-model="formData.is_reim_toll" clearable placeholder="请选择" style="width: 100%;">
                                <el-option label="是" :value="true" />
                                <el-option label="否" :value="false" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="停车费" prop="parking_fee">
                            <el-input type="number" v-model="formData.parking_fee" clearable placeholder="请输入停车费" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="是否报销" prop="is_reim_parking_fee">
                            <el-select v-model="formData.is_reim_parking_fee" clearable placeholder="请选择"
                                style="width: 100%;">
                                <el-option label="是" :value="true" />
                                <el-option label="否" :value="false" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="加油量" prop="fuel_amount">
                            <el-input type="number" v-model="formData.fuel_amount" clearable placeholder="请输入加油量(L)" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="加油费用" prop="fuel_costs">
                            <el-input type="number" v-model="formData.fuel_costs" clearable placeholder="请输入加油费用" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="是否报销" prop="is_reim_fuel_costs">
                            <el-select v-model="formData.is_reim_fuel_costs" clearable placeholder="请选择"
                                style="width: 100%;">
                                <el-option label="是" :value="true" />
                                <el-option label="否" :value="false" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="维修费用" prop="maintenance_cost">
                            <el-input type="number" v-model="formData.maintenance_cost" clearable
                                placeholder="请输入维修费用" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="是否报销" prop="is_reim_maintenance_cost">
                            <el-select v-model="formData.is_reim_maintenance_cost" clearable placeholder="请选择"
                                style="width: 100%;">
                                <el-option label="是" :value="true" />
                                <el-option label="否" :value="false" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="其他费用" prop="other_expenses">
                            <el-input type="number" v-model="formData.other_expenses" clearable placeholder="请输入其他费用" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="是否报销" prop="is_reim_other_expenses">
                            <el-select v-model="formData.is_reim_other_expenses" clearable placeholder="请选择"
                                style="width: 100%;">
                                <el-option label="是" :value="true" />
                                <el-option label="否" :value="false" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="时间" prop="time">
                            <el-date-picker v-model="formData.time" type="date" placeholder="请选择时间" format="YYYY-MM-DD"
                                value-format="YYYY-MM-DD" style="width: 100%;" clearable />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="状态" prop="status">
                            <el-select v-model="formData.status" clearable placeholder="请选择状态" style="width: 100%;">
                                <el-option label="处理中" value="处理中" />
                                <el-option label="已完成" value="已完成" />
                                <el-option label="已取消" value="已取消" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>

                <hr style="margin-bottom: 20px;" />

                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="自定义单价" prop="c_amount">
                            <el-input type="number" v-model="formData.c_amount" clearable
                                placeholder="请输入单价(空既按照公式计算)" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="自定义提成" prop="c_commission">
                            <el-input type="number" v-model="formData.c_commission" clearable
                                placeholder="请输入提成(空既按照公式计算)" />
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-row :gutter="24">
                    <el-col :span="24">
                        <el-form-item label="备注" prop="note">
                            <el-input v-model="formData.note" clearable placeholder="请输入备注" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="24" v-if="formData.order_img">
                    <el-col :span="24">
                        <el-form-item label="订单照片" prop="order_img">
                            <ImagePreview :value="formData.order_img" :baseUrl="config.baseUrl" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <!-- <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="状态" prop="status">
                            <el-input v-model="formData.status" clearable placeholder="请输入状态" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="订单照片" prop="order_img">
                            <el-input v-model="formData.order_img" clearable placeholder="请输入订单照片" />
                        </el-form-item>
                    </el-col>
                </el-row> -->
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="detailsProjectEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import ImagePreview from '@/components/imagePreview/index.vue'
import { apiDetailsProjectAdd, apiDetailsProjectEdit, apiDetailsProjectDetail, apiDetailsProjectLists } from '@/api/details_project'
import { apiCustomerLists } from '@/api/customer'
import { apiDriverLists } from '@/api/driver'
import { apiVehicleLists } from '@/api/vehicle'
import { apiProjectTypeLists } from '@/api/project_type'
import { apiTransportCompanyLists } from '@/api/transport_company'
import { apiRouteLists } from '@/api/route'
import type { PropType } from 'vue'
import config from '@/config/index'
import { get } from 'lodash'
import { el } from 'element-plus/es/locales.mjs'
defineProps({
    dictData: {
        type: Object as PropType<Record<string, any[]>>,
        default: () => ({})
    }
})
const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')

// 获取客户列表选项
const customerOptions = ref<any[]>([])
const getCustomerOptions = async () => {
    try {
        const data = await apiCustomerLists({ page_no: 1, page_size: 999 })
        customerOptions.value = data.lists || []
        console.log('customerOptions的值为', customerOptions.value)
    } catch (error) {
        console.error('获取客户列表失败:', error)
    }
}

const driverOptions = ref<any[]>([])
const getDriverOptions = async () => {
    try {
        const data = await apiDriverLists({ page_no: 1, page_size: 999 })
        driverOptions.value = data.lists || []
        console.log('driverOptions的值为', driverOptions.value)
    } catch (error) {
        console.error('获取司机列表失败:', error)
    }
}

const vehicleOptions = ref<any[]>([])
const getVehicleOptions = async () => {
    try {
        const data = await apiVehicleLists({ page_no: 1, page_size: 999 })
        vehicleOptions.value = data.lists || []
        console.log('vehicleOptions的值为', vehicleOptions.value)
    } catch (error) {
        console.error('获取订单车辆列表失败:', error)
    }
}



const projectOptions = ref<any[]>([])
const getProjectOptions = async () => {
    try {
        const data = await apiProjectTypeLists({ page_no: 1, page_size: 999 })
        projectOptions.value = data.lists || []
        console.log('projectOptions的值为', projectOptions.value)
    } catch (error) {
        console.error('获取项目列表失败:', error)
    }
}

const transport_company_options = ref<any[]>([])
const gettransport_company_options = async () => {
    try {
        const data = await apiTransportCompanyLists({ page_no: 1, page_size: 999 })
        transport_company_options.value = data.lists || []
        console.log('transport_company_options的值为', transport_company_options.value)
    } catch (error) {
        console.error('获取运输公司列表失败:', error)
    }
}

const routeOptions = ref<any[]>([])
const getRouteOptions = async () => {
    // 假设有一个获取路线列表的API
    try {
        const data = await apiRouteLists({ page_no: 1, page_size: 999 })
        routeOptions.value = data.lists || []
        console.log('routeOptions的值为', routeOptions.value)
    } catch (error) {
        console.error('获取路线列表失败:', error)
    }
}


// 弹窗标题
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑明细表' : '新增明细表'
})

// 表单数据
const formData = reactive({
    id: '',
    customer_id: '',
    driver_id: '',
    vehicle_id: '',
    start_vehicle_id: '',
    project_type_id: '',
    route_id: '',
    dispatch_order_number: '',
    time: new Date().toISOString().split('T')[0],
    note: '',
    status: '处理中',
    order_img: '',
    toll: '',
    parking_fee: '0',
    fuel_amount: '0', // 加油量
    fuel_costs: '0', // 加油费用
    maintenance_cost: '0', // 维修费用
    other_expenses: '0', // 其他费用
    is_reim_toll: false, // 高速费是否报销
    is_reim_parking_fee: false, // 停车费是否报销
    is_reim_fuel_costs: false, // 加油费用是否报销
    is_reim_maintenance_cost: false, // 维修费用是否报销
    is_reim_other_expenses: false, // 其他费用是否报销
    c_commission: '',
    c_amount: ''
})


// 表单验证
const formRules = reactive<any>({
    customer_id: [{
        required: true,
        message: '请输入客户',
        trigger: ['blur']
    }],
    driver_id: [{
        required: true,
        message: '请输入司机',
        trigger: ['blur']
    }],
    vehicle_id: [{
        required: true,
        message: '请输入订单车辆',
        trigger: ['blur']
    }],
    start_vehicle_id: [{
        required: true,
        message: '请输入出发车辆',
        trigger: ['blur']
    }],
    project_type_id: [{
        required: true,
        message: '请输入项目',
        trigger: ['blur']
    }],
    route_id: [{
        required: true,
        message: '请输入路线',
        trigger: ['blur']
    }],
    dispatch_order_number: [{
        required: true,
        message: '请输入调度单号',
        trigger: ['blur']
    }],
    time: [{
        required: true,
        message: '请输入时间',
        trigger: ['blur']
    }],
    status: [{
        required: true,
        message: '请输入状态',
        trigger: ['blur']
    }],
    order_img: [{
        required: true,
        message: '请输入订单照片',
        trigger: ['blur']
    }],
    toll: [{
        required: true,
        message: '请输入高速费',
        trigger: ['blur']
    }],
    parking_fee: [{
        required: true,
        message: '请输入停车费',
        trigger: ['blur']
    }],
    fuel_amount: [{
        required: true,
        message: '请输入加油量',
        trigger: ['blur']
    }],
    fuel_costs: [{
        required: true,
        message: '请输入加油费用',
        trigger: ['blur']
    }],
    maintenance_cost: [{
        required: true,
        message: '请输入维修费用',
        trigger: ['blur']
    }],
    other_expenses: [{
        required: true,
        message: '请输入其他费用',
        trigger: ['blur']
    }],
    is_reim_toll: [{
        required: true,
        message: '请选择是否报销',
        trigger: ['change']
    }],
    is_reim_parking_fee: [{
        required: true,
        message: '请选择是否报销',
        trigger: ['change']
    }],
    is_reim_fuel_costs: [{
        required: false,
        message: '请选择是否报销',
        trigger: ['change']
    }],
    is_reim_maintenance_cost: [{
        required: true,
        message: '请选择是否报销',
        trigger: ['change']
    }],
    is_reim_other_expenses: [{
        required: true,
        message: '请选择是否报销',
        trigger: ['change']
    }],
})


// 获取详情
const setFormData = async (data: Record<any, any>) => {
    console.log('setFormData接收到的数据:', data)
    for (const key in formData) {
        if (data[key] != null && data[key] != undefined) {
            //@ts-ignore
            formData[key] = data[key]
        }
    }
    // 特别处理报销字段，确保布尔值正确设置
    const reimburseFields = ['is_reim_toll', 'is_reim_parking_fee', 'is_reim_fuel_costs', 'is_reim_maintenance_cost', 'is_reim_other_expenses']
    reimburseFields.forEach(field => {
        if (data[field] !== undefined && data[field] !== null) {
            // 处理各种可能的返回值：数字1、字符串"1"、布尔true等
            const value = data[field]
            if (value === 1 || value === '1' || value === true || value === 'true') {
                //@ts-ignore
                formData[field] = true
            } else if (value === 0 || value === '0' || value === false || value === 'false') {
                //@ts-ignore
                formData[field] = false
            } else {
                // 其他情况使用Boolean转换
                //@ts-ignore
                formData[field] = Boolean(value)
            }
            console.log(`字段 ${field}: 原始值=${value}, 设置值=${formData[field]}`)
        }
    })
    if (!formData['route_id']) {
        formData['route_id'] = '';
    }
    if (!Number(formData['c_commission'])) {
        formData['c_commission'] = '';
    }
    if (!Number(formData['c_amount'])) {
        formData['c_amount'] = '';
    }
    console.log('setFormData设置后的formData:', formData)
}

const getDetail = async (row: Record<string, any>) => {
    const data = await apiDetailsProjectDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiDetailsProjectEdit(data)
        : await apiDetailsProjectAdd(data)
    popupRef.value?.close()
    emit('success')
}

//打开弹窗
const open = (type = 'add') => {
    mode.value = type
    getCustomerOptions()
    getDriverOptions()
    getVehicleOptions()
    getProjectOptions()
    gettransport_company_options()
    getRouteOptions()
    popupRef.value?.open()
}

// 关闭回调
const handleClose = () => {
    emit('close')
}

const getvehicletListGroupType = () => {
    const res: any[] = [];

    const currentItem = vehicleOptions.value.find(v => v.id === formData.vehicle_id);
    if (currentItem) {
        res.push(currentItem);
    }

    for (const item of vehicleOptions.value) {
        if (res.find(v => v.vehicle_type === item.vehicle_type)) {
            continue;
        }
        res.push(item);
    }
    return res;
}
defineExpose({
    open,
    setFormData,
    getDetail
})
</script>
