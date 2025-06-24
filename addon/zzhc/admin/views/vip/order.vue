<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="orderTable.searchParam" ref="searchFormRef">
                    <el-form-item :label="t('orderNo')" prop="order_no">
                        <el-input v-model="orderTable.searchParam.order_no" :placeholder="t('orderNoPlaceholder')" clearable />
                    </el-form-item>
                    <el-form-item :label="t('nickname')" prop="nickname">
                        <el-input v-model="orderTable.searchParam.nickname" :placeholder="t('nicknamePlaceholder')" clearable />
                    </el-form-item>
                    <el-form-item :label="t('mobile')" prop="mobile">
                        <el-input v-model="orderTable.searchParam.mobile" :placeholder="t('mobilePlaceholder')" clearable />
                    </el-form-item>
					<el-form-item :label="t('status')" prop="status" class="w-[240px]">
					    <el-select v-model="orderTable.searchParam.status" clearable :placeholder="t('statusPlaceholder')">
					       <el-option
							   v-for="(item, index) in statusList"
							   :key="index"
							   :label="item.name"
							   :value="item.status"
						   />
					    </el-select>
					</el-form-item>
                    <el-form-item :label="t('createTime')" prop="create_time">
                        <el-date-picker v-model="orderTable.searchParam.create_time" type="datetimerange" format="YYYY-MM-DD hh:mm:ss"
                            :start-placeholder="t('startDate')" :end-placeholder="t('endDate')" />
                    </el-form-item>
                    
                    <el-form-item>
                        <el-button type="primary" @click="loadOrderList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="orderTable.data" size="large" v-loading="orderTable.loading">
                    <template #empty>
                        <span>{{ !orderTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                    <el-table-column prop="order_no" :label="t('orderNo')" min-width="220" :show-overflow-tooltip="true"/>
                    
					
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
                    
                    
                    <el-table-column prop="vip_name" :label="t('vipName')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="days" :label="t('days')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="vip_money" :label="t('vipMoney')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="order_money" :label="t('orderMoney')" min-width="120" :show-overflow-tooltip="true"/>
                    
					<el-table-column prop="order_from_name" :label="t('orderFrom')" min-width="120" :show-overflow-tooltip="true"/>
					<el-table-column prop="status_name.name" :label="t('status')" min-width="120" :show-overflow-tooltip="true"/>
                    
					<el-table-column prop="create_time" :label="t('createTime')" min-width="200" :show-overflow-tooltip="true"/>
                    
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
                           <el-button type="primary" link @click="deleteEvent(row.order_id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="orderTable.page" v-model:page-size="orderTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="orderTable.total"
                        @size-change="loadOrderList()" @current-change="loadOrderList" />
                </div>
            </div>

            <edit ref="editOrderDialog" @complete="loadOrderList" />
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getOrderList, deleteOrder } from '@/addon/zzhc/api/vip'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import Edit from '@/addon/zzhc/views/vip/components/order-edit.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let orderTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "order_no":"",
      "nickname":"",
      "mobile":"",
      "out_trade_no":"",
      "status":"",
      "create_time":[]
    }
})

const searchFormRef = ref<FormInstance>()

const statusList = ref<any[]>([
	{name:'待支付',status:1},
	{name:'已支付',status:2},
])

// 字典数据
    

/**
 * 获取办卡订单列表
 */
const loadOrderList = (page: number = 1) => {
    orderTable.loading = true
    orderTable.page = page

    getOrderList({
        page: orderTable.page,
        limit: orderTable.limit,
         ...orderTable.searchParam
    }).then(res => {
        orderTable.loading = false
        orderTable.data = res.data.data
        orderTable.total = res.data.total
    }).catch(() => {
        orderTable.loading = false
    })
}
loadOrderList()

const editOrderDialog: Record<string, any> | null = ref(null)

/**
 * 添加办卡订单
 */
const addEvent = () => {
    editOrderDialog.value.setFormData()
    editOrderDialog.value.showDialog = true
}

/**
 * 编辑办卡订单
 * @param data
 */
const editEvent = (data: any) => {
    editOrderDialog.value.setFormData(data)
    editOrderDialog.value.showDialog = true
}

/**
 * 删除办卡订单
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('orderDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteOrder(id).then(() => {
            loadOrderList()
        }).catch(() => {
        })
    })
}

    

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadOrderList()
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
