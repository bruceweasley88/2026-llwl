<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="550px" @confirm="handleSubmit"
            @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-form-item label="客户" prop="customer_id">
                    <el-select v-model="formData.customer_id" clearable placeholder="请选择客户" style="width: 100%;">
                        <el-option v-for="item in customerOptions" :key="item.id" :label="item.customer_name"
                            :value="item.id" />
                    </el-select>
                </el-form-item>
                <el-form-item label="路线名称" prop="route_name">
                    <el-input v-model="formData.route_name" clearable placeholder="请输入路线名称" />
                </el-form-item>
                <el-form-item label="起点" prop="start_point">
                    <el-input v-model="formData.start_point" clearable placeholder="请输入起点" />
                </el-form-item>
                <el-form-item label="终点" prop="end_point">
                    <el-input v-model="formData.end_point" clearable placeholder="请输入终点" />
                </el-form-item>
                <el-form-item label="里程(km)" prop="distance_km">
                    <el-input type="number" v-model="formData.distance_km" clearable placeholder="请输入里程距离" />
                </el-form-item>
                <el-form-item label="基准油价" prop="fuel_price">
                    <el-input type="number" v-model="formData.fuel_price" clearable placeholder="请输入基准油价" />
                </el-form-item>
                <el-form-item label="门店数" prop="store_count">
                    <el-input v-model="formData.store_count" clearable placeholder="请输入门店数" />
                </el-form-item>
                <el-form-item label="单价公式" prop="amount_formula_id">
                    <el-select v-model="formData.amount_formula_id" clearable placeholder="请选择金额公式"
                        style="width: 100%; margin-top: 10px;">
                        <el-option v-for="item in amountFormulaOptions" :key="item.id" :label="item.name"
                            :value="item.id" />
                    </el-select>
                </el-form-item>
                <el-form-item label="提成公式" prop="commission_formula_id">
                    <el-select v-model="formData.commission_formula_id" clearable placeholder="请选择司机提成公式"
                        style="width: 100%; margin-top: 10px;">
                        <el-option v-for="item in commissionFormulaOptions" :key="item.id" :label="item.name"
                            :value="item.id" />
                    </el-select>
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="routeEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiRouteAdd, apiRouteEdit, apiRouteDetail } from '@/api/route'
import { apiCustomerLists } from '@/api/customer'
import type { PropType } from 'vue'
import { apiCommissionFormulaLists } from '@/api/commission_formula'
import { apiAmountFormulaLists } from '@/api/amount_formula'
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

const commissionFormulaOptions = ref<any[]>([])
const getCommissionFormulaOptions = async () => {
    try {
        const data = await apiCommissionFormulaLists({ page_no: 1, page_size: 999 })
        commissionFormulaOptions.value = data.lists || []
        console.log('commissionFormulaOptions的值为', commissionFormulaOptions.value)
    } catch (error) {
        console.error('获取提成公式列表失败:', error)
    }
}

const amountFormulaOptions = ref<any[]>([])
const getAmountFormulaOptions = async () => {
    try {
        const data = await apiAmountFormulaLists({ page_no: 1, page_size: 999 })
        amountFormulaOptions.value = data.lists || []
        console.log('amountFormulaOptions的值为', amountFormulaOptions.value)
    } catch (error) {
        console.error('获取金额公式列表失败:', error)
    }
}

onMounted(() => {
    getCommissionFormulaOptions()
    getAmountFormulaOptions()
});

// 弹窗标题
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑路线表' : '新增路线表'
})

// 表单数据
const formData = reactive({
    id: '',
    customer_id: '',
    route_name: '',
    start_point: '',
    end_point: '',
    distance_km: '',
    commission_formula_id: '',
    amount_formula_id: '',
    store_count: '',
    fuel_price: ''
})


// 表单验证
const formRules = reactive<any>({
    customer_id: [{
        required: true,
        message: '请选择客户',
        trigger: ['blur']
    }],
    route_name: [{
        required: true,
        message: '请输入路线名称',
        trigger: ['blur']
    }],
    start_point: [{
        required: true,
        message: '请输入起点',
        trigger: ['blur']
    }],
    end_point: [{
        required: true,
        message: '请输入终点',
        trigger: ['blur']
    }],
    distance_km: [{
        required: true,
        message: '请输入里程距离',
        trigger: ['blur']
    }],
    commission_formula_id: [{
        required: true,
        message: '请输入司机提成公式',
        trigger: ['blur']
    }],
    amount_formula_id: [{
        required: true,
        message: '请输入金额公式',
        trigger: ['blur']
    }],
    store_count: [{
        required: true,
        message: '请输入门店数',
        trigger: ['blur']
    }],
    fuel_price: [{
        required: true,
        message: '请输入基准油价',
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
    const data = await apiRouteDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiRouteEdit(data)
        : await apiRouteAdd(data)
    popupRef.value?.close()
    emit('success')
}

//打开弹窗
const open = (type = 'add') => {
    mode.value = type
    getCustomerOptions()
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
