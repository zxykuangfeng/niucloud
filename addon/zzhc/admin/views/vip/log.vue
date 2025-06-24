<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
                <el-button type="primary" @click="addEvent">
                    {{ t('addLog') }}
                </el-button>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="logTable.searchParam" ref="searchFormRef">
                    <el-form-item :label="t('content')" prop="content">
                        <el-date-picker v-model="logTable.searchParam.content" type="datetimerange" format="YYYY-MM-DD hh:mm:ss"
                            :start-placeholder="t('startDate')" :end-placeholder="t('endDate')" />
                    </el-form-item>
                    
                    <el-form-item>
                        <el-button type="primary" @click="loadLogList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="logTable.data" size="large" v-loading="logTable.loading">
                    <template #empty>
                        <span>{{ !logTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                    <el-table-column prop="vip_member_id" :label="t('vipMemberId')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="main_type" :label="t('mainType')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="main_id" :label="t('mainId')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="days" :label="t('days')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="type" :label="t('type')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="content" :label="t('content')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
                           <el-button type="primary" link @click="editEvent(row)">{{ t('edit') }}</el-button>
                           <el-button type="primary" link @click="deleteEvent(row.id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="logTable.page" v-model:page-size="logTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="logTable.total"
                        @size-change="loadLogList()" @current-change="loadLogList" />
                </div>
            </div>

            <edit ref="editLogDialog" @complete="loadLogList" />
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getLogList, deleteLog } from '@/addon/zzhc/api/vip'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import Edit from '@/addon/zzhc/views/vip/components/log-edit.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let logTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "content":[]
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])

// 字典数据
    

/**
 * 获取VIP会员操作日志列表
 */
const loadLogList = (page: number = 1) => {
    logTable.loading = true
    logTable.page = page

    getLogList({
        page: logTable.page,
        limit: logTable.limit,
         ...logTable.searchParam
    }).then(res => {
        logTable.loading = false
        logTable.data = res.data.data
        logTable.total = res.data.total
    }).catch(() => {
        logTable.loading = false
    })
}
loadLogList()

const editLogDialog: Record<string, any> | null = ref(null)

/**
 * 添加VIP会员操作日志
 */
const addEvent = () => {
    editLogDialog.value.setFormData()
    editLogDialog.value.showDialog = true
}

/**
 * 编辑VIP会员操作日志
 * @param data
 */
const editEvent = (data: any) => {
    editLogDialog.value.setFormData(data)
    editLogDialog.value.showDialog = true
}

/**
 * 删除VIP会员操作日志
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('logDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteLog(id).then(() => {
            loadLogList()
        }).catch(() => {
        })
    })
}

    

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadLogList()
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
