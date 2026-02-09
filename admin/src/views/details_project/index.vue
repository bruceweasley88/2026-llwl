<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="客户" prop="customer_name">
                    <el-input class="w-[280px]" v-model="queryParams.customer_name" clearable placeholder="请输入联系人" />
                </el-form-item>
                <!-- <el-form-item label="联系人" prop="customer_contact_person">
                    <el-input class="w-[280px]" v-model="queryParams.customer_contact_person" clearable
                        placeholder="请输入联系人" />
                </el-form-item> -->
                <el-form-item label="司机" prop="driver_name">
                    <el-input class="w-[280px]" v-model="queryParams.driver_name" clearable placeholder="请输入司机" />
                </el-form-item>
                <el-form-item label="调度单号" prop="dispatch_order_number">
                    <el-input class="w-[280px]" v-model="queryParams.dispatch_order_number" clearable
                        placeholder="请输入调度单号" />
                </el-form-item>
                <el-form-item label="时间" prop="time">
                    <el-date-picker class="w-[280px]" v-model="queryParams.time" type="date" placeholder="请选择时间"
                        format="YYYY-MM-DD" value-format="YYYY-MM-DD" />
                </el-form-item>
                <el-form-item label="备注" prop="note">
                    <el-input class="w-[280px]" v-model="queryParams.note" clearable placeholder="请输入备注" />
                </el-form-item>
                <el-form-item label="状态" prop="status">
                    <el-select class="w-[200px]" style="width: 200px" v-model="queryParams.status" clearable
                        placeholder="请输入状态">
                        <el-option label="处理中" value="处理中" />
                        <el-option label="已完成" value="已完成" />
                        <el-option label="已取消" value="已取消" />
                    </el-select>
                </el-form-item>
                <el-form-item label="是否有备注" prop="has_note">
                    <el-select class="w-[140px]" style="width: 200px" v-model="queryParams.has_note" clearable
                        placeholder="请选择">
                        <el-option label="是" value="是" />
                        <el-option label="否" value="否" />
                    </el-select>
                </el-form-item>
                <el-form-item label="是否有报销" prop="has_reimbursement">
                    <el-select class="w-[140px]" style="width: 200px" v-model="queryParams.has_reimbursement" clearable
                        placeholder="请选择">
                        <el-option label="是" value="是" />
                        <el-option label="否" value="否" />
                    </el-select>
                </el-form-item>
                <!-- <el-form-item label="订单照片" prop="order_img">
                    <el-input class="w-[280px]" v-model="queryParams.order_img" clearable placeholder="请输入订单照片" />
                </el-form-item> -->
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-button v-perms="['details_project/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
                新增
            </el-button>
            <el-button v-perms="['details_project/delete']" :disabled="!selectData.length"
                @click="handleDelete(selectData)">
                删除
            </el-button>
            <el-button @click="handleExport">
                导出
            </el-button>
            <div class="mt-4">
                <el-table :data="pager.lists" :row-style="tableRowStyle" :cell-style="tableCellStyle"
                    @selection-change="handleSelectionChange">

                    <el-table-column label="状态" prop="status" width="70" show-overflow-tooltip fixed="left" />
                    <el-table-column label="操作" width="120" fixed="left">
                        <template #default="{ row }">
                            <el-button v-perms="['details_project/edit']" type="primary" link @click="handleEdit(row)">
                                编辑
                            </el-button>
                            <el-button v-perms="['details_project/delete']" type="danger" link
                                @click="handleDelete(row.id)">
                                删除
                            </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column type="selection" width="30" />
                    <el-table-column label="客户" prop="customer_name" show-overflow-tooltip />
                    <el-table-column label="客户联系人" width="180" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.customer_contact_person }}（{{ row.customer_contact_phone }}）
                        </template>
                    </el-table-column>
                    <el-table-column label="时间" width="100" prop="time" show-overflow-tooltip />
                    <el-table-column label="司机" prop="driver_name" show-overflow-tooltip />
                    <el-table-column label="出发车辆" width="120" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.start_vehicle_license_plate }}（{{ row.start_vehicle_vehicle_type }}）
                        </template>
                    </el-table-column>
                    <el-table-column label="订单车型" width="100" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.vehicle_vehicle_type }}
                        </template>
                    </el-table-column>
                    <el-table-column label="项目" width="100" prop="project_type_type_name" show-overflow-tooltip />
                    <el-table-column label="路线" width="180" show-overflow-tooltip>
                        <template #default="{ row }">
                            <view v-if="row.route_route_name">
                                {{ row.route_route_name }}（{{ row.route_start_point }}-{{ row.route_end_point }}）
                            </view>
                            <view v-else>--</view>
                        </template>
                    </el-table-column>
                    <el-table-column label="线路距离" width="100" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.route_distance_km }}km
                        </template>
                    </el-table-column>

                    <el-table-column label="单价" width="120" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ isNumeric(row.amount) ? `￥${row.amount}`
                                : row.amount }}
                        </template>
                    </el-table-column>
                    <el-table-column label="司机提成" width="120" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ isNumeric(row.driver_commission) ? `￥${row.driver_commission}`
                                : row.driver_commission }}
                        </template>
                    </el-table-column>

                    <el-table-column label="高速费" width="90" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ `￥${row.toll}` }}
                        </template>
                    </el-table-column>
                    <el-table-column label="停车费" width="90" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ `￥${row.parking_fee}` }}
                        </template>
                    </el-table-column>
                    <el-table-column label="加油量" width="90" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.fuel_amount || '-' }}L
                        </template>
                    </el-table-column>
                    <el-table-column label="电/油费用" width="90" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ `￥${row.fuel_costs || '-'}` }}
                        </template>
                    </el-table-column>
                    <el-table-column label="维修费用" width="90" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ `￥${row.maintenance_cost || '-'}` }}
                        </template>
                    </el-table-column>
                    <el-table-column label="其他费用" width="90" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ `￥${row.other_expenses || '-'}` }}
                        </template>
                    </el-table-column>
                    <el-table-column label="调度单号" width="110" prop="dispatch_order_number" show-overflow-tooltip />
                    <el-table-column label="门店数" width="70" prop="route_store_count" show-overflow-tooltip />

                    <el-table-column label="备注" width="200" prop="note" show-overflow-tooltip />

                    <el-table-column label="高速费报销" width="100" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.is_reim_toll === true || row.is_reim_toll === 'true' || row.is_reim_toll === 1 ||
                                row.is_reim_toll === '1' ? '是' : '否' }}
                        </template>
                    </el-table-column>
                    <el-table-column label="停车费报销" width="100" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.is_reim_parking_fee === true || row.is_reim_parking_fee === 'true' ||
                                row.is_reim_parking_fee === 1 || row.is_reim_parking_fee === '1' ? '是' : '否' }}
                        </template>
                    </el-table-column>
                    <el-table-column label="电/油费用报销" width="115" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.is_reim_fuel_costs === true || row.is_reim_fuel_costs === 'true' ||
                                row.is_reim_fuel_costs === 1 || row.is_reim_fuel_costs === '1' ? '是' : '否' }}
                        </template>
                    </el-table-column>
                    <el-table-column label="维修费用报销" width="110" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.is_reim_maintenance_cost === true || row.is_reim_maintenance_cost === 'true' ||
                                row.is_reim_maintenance_cost === 1 || row.is_reim_maintenance_cost === '1' ? '是' : '否' }}
                        </template>
                    </el-table-column>
                    <el-table-column label="其他费用报销" width="110" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.is_reim_other_expenses === true || row.is_reim_other_expenses === 'true' ||
                                row.is_reim_other_expenses === 1 || row.is_reim_other_expenses === '1' ? '是' : '否' }}
                        </template>
                    </el-table-column>
                    <!-- <el-table-column label="订单照片" prop="order_img" show-overflow-tooltip /> -->
                </el-table>
            </div>
            <div class="flex mt-4 justify-end">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
        <edit-popup v-if="showEdit" ref="editRef" :dict-data="dictData" @success="getLists" @close="showEdit = false" />
    </div>
</template>

<script lang="ts" setup name="detailsProjectLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiDetailsProjectLists, apiDetailsProjectDelete } from '@/api/details_project'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'
import * as XLSX from 'xlsx'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)


// 查询条件
const queryParams = reactive({
    dispatch_order_number: '',
    number_of_outlets: '',
    starting_price: '',
    time: '',
    note: '',
    unit_price: '',
    status: '',
    order_img: '',
    customer_name: '',
    customer_contact_person: '',
    driver_name: '',
    has_note: '',
    has_reimbursement: '',
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
    fetchFun: apiDetailsProjectLists,
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
    await apiDetailsProjectDelete({ id })
    getLists()
}

// 导出
const handleExport = async () => {
    try {
        feedback.loading('正在导出...')

        // 获取所有数据（设置page_size为999999）
        const res = await apiDetailsProjectLists({
            page_no: 1,
            page_size: 25000,
            ...queryParams
        })

        const lists = res?.lists || []

        if (lists.length === 0) {
            feedback.closeLoading()
            feedback.msgError('没有数据可导出')
            return
        }

        // 格式化导出数据
        const exportData = lists.map((row: any) => ({
            '客户': row.customer_name || '',
            '客户联系人': row.customer_contact_person ? `${row.customer_contact_person}（${row.customer_contact_phone || ''}）` : '',
            '时间': row.time || '',
            '司机': row.driver_name || '',
            '出发车辆': row.start_vehicle_license_plate ? `${row.start_vehicle_license_plate}（${row.start_vehicle_vehicle_type || ''}）` : '',
            '订单车型': row.vehicle_vehicle_type || '',
            '项目': row.project_type_type_name || '',
            '路线': row.route_route_name ? `${row.route_route_name}（${row.route_start_point || ''}-${row.route_end_point || ''}）` : '',
            '线路距离': row.route_distance_km ? `${row.route_distance_km}km` : '',
            '单价': isNumeric(row.amount) ? `￥${row.amount}` : (row.amount || ''),
            '司机提成': isNumeric(row.driver_commission) ? `￥${row.driver_commission}` : (row.driver_commission || ''),
            '高速费': `￥${row.toll || 0}`,
            '停车费': `￥${row.parking_fee || 0}`,
            '加油量': row.fuel_amount ? `${row.fuel_amount}L` : '',
            '电/油费用': `￥${row.fuel_costs || 0}`,
            '维修费用': `￥${row.maintenance_cost || 0}`,
            '其他费用': `￥${row.other_expenses || 0}`,
            '调度单号': row.dispatch_order_number || '',
            '门店数': row.route_store_count || '',
            '备注': row.note || '',
            '高速费报销': row.is_reim_toll === true || row.is_reim_toll === 'true' || row.is_reim_toll === 1 || row.is_reim_toll === '1' ? '是' : '否',
            '停车费报销': row.is_reim_parking_fee === true || row.is_reim_parking_fee === 'true' || row.is_reim_parking_fee === 1 || row.is_reim_parking_fee === '1' ? '是' : '否',
            '电/油费用报销': row.is_reim_fuel_costs === true || row.is_reim_fuel_costs === 'true' || row.is_reim_fuel_costs === 1 || row.is_reim_fuel_costs === '1' ? '是' : '否',
            '维修费用报销': row.is_reim_maintenance_cost === true || row.is_reim_maintenance_cost === 'true' || row.is_reim_maintenance_cost === 1 || row.is_reim_maintenance_cost === '1' ? '是' : '否',
            '其他费用报销': row.is_reim_other_expenses === true || row.is_reim_other_expenses === 'true' || row.is_reim_other_expenses === 1 || row.is_reim_other_expenses === '1' ? '是' : '否',
        }))

        // 创建工作表
        const ws = XLSX.utils.json_to_sheet(exportData)

        // 创建工作簿
        const wb = XLSX.utils.book_new()
        XLSX.utils.book_append_sheet(wb, ws, '明细表')

        // 生成文件名（带时间戳）
        const now = new Date()
        const timestamp = now.toISOString().slice(0, 19).replace(/[-:T]/g, '')
        const fileName = `明细表_${timestamp}.xlsx`

        // 导出文件
        XLSX.writeFile(wb, fileName)

        feedback.closeLoading()
        feedback.msgSuccess('导出成功')
    } catch (error) {
        feedback.closeLoading()
        feedback.msgError('导出失败')
        console.error(error)
    }
}

function isNumeric(value: any) {
    // 1. 过滤非字符串/数字类型（布尔、对象、null/undefined 等直接排除）
    if (typeof value !== 'string' && typeof value !== 'number') {
        return false;
    }

    // 2. 转字符串并去除首尾空格（处理 "  123  " 这类情况）
    const trimmedStr = String(value).trim();

    // 3. 空字符串（含纯空格）直接排除
    if (trimmedStr === '') {
        return false;
    }

    // 4. 转换为数值并判断是否为有限数值（排除 NaN、Infinity）
    const num = Number(trimmedStr);
    return isFinite(num);
}

// 判断是否有报销
const hasReimbursement = (row: any) => {
    return row.is_reim_toll === true || row.is_reim_toll === 'true' || row.is_reim_toll === 1 || row.is_reim_toll === '1' ||
        row.is_reim_parking_fee === true || row.is_reim_parking_fee === 'true' || row.is_reim_parking_fee === 1 || row.is_reim_parking_fee === '1' ||
        row.is_reim_fuel_costs === true || row.is_reim_fuel_costs === 'true' || row.is_reim_fuel_costs === 1 || row.is_reim_fuel_costs === '1' ||
        row.is_reim_maintenance_cost === true || row.is_reim_maintenance_cost === 'true' || row.is_reim_maintenance_cost === 1 || row.is_reim_maintenance_cost === '1' ||
        row.is_reim_other_expenses === true || row.is_reim_other_expenses === 'true' || row.is_reim_other_expenses === 1 || row.is_reim_other_expenses === '1'
}

// 行样式：存在报销时背景变红
const tableRowStyle = ({ row }: { row: any }) => {
    if (hasReimbursement(row)) {
        return { backgroundColor: '#ffaaaa' }
    }
    return {}
}

// 单元格样式：备注有值时该单元格变鲜红色
const tableCellStyle = ({ row, column }: { row: any, column: any }) => {
    if (column.property === 'note' && row.note && row.id) {
        return { backgroundColor: '#ff6666' }
    }
}

getLists()
</script>

<style>
.el-scrollbar__bar.is-horizontal>div {
    height: 16px;
    position: relative;
    top: -10px;
}
</style>
