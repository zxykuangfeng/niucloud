<template>
    <el-dialog v-model="showDialog" :title="formData.goods_id ? t('updateGoods') : t('addGoods')" width="50%" class="diy-dialog-wrap" :destroy-on-close="true">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" class="page-form" v-loading="loading">
                
                <el-form-item :label="t('categoryId')" prop="category_id">
                    <el-select class="input-width" v-model="formData.category_id" clearable :placeholder="t('categoryIdPlaceholder')">
                       <el-option label="请选择" value=""></el-option>
                        <el-option
                            v-for="(item, index) in categoryIdList"
                            :key="index"
                            :label="item['category_name']"
                            :value="item['category_id']"
                        />
                    </el-select>
                </el-form-item>
                
                <el-form-item :label="t('goodsName')" prop="goods_name">
                    <el-input v-model="formData.goods_name" clearable :placeholder="t('goodsNamePlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('goodsImage')" prop="goods_image">
					<div>
						<upload-image v-model="formData.goods_image" />
						<p class="text-[12px] text-[#a9a9a9]">建议图片尺寸：200*200像素；图片格式：jpg、png、jpeg。</p>
					</div>
                </el-form-item>
                
                <el-form-item :label="t('duration')" prop="duration">
					<div>
						<el-input-number v-model="formData.duration" clearable :placeholder="t('durationPlaceholder')"  class="input-width"  :min = "0" />
						<p class="text-[12px] text-[#a9a9a9]">服务预计时长，单位分钟。</p>
					</div>
                    
                </el-form-item>
				
				<el-form-item :label="t('price')" prop="price">
					<el-input v-model="formData.price" clearable :placeholder="t('pricePlaceholder')" class="input-width" maxlength="8" >
						<template #append>{{ t('元') }}</template>
					</el-input>
				</el-form-item>
                
                <el-form-item :label="t('sort')" prop="sort">
                    <el-input-number v-model="formData.sort" clearable :placeholder="t('sortPlaceholder')"  class="input-width"  :min = "0" />
                </el-form-item>
                
                <el-form-item :label="t('status')" prop="status">
                	<el-radio-group v-model="formData.status">
                	    <el-radio :label="'up'">上架</el-radio>
                	    <el-radio :label="'down'">下架</el-radio>
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
import { t } from '@/lang'
import type { FormInstance } from 'element-plus'
import { addGoods, editGoods, getGoodsInfo, getCategoryAll } from '@/addon/zzhc/api/goods'

let showDialog = ref(false)
const loading = ref(false)

/**
 * 表单数据
 */
const initialFormData = {
    goods_id: '',
    store_ids: '',
    category_id: '',
    goods_name: '',
    goods_image: '',
    duration: null,
    price: '',
    sort: '',
    status: 'up',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
		category_id: [
			{ required: true, message: t('categoryIdPlaceholder'), trigger: 'blur' },
		],
		goods_name: [
			{ required: true, message: t('goodsNamePlaceholder'), trigger: 'blur' },
		],
		goods_image: [
			{ required: true, message: t('goodsImagePlaceholder'), trigger: 'blur' },
		],
		duration: [
			{ required: true, message: t('durationPlaceholder'), trigger: 'blur' },
		],
		price: [
			{
				required: true,
				trigger: 'blur',
				validator: (rule: any, value: any, callback: any) => {
					if (isNaN(value) || !regExp.digit.test(value) || value <= 0) {
						callback(new Error(t('价格输入错误')))
					} else {
						callback()
					}
				}
			}
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
    let save = formData.goods_id ? editGoods : addGoods

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
const categoryIdList = ref([] as any[])
const setCategoryIdList = async () => {
    categoryIdList.value = await (await getCategoryAll()).data
}
setCategoryIdList()

const setFormData = async (row: any = null) => {
    Object.assign(formData, initialFormData)
    loading.value = true
    if(row){
        const data = await (await getGoodsInfo(row.goods_id)).data
        if (data) Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
    }
    loading.value = false
}

// 正则表达式
const regExp = {
	number: /^\d{0,10}(.?\d{0,1})$/,
	digit: /^\d{0,10}(.?\d{0,2})$/
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
