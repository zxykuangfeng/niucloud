<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
                <el-button type="primary" @click="addEvent">
                    {{ t('addGoods') }}
                </el-button>
            </div>
            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="goodsTable.searchParam" ref="searchFormRef">
                   
                    
                    <el-form-item :label="t('categoryId')" prop="category_id">
                        <el-select class="w-[280px]" v-model="goodsTable.searchParam.category_id" clearable :placeholder="t('categoryIdPlaceholder')">
                           <el-option
                                       v-for="(item, index) in categoryIdList"
                                       :key="index"
                                       :label="item['category_name']"
                                       :value="item['category_id']"
                                   />
                        </el-select>
                    </el-form-item>
                    
                    <el-form-item :label="t('goodsName')" prop="goods_name">
                        <el-input v-model="goodsTable.searchParam.goods_name" :placeholder="t('goodsNamePlaceholder')" />
                    </el-form-item>
                    <el-form-item :label="t('status')" prop="status">
                        <el-input v-model="goodsTable.searchParam.status" :placeholder="t('statusPlaceholder')" />
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="loadGoodsList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="goodsTable.data" size="large" v-loading="goodsTable.loading">
                    <template #empty>
                        <span>{{ !goodsTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                    
                    <el-table-column prop="category_name" :label="t('categoryId')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="goods_name" :label="t('goodsName')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    
					<el-table-column :label="t('goodsImage')" width="170" align="left">
					    <template #default="{ row }">
					        <div class="h-[60px]">
					            <el-image class="w-[60px] h-[60px] " :src="img(row.goods_image)" fit="contain">
					                <template #error>
					                    <div class="image-slot">
					                        <img class="w-[60px] h-[60px]" src="@/addon/zzhc/assets/no_img.png" />
					                    </div>
					                </template>
					            </el-image>
					        </div>
					    </template>
					</el-table-column>
                    <el-table-column prop="duration" :label="t('duration')" min-width="120" :show-overflow-tooltip="true"/>
                    
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
                           <el-button type="primary" link @click="deleteEvent(row.goods_id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="goodsTable.page" v-model:page-size="goodsTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="goodsTable.total"
                        @size-change="loadGoodsList()" @current-change="loadGoodsList" />
                </div>
            </div>

            <edit ref="editGoodsDialog" @complete="loadGoodsList" />
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getGoodsList, deleteGoods, getCategoryAll } from '@/addon/zzhc/api/goods'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import Edit from '@/addon/zzhc/views/goods/components/goods-edit.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let goodsTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "store_ids":"",
      "category_id":"",
      "goods_name":"",
      "status":""
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])

// 字典数据
    

/**
 * 获取项目列表
 */
const loadGoodsList = (page: number = 1) => {
    goodsTable.loading = true
    goodsTable.page = page

    getGoodsList({
        page: goodsTable.page,
        limit: goodsTable.limit,
         ...goodsTable.searchParam
    }).then(res => {
        goodsTable.loading = false
        goodsTable.data = res.data.data
        goodsTable.total = res.data.total
    }).catch(() => {
        goodsTable.loading = false
    })
}
loadGoodsList()

const editGoodsDialog: Record<string, any> | null = ref(null)

/**
 * 添加项目
 */
const addEvent = () => {
    editGoodsDialog.value.setFormData()
    editGoodsDialog.value.showDialog = true
}

/**
 * 编辑项目
 * @param data
 */
const editEvent = (data: any) => {
    editGoodsDialog.value.setFormData(data)
    editGoodsDialog.value.showDialog = true
}

/**
 * 删除项目
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('goodsDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteGoods(id).then(() => {
            loadGoodsList()
        }).catch(() => {
        })
    })
}

    
const categoryIdList = ref([])
    const setCategoryIdList = async () => {
    categoryIdList.value = await (await getCategoryAll()).data
}
setCategoryIdList()

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadGoodsList()
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
