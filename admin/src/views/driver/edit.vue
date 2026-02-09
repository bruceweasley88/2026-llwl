<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="550px" @confirm="handleSubmit"
            @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-form-item label="名字" prop="name">
                    <el-input v-model="formData.name" clearable placeholder="请输入名字" />
                </el-form-item>
                <el-form-item label="手机号码" prop="phone" :rules="formRules">
                    <el-input v-model="formData.phone" clearable placeholder="请输入手机号码" />
                </el-form-item>
                <el-form-item label="性别" prop="sex">
                    <el-select class="flex-1" v-model="formData.sex" clearable placeholder="请选择性别">
                        <el-option label="男" value="男"></el-option>
                        <el-option label="女" value="女"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="密码" prop="passwrod">
                    <el-input v-model="formData.passwrod" clearable placeholder="请输入密码" />
                </el-form-item>
                <el-form-item label="运输公司" prop="trans_company_id">
                    <el-select v-model="formData.trans_company_id">
                        <el-option v-for="item in transCompanyList" :key="item.id" :label="item.company_name"
                            :value="item.id" />
                    </el-select>
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

<script lang="ts" setup name="driverEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiDriverAdd, apiDriverEdit, apiDriverDetail } from '@/api/driver'
import { timeFormat } from '@/utils/util'
import type { PropType } from 'vue'
import { apiTransportCompanyLists } from '@/api/transport_company'
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
    return mode.value == 'edit' ? '编辑司机表' : '新增司机表'
})



// 表单数据
const formData = reactive({
    id: '',
    name: '',
    phone: '',
    sex: '',
    passwrod: '',
    trans_company_id: '',
    status: '',
    Starting_price: '',
})


// 表单验证
const formRules = reactive<any>({
    name: [{
        required: true,
        message: '请输入名字',
        trigger: ['blur']
    }],
    phone: [{
        required: true,
        message: '请输入手机号码',
        trigger: ['blur']
    }],
    sex: [{
        required: true,
        message: '请选择性别',
        trigger: ['blur']
    }],
    passwrod: [{
        required: true,
        message: '请输入密码',
        trigger: ['blur']
    }],
    trans_company_id: [{
        required: true,
        message: '请输入运输公司',
        trigger: ['blur']
    }],
    status: [{
        required: true,
        message: '请选择状态',
        trigger: ['blur']
    }],
    Starting_price: [{
        required: true,
        message: '请输入起步价',
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
    const data = await apiDriverDetail({
        id: row.id
    })
    setFormData(data)
}

const transCompanyList = ref<any[]>([]);
const getTransCompanyList = async () => {
    // 假设有一个API函数叫apiGetTransCompanyList
    const res = await apiTransportCompanyLists({
        page_no: 1, page_size: 999
    });
    console.log('Transport Company List:', res);
    transCompanyList.value = res.lists;
}
getTransCompanyList()

// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiDriverEdit(data)
        : await apiDriverAdd(data)
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
