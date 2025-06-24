<template>
    <el-dialog v-model="showDialog" :title="formData.id ? t('updateCategory') : t('addCategory')" width="50%" class="diy-dialog-wrap" :destroy-on-close="true">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" class="page-form" v-loading="loading">
                <el-form-item :label="t('categoryName')" prop="category_name">
                    <el-input v-model="formData.category_name" clearable :placeholder="t('categoryNamePlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('categoryImage')" >
                    <div>
                    	<upload-image v-model="formData.category_image" />
                    	<p class="text-[12px] text-[#a9a9a9]">建议图片尺寸：200*200像素；图片格式：jpg、png、jpeg。</p>
                    </div>
                </el-form-item>
                
                <el-form-item :label="t('sort')" prop="sort">
                     <el-input-number v-model="formData.sort" clearable :placeholder="t('sortPlaceholder')"  class="input-width"  :min = "0" />
                </el-form-item>
                
                <el-form-item :label="t('status')" prop="status">
                	<el-radio-group v-model="formData.status">
                	    <el-radio :label="'normal'">显示</el-radio>
                	    <el-radio :label="'hidden'">隐藏</el-radio>
                	</el-radio-group>
                </el-form-item>
                
        </el-form>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="showDialog = false">{{ t('cancel') }}</el-button>
                <el-button type="primary" :loading="loading" @click="confirm(formRef)">{{
                    t('confirm')
                }}</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive, computed, watch } from 'vue'
import { useDictionary } from '@/app/api/dict'
import { t } from '@/lang'
import type { FormInstance } from 'element-plus'
import { addCategory, editCategory, getCategoryInfo } from '@/addon/zzhc/api/goods'

let showDialog = ref(false)
const loading = ref(false)

/**
 * 表单数据
 */
const initialFormData = {
    category_id: '',
    category_name: '',
    category_image: '',
    sort: '0',
    status: 'normal',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
		category_name: [
			{ required: true, message: t('categoryNamePlaceholder'), trigger: 'blur' },
		],
		category_image: [
			{ required: true, message: t('categoryImagePlaceholder'), trigger: 'blur' },
		],
		sort: [
			{ required: true, message: t('sortPlaceholder'), trigger: 'blur' },
		],
		status: [
			{ required: true, message: t('statusPlaceholder'), trigger: 'blur' },
		]
    }
})

const emit = defineEmits(['complete'])

/**
 * 确认
 * @param formEl
 */
const confirm = async (formEl: FormInstance | undefined) => {
    if (loading.value || !formEl) return
    let save = formData.category_id ? editCategory : addCategory

    await formEl.validate(async (valid) => {
        if (valid) {
            loading.value = true

            let data = formData

            save(data).then(res => {
                loading.value = false
                showDialog.value = false
                emit('complete')
            }).catch(err => {
                loading.value = false
            })
        }
    })
}

// 获取字典数据
    

    
const setFormData = async (row: any = null) => {
    Object.assign(formData, initialFormData)
    loading.value = true
    if(row){
        const data = await (await getCategoryInfo(row.category_id)).data
        if (data) Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
    }
    loading.value = false
}

defineExpose({
    showDialog,
    setFormData
})
</script>

<style lang="scss" scoped></style>
<style lang="scss">
.diy-dialog-wrap .el-form-item__label{
    height: auto  !important;
}
</style>
