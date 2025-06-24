<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="memberTable.searchParam" ref="searchFormRef">
                    <el-form-item :label="t('nickname')" prop="nickname">
                        <el-input v-model="memberTable.searchParam.nickname" :placeholder="t('nicknamePlaceholder')" />
                    </el-form-item>
                    <el-form-item :label="t('mobile')" prop="mobile">
                        <el-input v-model="memberTable.searchParam.mobile" :placeholder="t('mobilePlaceholder')" />
                    </el-form-item>
                    <el-form-item :label="t('overdueTime')" prop="overdue_time">
                        <el-date-picker v-model="memberTable.searchParam.overdue_time" type="datetimerange" format="YYYY-MM-DD hh:mm:ss" :start-placeholder="t('startDate')" :end-placeholder="t('endDate')" />
                    </el-form-item>
                    
                    <el-form-item>
                        <el-button type="primary" @click="loadMemberList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="memberTable.data" size="large" v-loading="memberTable.loading">
                    <template #empty>
                        <span>{{ !memberTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                    
					<el-table-column :label="t('会员信息')" min-width="200" :show-overflow-tooltip="true">
					    <template #default="{ row }">
							 <div class="flex items-center cursor-pointer">
							     <img class="w-[50px] h-[50px] mr-[10px]" v-if="row.headimg" :src="img(row.headimg)" alt="">
							     <img class="w-[50px] h-[50px] mr-[10px] rounded-full" v-else src="@/app/assets/images/member_head.png" alt="">
							     <div class="flex flex flex-col">
							         <div>{{ row.nickname || '' }}</div>
									 <div>{{ row.mobile || '' }}</div>
							     </div>
							 </div>
					     </template>
					</el-table-column>
					
                    <el-table-column :label="t('overdueTime')" min-width="200" :show-overflow-tooltip="true">
                        <template #default="{ row }">
                            {{ row.overdue_time || '' }}
                        </template>
                    </el-table-column>
					
					<el-table-column prop="create_time" :label="t('createTime')" min-width="200" :show-overflow-tooltip="true"/>
                    
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
                           <el-button type="primary" link @click="deleteEvent(row.id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="memberTable.page" v-model:page-size="memberTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="memberTable.total"
                        @size-change="loadMemberList()" @current-change="loadMemberList" />
                </div>
            </div>

            <edit ref="editMemberDialog" @complete="loadMemberList" />
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getMemberList, deleteMember } from '@/addon/zzhc/api/vip'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import Edit from '@/addon/zzhc/views/vip/components/member-edit.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let memberTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "nickname":"",
      "mobile":"",
      "overdue_time":[]
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])

// 字典数据
    

/**
 * 获取办卡会员列表
 */
const loadMemberList = (page: number = 1) => {
    memberTable.loading = true
    memberTable.page = page

    getMemberList({
        page: memberTable.page,
        limit: memberTable.limit,
         ...memberTable.searchParam
    }).then(res => {
        memberTable.loading = false
        memberTable.data = res.data.data
        memberTable.total = res.data.total
    }).catch(() => {
        memberTable.loading = false
    })
}
loadMemberList()

const editMemberDialog: Record<string, any> | null = ref(null)

/**
 * 添加办卡会员
 */
const addEvent = () => {
    editMemberDialog.value.setFormData()
    editMemberDialog.value.showDialog = true
}

/**
 * 编辑办卡会员
 * @param data
 */
const editEvent = (data: any) => {
    editMemberDialog.value.setFormData(data)
    editMemberDialog.value.showDialog = true
}

/**
 * 删除办卡会员
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('memberDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteMember(id).then(() => {
            loadMemberList()
        }).catch(() => {
        })
    })
}

    

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadMemberList()
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
