<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="名字" prop="name">
                    <el-input class="w-[280px]" v-model="queryParams.name" clearable placeholder="请输入名字" />
                </el-form-item>
                <el-form-item label="性别" prop="sex">
                    <el-select class="w-[280px]" style="width: 280px" v-model="queryParams.sex" clearable
                        placeholder="请选择性别">
                        <el-option label="男" value="男"></el-option>
                        <el-option label="女" value="女"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="状态" style="width: 280px" prop="status">
                    <el-select class="w-[280px]" v-model="queryParams.status" clearable placeholder="请选择状态">
                        <el-option label="正常" value="正常"></el-option>
                        <el-option label="停用" value="停用"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-button v-perms="['driver/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
                新增
            </el-button>
            <!-- <el-button v-perms="['driver/delete']" :disabled="!selectData.length" @click="handleDelete(selectData)">
                删除
            </el-button> -->
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55" />
                    <el-table-column label="名字" prop="name" show-overflow-tooltip />
                    <el-table-column label="手机号码" prop="phone" show-overflow-tooltip />
                    <el-table-column label="性别" prop="sex" show-overflow-tooltip />
                    <el-table-column label="密码" prop="passwrod" show-overflow-tooltip />
                    <el-table-column label="运输公司" prop="trans_company_name" show-overflow-tooltip />
                    <el-table-column label="状态" prop="status" show-overflow-tooltip />
                    <el-table-column label="操作" width="120" fixed="right">
                        <template #default="{ row }">
                            <el-button v-perms="['driver/edit']" type="primary" link @click="handleEdit(row)">
                                编辑
                            </el-button>
                            <!-- <el-button v-perms="['driver/delete']" type="danger" link @click="handleDelete(row.id)">
                                删除
                            </el-button> -->
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

<script lang="ts" setup name="driverLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiDriverLists, apiDriverDelete } from '@/api/driver'
import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)


// 查询条件
const queryParams = reactive({
    name: '',
    sex: '',
    passwrod: '',
    trans_company_id: '',
    status: '',
    Starting_price: '',
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
    fetchFun: apiDriverLists,
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
    await feedback.confirm('确定要删除？')
    await apiDriverDelete({ id })
    getLists()
}

getLists()
</script>
