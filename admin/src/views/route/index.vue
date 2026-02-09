<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="客户" prop="customer_name">
                    <el-input class="w-[280px]" v-model="queryParams.customer_name" clearable placeholder="请输入联系人" />
                </el-form-item>
                <el-form-item label="路线名称" prop="route_name">
                    <el-input class="w-[280px]" v-model="queryParams.route_name" clearable placeholder="请输入路线名称" />
                </el-form-item>
                <el-form-item label="起点" prop="start_point">
                    <el-input class="w-[280px]" v-model="queryParams.start_point" clearable placeholder="请输入起点" />
                </el-form-item>
                <el-form-item label="终点" prop="end_point">
                    <el-input class="w-[280px]" v-model="queryParams.end_point" clearable placeholder="请输入终点" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-button v-perms="['route/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
                新增
            </el-button>
            <!-- <el-button v-perms="['route/delete']" :disabled="!selectData.length" @click="handleDelete(selectData)">
                删除
            </el-button> -->
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="0" />
                    <el-table-column label="路线名称" prop="route_name" show-overflow-tooltip />
                    <el-table-column label="客户名称" prop="customer_name" show-overflow-tooltip />
                    <el-table-column label="起点" width="200" prop="start_point" show-overflow-tooltip />
                    <el-table-column label="终点" width="200" prop="end_point" show-overflow-tooltip />
                    <el-table-column label="里程(km)" width="100" prop="distance_km" show-overflow-tooltip />
                    <el-table-column label="门店数" width="100" prop="store_count" show-overflow-tooltip />
                    <el-table-column label="单价公式" prop="amount_formula_name" show-overflow-tooltip />
                    <el-table-column label="基准油价" prop="fuel_price" show-overflow-tooltip />
                    <el-table-column label="司机提成公式" prop="commission_formula_name" show-overflow-tooltip />
                    <el-table-column label="操作" width="120" fixed="right">
                        <template #default="{ row }">
                            <el-button v-perms="['route/edit']" type="primary" link @click="handleEdit(row)">
                                编辑
                            </el-button>
                            <el-button v-perms="['route/delete']" type="danger" link @click="handleDelete(row.id)">
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

<script lang="ts" setup name="routeLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiRouteLists, apiRouteDelete } from '@/api/route'
import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)


// 查询条件
const queryParams = reactive({
    route_name: '',
    start_point: '',
    end_point: '',
    distance_km: '',
    toll: '',
    Parking_fee: '',
    Reference_fuel_consumption: '',
    Fuel_consumption: '',
    customer_name: '',

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
    fetchFun: apiRouteLists,
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
    await apiRouteDelete({ id })
    getLists()
}

getLists()
</script>
