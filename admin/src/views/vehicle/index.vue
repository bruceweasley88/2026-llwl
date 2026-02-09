<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="车型" prop="vehicle_type">
                    <el-input class="w-[280px]" v-model="queryParams.vehicle_type" clearable placeholder="请输入车型" />
                </el-form-item>
                <el-form-item label="车牌号" prop="license_plate">
                    <el-input class="w-[280px]" v-model="queryParams.license_plate" clearable placeholder="请输入车牌号" />
                </el-form-item>
                <el-form-item label="车辆状态" prop="vehicle_status">
                    <el-select class="w-[280px]" style="width: 280px" v-model="queryParams.vehicle_status" clearable
                        placeholder="请选择车辆状态">
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
            <el-button v-perms="['vehicle/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
                新增
            </el-button>
            <!-- <el-button v-perms="['vehicle/delete']" :disabled="!selectData.length" @click="handleDelete(selectData)">
                删除
            </el-button> -->
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55" />
                    <el-table-column label="车型" prop="vehicle_type" show-overflow-tooltip />
                    <el-table-column label="车牌号" prop="license_plate" show-overflow-tooltip />
                    <el-table-column label="起步价" prop="start_price" />
                    <el-table-column label="油耗" prop="fuel" />
                    <el-table-column label="公里系数" prop="odometer_coefficient" />
                    <el-table-column label="车辆状态" prop="vehicle_status" />
                    <el-table-column label="车辆尺寸" prop="size" />

                    <el-table-column label="操作" width="120" fixed="right">
                        <template #default="{ row }">
                            <el-button v-perms="['vehicle/edit']" type="primary" link @click="handleEdit(row)">
                                编辑
                            </el-button>
                            <el-button v-perms="['vehicle/delete']" type="danger" link @click="handleDelete(row.id)">
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

<script lang="ts" setup name="vehicleLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiVehicleLists, apiVehicleDelete } from '@/api/vehicle'
import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'
import { el } from 'element-plus/es/locales.mjs'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)


// 查询条件
const queryParams = reactive({
    vehicle_type: '',
    license_plate: '',
    vehicle_status: '',
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
    fetchFun: apiVehicleLists,
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
    await apiVehicleDelete({ id })
    getLists()
}

getLists()
</script>
