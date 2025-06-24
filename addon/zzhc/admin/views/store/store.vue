<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
                <el-button type="primary" @click="addEvent">
                    {{ t('addStore') }}
                </el-button>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="storeTable.searchParam" ref="searchFormRef">
                    <el-form-item :label="t('storeName')" prop="store_name">
                        <el-input v-model="storeTable.searchParam.store_name" :placeholder="t('storeNamePlaceholder')" />
                    </el-form-item>
                    <el-form-item :label="t('storeContacts')" prop="store_contacts">
                        <el-input v-model="storeTable.searchParam.store_contacts" :placeholder="t('storeContactsPlaceholder')" />
                    </el-form-item>
                    <el-form-item :label="t('storeMobile')" prop="store_mobile">
                        <el-input v-model="storeTable.searchParam.store_mobile" :placeholder="t('storeMobilePlaceholder')" />
                    </el-form-item>
					<el-form-item :label="t('status')" prop="status">
					    <el-select class="w-[280px]" v-model="storeTable.searchParam.status" clearable :placeholder="t('statusPlaceholder')">
					        <el-option :label="t('normal')" value="normal"></el-option>
					        <el-option :label="t('shut')" value="shut"></el-option>
					    </el-select>
					</el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="loadStoreList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="storeTable.data" size="large" v-loading="storeTable.loading">
                    <template #empty>
                        <span>{{ !storeTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                     <el-table-column :label="t('storeLogo')" min-width="150" align="left">
                        <template #default="{ row }">
                            <el-avatar v-if="row.store_logo" :src="img(row.store_logo)" />
                            <el-avatar v-else icon="UserFilled" />
                        </template>
                    </el-table-column>
                    <el-table-column prop="store_name" :label="t('storeName')" min-width="180" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="trade_time" :label="t('tradeTime')" min-width="160" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="store_contacts" :label="t('storeContacts')" min-width="150" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="store_mobile" :label="t('storeMobile')" min-width="150" :show-overflow-tooltip="true"/>
                    <el-table-column prop="status" :label="t('status')" width="120">
                        <template #default="{ row }">
                            <el-tag class="cursor-pointer" :type="row.status == 'normal' ? 'success' : 'danger'" @click="statusClick(row)">{{ row.status == 'normal' ? t('normal') : t('shut') }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
                           <el-button type="primary" link @click="editEvent(row)">{{ t('edit') }}</el-button>
                           <el-button type="primary" link @click="deleteEvent(row.store_id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="storeTable.page" v-model:page-size="storeTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="storeTable.total"
                        @size-change="loadStoreList()" @current-change="loadStoreList" />
                </div>
            </div>

            
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { getStoreList, deleteStore, editStore } from '@/addon/zzhc/api/store'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import { useRouter } from 'vue-router'
import { useRoute } from 'vue-router'
import { cloneDeep } from 'lodash-es'
const route = useRoute()
const pageName = route.meta.title;

let storeTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "store_name":"",
      "store_contacts":"",
      "store_mobile":"",
	  "status":""
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])

// 字典数据
    

/**
 * 获取门店列表
 */
const loadStoreList = (page: number = 1) => {
    storeTable.loading = true
    storeTable.page = page

    getStoreList({
        page: storeTable.page,
        limit: storeTable.limit,
         ...storeTable.searchParam
    }).then(res => {
        storeTable.loading = false
        storeTable.data = res.data.data
        storeTable.total = res.data.total
    }).catch(() => {
        storeTable.loading = false
    })
}
loadStoreList()

const router = useRouter()

/**
 * 添加门店
 */
const addEvent = () => {
    router.push('/store/store_edit')
}

/**
 * 编辑门店
 * @param data
 */
const editEvent = (data: any) => {
    router.push('/store/store_edit?id='+data.store_id)
}

const statusClick = (row: any) => {
    row.status = row.status == 'normal' ? 'shut' : 'normal'
    const obj = cloneDeep(row)
    editStore(obj)
}

/**
 * 删除门店
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('storeDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteStore(id).then(() => {
            loadStoreList()
        }).catch(() => {
        })
    })
}

    

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadStoreList()
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
