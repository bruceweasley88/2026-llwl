<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="客户名称" prop="customer_name">
                    <el-input class="w-[280px]" v-model="queryParams.customer_name" clearable placeholder="请输入客户名称" />
                </el-form-item>
                <el-form-item label="联系人" prop="contact_person">
                    <el-input class="w-[280px]" v-model="queryParams.contact_person" clearable placeholder="请输入联系人" />
                </el-form-item>
                <el-form-item label="联系电话" prop="contact_phone">
                    <el-input class="w-[280px]" v-model="queryParams.contact_phone" clearable placeholder="请输入联系电话" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-button v-perms="['customer/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
                新增
            </el-button>
            <!-- <el-button v-perms="['customer/delete']" :disabled="!selectData.length" @click="handleDelete(selectData)">
                删除
            </el-button> -->
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55" />
                    <el-table-column label="客户名称" prop="customer_name" show-overflow-tooltip />
                    <el-table-column label="联系人" prop="contact_person" show-overflow-tooltip />
                    <el-table-column label="联系电话" prop="contact_phone" show-overflow-tooltip />
                    <el-table-column label="操作" width="120" fixed="right">
                        <template #default="{ row }">
                            <el-button v-perms="['customer/edit']" type="primary" link @click="handleEdit(row)">
                                编辑
                            </el-button>
                            <el-button v-perms="['customer/delete']" type="danger" link @click="handleDelete(row.id)">
                                删除
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <div class="flex mt-4 justify-end">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
        <edit-popup v-if="showEdit" ref="editRef" :dict-data="dictData" @success="getLists" @close="showEdit = false" />
    </div>
</template>

<script lang="ts" setup name="customerLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiCustomerLists, apiCustomerDelete } from '@/api/customer'
import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)


// 查询条件
const queryParams = reactive({
    customer_name: '',
    contact_person: '',
    contact_phone: '',
})

// 选中数据
const selectData = ref<any[]>([])

// 表格选择后回调事件
const handleSelectionChange = (val: any[]) => {
    selectData.value = val.map(({ id }) => id)
}

// 获取字典数据
const { dictData } = useDictData('')

// 分页相关
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiCustomerLists,
    params: queryParams
})

// 添加
const handleAdd = async () => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('add')
}

// 编辑
const handleEdit = async (data: any) => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('edit')
    editRef.value?.setFormData(data)
}

// 删除
const handleDelete = async (id: number | any[]) => {
    await feedback.confirm('确定要删除？(谨慎，全部相关数据将被删除且不可恢复)')
    await apiCustomerDelete({ id })
    getLists()
}

getLists()
</script>
