<template>
    <el-dialog v-model="showDialog" :title="formData.vip_id ? t('updateVip') : t('addVip')" width="50%" class="diy-dialog-wrap" :destroy-on-close="true">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" class="page-form" v-loading="loading">
                <el-form-item :label="t('vipName')" prop="vip_name">
                    <el-input v-model="formData.vip_name" clearable :placeholder="t('vipNamePlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('days')" prop="days">
                    <el-input-number v-model="formData.days" clearable :placeholder="t('daysPlaceholder')"  class="input-width days-el-input-number" :min = "1" />
                </el-form-item>
				
				<el-form-item :label="t('price')" prop="price">
					<el-input v-model="formData.price" clearable :placeholder="t('pricePlaceholder')" class="input-width" maxlength="8" >
						<template #append>元</template>
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
import { useDictionary } from '@/app/api/dict'
import { t } from '@/lang'
import type { FormInstance } from 'element-plus'
import { addVip, editVip, getVipInfo } from '@/addon/zzhc/api/vip'

let showDialog = ref(false)
const loading = ref(false)

/**
 * 表单数据
 */
const initialFormData = {
    vip_id: '',
    vip_name: '',
    days: null,
    price: '',
    sort: '',
    status: 'up',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
		vip_name: [
			{ required: true, message: t('vipNamePlaceholder'), trigger: 'blur' },
		],
		days: [
			{ required: true, message: t('daysPlaceholder'), trigger: 'blur' },
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
    let save = formData.vip_id ? editVip : addVip

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

// 正则表达式
const regExp = {
	number: /^\d{0,10}(.?\d{0,1})$/,
	digit: /^\d{0,10}(.?\d{0,2})$/
} 

    
const setFormData = async (row: any = null) => {
    Object.assign(formData, initialFormData)
    loading.value = true
    if(row){
        const data = await (await getVipInfo(row.vip_id)).data
        if (data) Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
    }
    loading.value = false
}

// 验证手机号格式
const mobileVerify = (rule: any, value: any, callback: any) => {
    if (value && !/^1[3-9]\d{9}$/.test(value)) {
        callback(new Error(t('generateMobile')))
    } else {
        callback()
    }
}

// 验证身份证号
const idCardVerify = (rule: any, value: any, callback: any) => {
    if (value && !/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(value)) {
        callback(new Error(t('generateIdCard')))
    } else {
        callback()
    }
}

// 验证邮箱号
const emailVerify = (rule: any, value: any, callback: any) => {
    if (value && !/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(value)) {
        callback(new Error(t('generateEmail')))
    } else {
        callback()
    }
}

// 验证请输入整数
const numberVerify = (rule: any, value: any, callback: any) => {
    if (!Number.isInteger(value)) {
        callback(new Error(t('generateNumber')))
    } else {
        callback()
    }
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
	
	.days-el-input-number {
	    position: relative;
	}
	.days-el-input-number::after {
	    content: '天 ';
	    display: inline-block;
	    height: 30px;
	    line-height: 30px;
	    width: 30px;
	    text-align: center;
	    position: absolute;
	    right: 32px;
	    top: 50%;
		background-color: var(--el-fill-color-light);
	    transform: translateY(-50%);
	}
	.days-el-input-number .el-input__inner{ 
	    padding-left: 30px;
	    padding-right: 48px;
	}
</style>
