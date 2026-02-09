<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="550px" @confirm="handleSubmit"
            @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-form-item label="车型" prop="vehicle_type">
                    <el-input v-model="formData.vehicle_type" clearable placeholder="请输入车型" />
                </el-form-item>
                <el-form-item label="车牌号" prop="license_plate">
                    <el-input v-model="formData.license_plate" clearable placeholder="请输入车牌号" />
                </el-form-item>
                <el-form-item label="起步价" prop="start_price">
                    <el-input type="number" v-model="formData.start_price" clearable placeholder="请输入起步价" />
                </el-form-item>
                <el-form-item label="油耗" prop="fuel">
                    <el-input type="number" v-model="formData.fuel" clearable placeholder="请输入油耗" />
                </el-form-item>
                <el-form-item label="公里系数" prop="odometer_coefficient">
                    <el-input type="number" v-model="formData.odometer_coefficient" clearable placeholder="请输入车辆公里系数" />
                </el-form-item>
                <el-form-item label="车辆尺寸" prop="size">
                    <el-input type="number" v-model="formData.size" clearable placeholder="请输入车辆尺寸" />
                </el-form-item>
                <el-form-item label="车辆状态" prop="vehicle_status">
                    <el-select class="flex-1" v-model="formData.vehicle_status" clearable placeholder="请选择车辆状态">
                        <el-option label="正常" value="正常"></el-option>
                        <el-option label="停用" value="停用"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="vehicleEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiVehicleAdd, apiVehicleEdit, apiVehicleDetail } from '@/api/vehicle'
import { timeFormat } from '@/utils/util'
import type { PropType } from 'vue'
import { start } from 'nprogress'
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


// 弹窗标题
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑车辆表' : '新增车辆表'
})

// 表单数据
const formData = reactive({
    id: '',
    vehicle_type: '',
    license_plate: '',
    vehicle_status: '',
    start_price: '',
    fuel: '', // 油耗
    odometer_coefficient: '1',
    size: '', // 车辆尺寸
})


// 表单验证
const formRules = reactive<any>({
    vehicle_type: [{
        required: true,
        message: '请输入车型',
        trigger: ['blur']
    }],
    license_plate: [{
        required: true,
        message: '请输入车牌号',
        trigger: ['blur']
    }],
    vehicle_status: [{
        required: true,
        message: '请选择车辆状态',
        trigger: ['blur']
    }],
    start_price: [{
        required: true,
        message: '请输入起步价',
        trigger: ['blur']
    }],
    fuel: [{
        required: true,
        message: '请输入油耗',
        trigger: ['blur']
    }],
    size: [{
        required: true,
        message: '请输入车辆尺寸',
        trigger: ['blur']
    }],
    odometer_coefficient: [{
        required: true,
        message: '请输入车辆公里系数',
        trigger: ['blur']
    }],
})


// 获取详情
const setFormData = async (data: Record<any, any>) => {
    for (const key in formData) {
        if (data[key] != null && data[key] != undefined) {
            //@ts-ignore
            formData[key] = data[key]
        }
    }


}

const getDetail = async (row: Record<string, any>) => {
    const data = await apiVehicleDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiVehicleEdit(data)
        : await apiVehicleAdd(data)
    popupRef.value?.close()
    emit('success')
}

//打开弹窗
const open = (type = 'add') => {
    mode.value = type
    popupRef.value?.open()
}

// 关闭回调
const handleClose = () => {
    emit('close')
}



defineExpose({
    open,
    setFormData,
    getDetail
})
</script>
