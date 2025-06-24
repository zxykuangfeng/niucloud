<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
                <el-button type="primary" @click="addEvent">
                    {{ t('addVip') }}
                </el-button>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="vipTable.searchParam" ref="searchFormRef">
                    <el-form-item :label="t('vipName')" prop="vip_name">
                        <el-input v-model="vipTable.searchParam.vip_name" :placeholder="t('vipNamePlaceholder')" />
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="loadVipList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="vipTable.data" size="large" v-loading="vipTable.loading">
                    <template #empty>
                        <span>{{ !vipTable.loading ? t('emptyData') : '' }}</span>
                    </template>
					
					
                    <el-table-column prop="vip_name" :label="t('vipName')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="days" :label="t('days')+'(天)'" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="price" :label="t('price')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="sort" :label="t('sort')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="status" :label="t('status')" min-width="120">
                        <template #default="{ row }">
                            <el-tag class="cursor-pointer" :type="row.status == 'up' ? 'success' : 'danger'">{{ row.status == 'up' ? '上架' : '下架' }}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
                           <el-button type="primary" link @click="editEvent(row)">{{ t('edit') }}</el-button>
                           <el-button type="primary" link @click="deleteEvent(row.vip_id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="vipTable.page" v-model:page-size="vipTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="vipTable.total"
                        @size-change="loadVipList()" @current-change="loadVipList" />
                </div>
            </div>

            <edit ref="editVipDialog" @complete="loadVipList" />
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getVipList, deleteVip } from '@/addon/zzhc/api/vip'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import Edit from '@/addon/zzhc/views/vip/components/vip-edit.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let vipTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "vip_name":""
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])

/**
 * 获取VIP套餐列表
 */
const loadVipList = (page: number = 1) => {
    vipTable.loading = true
    vipTable.page = page

    getVipList({
        page: vipTable.page,
        limit: vipTable.limit,
         ...vipTable.searchParam
    }).then(res => {
        vipTable.loading = false
        vipTable.data = res.data.data
        vipTable.total = res.data.total
    }).catch(() => {
        vipTable.loading = false
    })
}
loadVipList()

const editVipDialog: Record<string, any> | null = ref(null)

/**
 * 添加VIP套餐
 */
const addEvent = () => {
    editVipDialog.value.setFormData()
    editVipDialog.value.showDialog = true
}

/**
 * 编辑VIP套餐
 * @param data
 */
const editEvent = (data: any) => {
    editVipDialog.value.setFormData(data)
    editVipDialog.value.showDialog = true
}

/**
 * 删除VIP套餐
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('vipDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteVip(id).then(() => {
            loadVipList()
        }).catch(() => {
        })
    })
}

    

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadVipList()
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
