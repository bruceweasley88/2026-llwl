<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="popupTitle"
            :async="true"
            width="550px"
            @confirm="handleSubmit"
            @close="handleClose"
        >
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-form-item label="客户名称" prop="customer_name">
    <el-input v-model="formData.customer_name" clearable placeholder="请输入客户名称" />
</el-form-item>
                <el-form-item label="联系人" prop="contact_person">
    <el-input v-model="formData.contact_person" clearable placeholder="请输入联系人" />
</el-form-item>
                <el-form-item label="联系电话" prop="contact_phone">
    <el-input v-model="formData.contact_phone" clearable placeholder="请输入联系电话" />
</el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="customerEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiCustomerAdd, apiCustomerEdit, apiCustomerDetail } from '@/api/customer'
import { timeFormat } from '@/utils/util'
import type { PropType } from 'vue'
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
    return mode.value == 'edit' ? '编辑客户表' : '新增客户表'
})

// 表单数据
const formData = reactive({
    id: '',
    customer_name: '',
    contact_person: '',
    contact_phone: '',
})


// 表单验证
const formRules = reactive<any>({
    customer_name: [{
        required: true,
        message: '请输入客户名称',
        trigger: ['blur']
    }],
    contact_person: [{
        required: true,
        message: '请输入联系人',
        trigger: ['blur']
    }],
    contact_phone: [{
        required: true,
        message: '请输入联系电话',
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
    const data = await apiCustomerDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiCustomerEdit(data) 
        : await apiCustomerAdd(data)
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
