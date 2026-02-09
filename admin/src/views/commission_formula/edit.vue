<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="550px" @confirm="handleSubmit"
            @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-form-item label="公式名称" prop="name">
                    <el-input v-model="formData.name" clearable placeholder="请输入公式名称" />
                </el-form-item>
                <el-form-item label="公式" prop="formula">
                    <el-input v-model="formData.formula" clearable placeholder="请输入公式" />
                    <div class="formula-hint mt-2">
                        <div class="hint-title">可用变量：</div>
                        <div class="hint-list flex flex-wrap gap-2">
                            <el-tag size="small">里程</el-tag>
                            <el-tag size="small">订单车辆类型</el-tag>
                            <el-tag size="small">订单车辆油耗</el-tag>
                            <el-tag size="small">订单车辆尺寸</el-tag>
                            <el-tag size="small">订单车辆起步价</el-tag>
                            <el-tag size="small">订单车辆公里系数</el-tag>

                            <el-tag size="small">出发车辆类型</el-tag>
                            <el-tag size="small">出发车辆油耗</el-tag>
                            <el-tag size="small">出发车辆尺寸</el-tag>
                            <el-tag size="small">出发车辆起步价</el-tag>
                            <el-tag size="small">出发车辆公里系数</el-tag>
                            <el-tag size="small">高速费</el-tag>
                            <el-tag size="small">停车费</el-tag>

                            <el-tag size="small">加油量</el-tag>
                            <el-tag size="small">加油费用</el-tag>
                            <el-tag size="small">维修费用</el-tag>
                            <el-tag size="small">其它费用</el-tag>

                            <el-tag size="small">门店数</el-tag>
                            <el-tag size="small">基准油价</el-tag>
                            <el-tag size="small">项目类型</el-tag>
                            <el-tag size="small">运输公司</el-tag>
                            <el-tag size="small">单价</el-tag>
                        </div>
                        <div class="hint-example mt-2">
                            <span class="text-gray-600">示例：</span>
                            <span class="text-blue-600">门店数 + (单价 * 0.2)</span>
                        </div>
                    </div>
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-select class="flex-1" v-model="formData.status" clearable placeholder="请选择状态">
                        <el-option label="正常" value="正常"></el-option>
                        <el-option label="停用" value="停用"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="commissionFormulaEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiCommissionFormulaAdd, apiCommissionFormulaEdit, apiCommissionFormulaDetail } from '@/api/commission_formula'
import type { PropType } from 'vue'
import { apiCustomerLists } from '@/api/customer'
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
    return mode.value == 'edit' ? '编辑提成公式' : '新增提成公式'
})

// 表单数据
const formData = reactive({
    id: '',
    name: '',
    formula: '',
    status: '正常',
})


// 表单验证
const formRules = reactive<any>({
    name: [{
        required: true,
        message: '请输入公式名称',
        trigger: ['blur']
    }],
    formula: [{
        required: true,
        message: '请输入公式',
        trigger: ['blur']
    }],
    status: [{
        required: true,
        message: '请输入状态',
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
    const data = await apiCommissionFormulaDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiCommissionFormulaEdit(data)
        : await apiCommissionFormulaAdd(data)
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


defineExpose({
    open,
    setFormData,
    getDetail
})
</script>
