<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
                <el-button type="primary" @click="addEvent">
                    {{ t('addCoupon') }}
                </el-button>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="couponTable.searchParam" ref="searchFormRef">
                    <el-form-item :label="t('name')" prop="name">
                        <el-input v-model="couponTable.searchParam.name" :placeholder="t('namePlaceholder')" />
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="loadCouponList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="couponTable.data" size="large" v-loading="couponTable.loading">
                    <template #empty>
                        <span>{{ !couponTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                    
                    <el-table-column prop="name" :label="t('name')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="money" :label="t('money')" min-width="120" :show-overflow-tooltip="true"/>
					
					<el-table-column prop="count" :label="t('count')" min-width="120" :show-overflow-tooltip="true"/>
					
					<el-table-column prop="is_show" :label="t('isShow')" min-width="120" :show-overflow-tooltip="true">
					    <template #default="{ row }">
					        <span v-if="row.is_show == 1">是</span>
					        <span v-else >否</span>
					    </template>
					</el-table-column>
                    
                    <el-table-column prop="max_fetch" :label="t('maxFetch')" min-width="120" :show-overflow-tooltip="true"/>
					
					<el-table-column  :label="t('有效期')" min-width="210">
					    <template #default="{ row }">
					        <span v-if="row.validity_type == 0">领取之日起{{ row.fixed_term || '' }}天内有效</span>
							<span v-else-if="row.validity_type == 1">使用截止时间至{{ row.end_usetime || ''}} </span>
					        <span v-else> 永久</span>
					    </template>
					</el-table-column>
                    
                    <el-table-column prop="sort" :label="t('sort')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
						    <div>
							    <el-button type="primary" link @click="editEvent(row)">{{ t('edit') }}</el-button>
							    <el-button type="primary" link @click="deleteEvent(row.coupon_id)">{{ t('delete') }}</el-button>
						    </div>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="couponTable.page" v-model:page-size="couponTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="couponTable.total"
                        @size-change="loadCouponList()" @current-change="loadCouponList" />
                </div>
            </div>

            <edit ref="editCouponDialog" @complete="loadCouponList" />
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getCouponList, deleteCoupon } from '@/addon/zzhc/api/coupon'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import Edit from '@/addon/zzhc/views/coupon/components/coupon-edit.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let couponTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "type":"",
      "name":""
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])


/**
 * 获取优惠券列表
 */
const loadCouponList = (page: number = 1) => {
    couponTable.loading = true
    couponTable.page = page

    getCouponList({
        page: couponTable.page,
        limit: couponTable.limit,
         ...couponTable.searchParam
    }).then(res => {
        couponTable.loading = false
        couponTable.data = res.data.data
        couponTable.total = res.data.total
    }).catch(() => {
        couponTable.loading = false
    })
}
loadCouponList()

const editCouponDialog: Record<string, any> | null = ref(null)

/**
 * 添加优惠券
 */
const addEvent = () => {
    editCouponDialog.value.setFormData()
    editCouponDialog.value.showDialog = true
}

/**
 * 编辑优惠券
 * @param data
 */
const editEvent = (data: any) => {
    editCouponDialog.value.setFormData(data)
    editCouponDialog.value.showDialog = true
}

/**
 * 删除优惠券
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('couponDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteCoupon(id).then(() => {
            loadCouponList()
        }).catch(() => {
        })
    })
}
    

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadCouponList()
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
