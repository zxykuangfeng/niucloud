<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="workTable.searchParam" ref="searchFormRef">
					<el-form-item :label="t('storeId')" prop="store_id" class="w-[260px]">
					    <el-select v-model="workTable.searchParam.store_id" filterable clearable :placeholder="t('storeIdPlaceholder')">
					       <el-option
							   v-for="(item, index) in storeIdList"
							   :key="index"
							   :label="item['store_name']"
							   :value="item['store_id']"
						   />
					    </el-select>
					</el-form-item>
					
                    <el-form-item :label="t('staffId')" prop="staff_id" class="w-[260px]">
						
						<el-select v-model="workTable.searchParam.staff_id" filterable clearable :placeholder="t('staffIdPlaceholder')">
						    <el-option
							   v-for="(item, index) in staffIdList"
							   :key="index"
							   :label="item['staff_name']"
							   :value="item['staff_id']"
						    />
						</el-select>
						
                    </el-form-item>
					
					<el-form-item :label="t('workStatus')" prop="store_id" class="w-[260px]" >
					    <el-select v-model="workTable.searchParam.status" filterable clearable :placeholder="t('workStatusPlaceholder')">
					       <el-option
							   v-for="(item, index) in statusList"
							   :key="index"
							   :label="item['name']"
							   :value="item['value']"
						   />
					    </el-select>
					</el-form-item>
					
                    <el-form-item :label="t('createTime')" prop="create_time">
                        <el-date-picker v-model="workTable.searchParam.create_time" type="datetimerange" format="YYYY-MM-DD hh:mm:ss" :start-placeholder="t('startDate')" :end-placeholder="t('endDate')" />
                    </el-form-item>
                    
                    <el-form-item>
                        <el-button type="primary" @click="loadWorkList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="workTable.data" size="large" v-loading="workTable.loading">
                    <template #empty>
                        <span>{{ !workTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                    <el-table-column prop="store.store_name" :label="t('storeId')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="staff.staff_name" :label="t('staffId')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="status_name" :label="t('workStatus')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="full_address" :label="t('位置')" min-width="420" :show-overflow-tooltip="true"/>
                    
                     <el-table-column :label="t('createTime')" min-width="180" align="center" :show-overflow-tooltip="true">
                        <template #default="{ row }">
                            {{ row.create_time || '' }}
                        </template>
                    </el-table-column>
                    
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
                           <el-button type="primary" link @click="deleteEvent(row.id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="workTable.page" v-model:page-size="workTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="workTable.total"
                        @size-change="loadWorkList()" @current-change="loadWorkList" />
                </div>
            </div>

            <edit ref="editWorkDialog" @complete="loadWorkList" />
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getWorkList, deleteWork, getStaffAll } from '@/addon/zzhc/api/staff'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import Edit from '@/addon/zzhc/views/staff/components/work-edit.vue'
import { getStoreAll } from '@/addon/zzhc/api/store'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let workTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "store_id":"",
      "staff_id":"",
      "status":"",
      "create_time":[]
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])

// 字典数据
const statusList = ref([
	{'name':'上班',value:'working'},
	{'name':'用餐',value:'meal'},
	{'name':'有事',value:'thing'},
	{'name':'停止',value:'stop'},
	{'name':'下班',value:'rest'},
]);   

const storeIdList = ref([])
const setStoreIdList = async () => {
    storeIdList.value = await (await getStoreAll({})).data
}
setStoreIdList()

const staffIdList = ref([])
const setStaffIdList = async () => {
    staffIdList.value = await (await getStaffAll({})).data
}
setStaffIdList()

/**
 * 获取考勤管理列表
 */
const loadWorkList = (page: number = 1) => {
    workTable.loading = true
    workTable.page = page

    getWorkList({
        page: workTable.page,
        limit: workTable.limit,
         ...workTable.searchParam
    }).then(res => {
        workTable.loading = false
        workTable.data = res.data.data
        workTable.total = res.data.total
    }).catch(() => {
        workTable.loading = false
    })
}
loadWorkList()

const editWorkDialog: Record<string, any> | null = ref(null)

/**
 * 添加考勤管理
 */
const addEvent = () => {
    editWorkDialog.value.setFormData()
    editWorkDialog.value.showDialog = true
}

/**
 * 编辑考勤管理
 * @param data
 */
const editEvent = (data: any) => {
    editWorkDialog.value.setFormData(data)
    editWorkDialog.value.showDialog = true
}

/**
 * 删除考勤管理
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('workDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteWork(id).then(() => {
            loadWorkList()
        }).catch(() => {
        })
    })
}

    

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadWorkList()
}
</script>

<style lang="scss" scoped>
/* 多行超出隐藏 */
.multi-hidden {
		word-break: break-all;
		text-overflow: ellipsis;
		overflow: hidden;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}
</style>
