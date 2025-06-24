<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
                <el-button type="primary" @click="addEvent">
                    {{ t('addStaff') }}
                </el-button>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="staffTable.searchParam" ref="searchFormRef">
                    
                    <el-form-item :label="t('storeId')" prop="store_id">
                        <el-select class="w-[280px]" v-model="staffTable.searchParam.store_id" filterable clearable :placeholder="t('storeIdPlaceholder')">
                           <el-option
							   v-for="(item, index) in storeIdList"
							   :key="index"
							   :label="item['store_name']"
							   :value="item['store_id']"
						   />
                        </el-select>
                    </el-form-item>
                    
                    <el-form-item :label="t('staffName')" prop="staff_name">
                        <el-input v-model="staffTable.searchParam.staff_name" :placeholder="t('staffNamePlaceholder')" />
                    </el-form-item>
                    <el-form-item :label="t('staffMobile')" prop="staff_mobile">
                        <el-input v-model="staffTable.searchParam.staff_mobile" :placeholder="t('staffMobilePlaceholder')" />
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="loadStaffList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="staffTable.data" size="large" v-loading="staffTable.loading">
                    <template #empty>
                        <span>{{ !staffTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                    <el-table-column prop="store_id_name" :label="t('storeId')" min-width="120" :show-overflow-tooltip="true"/>
                    <el-table-column prop="staff_name" :label="t('staffName')" min-width="120" :show-overflow-tooltip="true"/>
                    
					<el-table-column prop="staff_role_name" :label="t('staffRole')" min-width="130">
					    <template #default="{ row }">
					        <el-tag class="cursor-pointer mr-[10px]" v-for="(item,index) in row.staff_role_name">{{ item }}</el-tag>
					    </template>
					</el-table-column>
                    <el-table-column :label="t('staffHeadimg')" min-width="120" align="left">
                        <template #default="{ row }">
                            <el-avatar v-if="row.staff_headimg" :src="img(row.staff_headimg)" />
                            <el-avatar v-else icon="UserFilled" />
                        </template>
                    </el-table-column>
                    <el-table-column prop="staff_name" :label="t('staffName')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="staff_mobile" :label="t('staffMobile')" min-width="120" :show-overflow-tooltip="true"/>
                    <el-table-column :label="t('memberId')" min-width="150" :show-overflow-tooltip="true">
                         <template #default="{ row }">
                     		 <div class="flex items-center cursor-pointer" v-if="row.member">
                     		     <img class="w-[50px] h-[50px] mr-[10px]" v-if="row.member.headimg" :src="img(row.member.headimg)" alt="">
                     		     <img class="w-[50px] h-[50px] mr-[10px] rounded-full" v-else src="@/app/assets/images/member_head.png" alt="">
                     		     <div class="flex flex flex-col">
                     		        <div>{{ row.member.member_no || '' }}</div>
                     				<div>{{ row.member.nickname || '' }}</div>
                     		     </div>
                     			
                     		 </div>
                              <el-tag type="error" v-else class="cursor-pointer">{{ t('未绑定') }}</el-tag>
                          </template>
                     </el-table-column>
                    
                    <el-table-column prop="sort" :label="t('sort')" min-width="80" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="status" :label="t('status')" min-width="80">
                        <template #default="{ row }">
                            <el-tag class="cursor-pointer" :type="row.status == 'normal' ? 'success' : 'danger'">{{ row.status == 'normal' ? '在职' : '离职' }}</el-tag>
                        </template>
                    </el-table-column>
                    
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
                           <el-button type="primary" link @click="editEvent(row)">{{ t('edit') }}</el-button>
                           <el-button type="primary" link @click="deleteEvent(row.staff_id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="staffTable.page" v-model:page-size="staffTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="staffTable.total"
                        @size-change="loadStaffList()" @current-change="loadStaffList" />
                </div>
            </div>

            
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getStaffList, deleteStaff } from '@/addon/zzhc/api/staff'
import { getStoreAll } from '@/addon/zzhc/api/store'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import { useRouter } from 'vue-router'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let staffTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "store_id":"",
      "staff_name":"",
      "staff_mobile":""
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])

// 字典数据
    

/**
 * 获取员工列表
 */
const loadStaffList = (page: number = 1) => {
    staffTable.loading = true
    staffTable.page = page

    getStaffList({
        page: staffTable.page,
        limit: staffTable.limit,
         ...staffTable.searchParam
    }).then(res => {
        staffTable.loading = false
        staffTable.data = res.data.data
        staffTable.total = res.data.total
    }).catch(() => {
        staffTable.loading = false
    })
}
loadStaffList()

const router = useRouter()

/**
 * 添加员工
 */
const addEvent = () => {
    router.push('/staff/staff_edit')
}

/**
 * 编辑员工
 * @param data
 */
const editEvent = (data: any) => {
    router.push('/staff/staff_edit?id='+data.staff_id)
}

/**
 * 删除员工
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('staffDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteStaff(id).then(() => {
            loadStaffList()
        }).catch(() => {
        })
    })
}

    
const storeIdList = ref([])
const setStoreIdList = async () => {
    storeIdList.value = await (await getStoreAll({})).data
}
setStoreIdList()

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadStaffList()
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
