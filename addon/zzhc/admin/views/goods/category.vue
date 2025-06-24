<template>
    <div class="main-container">
        <el-card class="box-card !border-none" shadow="never">

            <div class="flex justify-between items-center">
                <span class="text-lg">{{pageName}}</span>
                <el-button type="primary" @click="addEvent">
                    {{ t('addCategory') }}
                </el-button>
            </div>

            <el-card class="box-card !border-none my-[10px] table-search-wrap" shadow="never">
                <el-form :inline="true" :model="categoryTable.searchParam" ref="searchFormRef">
                    <el-form-item :label="t('categoryName')" prop="category_name">
                        <el-input v-model="categoryTable.searchParam.category_name" :placeholder="t('categoryNamePlaceholder')" />
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="loadCategoryList()">{{ t('search') }}</el-button>
                        <el-button @click="resetForm(searchFormRef)">{{ t('reset') }}</el-button>
                    </el-form-item>
                </el-form>
            </el-card>

            <div class="mt-[10px]">
                <el-table :data="categoryTable.data" size="large" v-loading="categoryTable.loading">
                    <template #empty>
                        <span>{{ !categoryTable.loading ? t('emptyData') : '' }}</span>
                    </template>
                    <el-table-column prop="category_name" :label="t('categoryName')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column :label="t('categoryImage')" width="170" align="left">
                        <template #default="{ row }">
                            <div class="h-[60px]">
                                <el-image class="w-[60px] h-[60px] " :src="img(row.category_image)" fit="contain">
                                    <template #error>
                                        <div class="image-slot">
                                            <img class="w-[60px] h-[60px]" src="@/addon/zzhc/assets/no_img.png" />
                                        </div>
                                    </template>
                                </el-image>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="sort" :label="t('sort')" min-width="120" :show-overflow-tooltip="true"/>
                    
                    <el-table-column prop="status" :label="t('status')" min-width="120">
                        <template #default="{ row }">
                            <el-tag class="cursor-pointer" :type="row.status == 'normal' ? 'success' : 'danger'">{{ row.status == 'normal' ? '显示' : '隐藏' }}</el-tag>
                        </template>
                    </el-table-column>
                    
                    <el-table-column :label="t('operation')" fixed="right" min-width="120">
                       <template #default="{ row }">
                           <el-button type="primary" link @click="editEvent(row)">{{ t('edit') }}</el-button>
                           <el-button type="primary" link @click="deleteEvent(row.category_id)">{{ t('delete') }}</el-button>
                       </template>
                    </el-table-column>

                </el-table>
                <div class="mt-[16px] flex justify-end">
                    <el-pagination v-model:current-page="categoryTable.page" v-model:page-size="categoryTable.limit"
                        layout="total, sizes, prev, pager, next, jumper" :total="categoryTable.total"
                        @size-change="loadCategoryList()" @current-change="loadCategoryList" />
                </div>
            </div>

            <edit ref="editCategoryDialog" @complete="loadCategoryList" />
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref, watch } from 'vue'
import { t } from '@/lang'
import { useDictionary } from '@/app/api/dict'
import { getCategoryList, deleteCategory } from '@/addon/zzhc/api/goods'
import { img } from '@/utils/common'
import { ElMessageBox,FormInstance } from 'element-plus'
import Edit from '@/addon/zzhc/views/goods/components/category-edit.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const pageName = route.meta.title;

let categoryTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam:{
      "category_name":""
    }
})

const searchFormRef = ref<FormInstance>()

// 选中数据
const selectData = ref<any[]>([])

// 字典数据
    

/**
 * 获取项目分类列表
 */
const loadCategoryList = (page: number = 1) => {
    categoryTable.loading = true
    categoryTable.page = page

    getCategoryList({
        page: categoryTable.page,
        limit: categoryTable.limit,
         ...categoryTable.searchParam
    }).then(res => {
        categoryTable.loading = false
        categoryTable.data = res.data.data
        categoryTable.total = res.data.total
    }).catch(() => {
        categoryTable.loading = false
    })
}
loadCategoryList()

const editCategoryDialog: Record<string, any> | null = ref(null)

/**
 * 添加项目分类
 */
const addEvent = () => {
    editCategoryDialog.value.setFormData()
    editCategoryDialog.value.showDialog = true
}

/**
 * 编辑项目分类
 * @param data
 */
const editEvent = (data: any) => {
    editCategoryDialog.value.setFormData(data)
    editCategoryDialog.value.showDialog = true
}

/**
 * 删除项目分类
 */
const deleteEvent = (id: number) => {
    ElMessageBox.confirm(t('categoryDeleteTips'), t('warning'),
        {
            confirmButtonText: t('confirm'),
            cancelButtonText: t('cancel'),
            type: 'warning',
        }
    ).then(() => {
        deleteCategory(id).then(() => {
            loadCategoryList()
        }).catch(() => {
        })
    })
}

    

const resetForm = (formEl: FormInstance | undefined) => {
    if (!formEl) return
    formEl.resetFields()
    loadCategoryList()
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
