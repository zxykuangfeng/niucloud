<template>
    <div class="main-container pt-[10px]">
        <div class="flex ml-[18px] justify-between items-center">
            <span class="text-[20px]">{{ pageName }}</span>
        </div>
        <el-form :model="formData" label-width="95" ref="formRef" :rules="rules" class="page-form" v-loading="loading">
			
            <el-card class="box-card !border-none mt-[15px]" shadow="never">
				
				<el-form-item :label="t('isEnable')">
				    <el-radio-group class="mx-[10px]" v-model="formData.is_enable">
				        <el-radio :label="1">{{ t('isEnableOpen') }}</el-radio>
				        <el-radio :label="0">{{ t('isEnableClose') }}</el-radio>
				    </el-radio-group>
				</el-form-item>

				<el-form-item :label="t('discount')" prop="discount" v-if="formData.is_enable == 1">
					<div class="pl-[8px]">
						<el-input-number v-model="formData.discount" clearable :placeholder="t('discountPlaceholder')" class="input-width" />
						<p class="text-[12px] text-[#a9a9a9] leading-normal  mt-[5px]">{{ t('discountTips') }}</p>
					</div>
				</el-form-item>
				<el-form-item :label="t('banner')" prop="banner">
					<div>
						<upload-image v-model="formData.banner" />
						<p class="text-[12px] text-[#a9a9a9]">建议图片尺寸：690*330像素；图片格式：jpg、png、jpeg。</p>
					</div>
				</el-form-item>
				<el-form-item :label="t('statement')" prop="statement" v-if="formData.is_enable == 1">
					<div class="pl-[8px]">
						<el-input type="textarea" v-model="formData.statement" clearable :placeholder="t('statementPlaceholder')" class="input-width" rows="10" />
					</div>
				</el-form-item>
			  
            </el-card>
            
        </el-form>
        <div class="fixed-footer-wrap" v-if="!loading">
            <div class="fixed-footer">
                <el-button type="primary" @click="onSave(formRef)">{{ t('save') }}</el-button>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
import { ref,reactive } from 'vue'
import { t } from '@/lang'
import { getVipConfig, setVipConfig } from '@/addon/zzhc/api/vip'
import { useRoute } from 'vue-router'
import Test from '@/utils/test'

const route = useRoute()
const pageName = route.meta.title
const formData = reactive({
	is_enable:1,
	discount:null,
	banner:'',
	statement:''
})

const rules = ref({
	
    discount: [
        {
            validator: (rule: any, value: any, callback: any) => {
                if (Test.empty(formData.discount)) {
                    callback('请输入折扣')
                }
                if (!Test.decimal(formData.discount, 1)) {
                    callback('折扣格式错误')
                }
                if (parseFloat(formData.discount) < 0.1 || parseFloat(formData.discount) > 9.9) {
                    callback('折扣只能输入0.1~10之间的值')
                }
                if (formData.discount <= 0) {
                    callback('折扣不能小于等于0')
                }
                callback()
            }
        }
    ]
})
const loading = ref(false)
const getVipConfigFn = () => {
    loading.value = true
    getVipConfig().then(res => {
		Object.keys(formData).forEach((key: string) => {
		    if (res.data[key] != undefined) formData[key] = res.data[key]
		})
        loading.value = false
    }).catch(() => {
        loading.value = false
    })
}

getVipConfigFn()
const formRef = ref()

const onSave = async (formEl: any) => {
    await formEl.validate(async (valid:any) => {
        if (valid) {
            loading.value = true
            setVipConfig(formData).then(res => {
                getVipConfigFn()
            }).catch(() => {
                loading.value = false
            })
        }
    })
}
</script>
<style lang="scss" scoped></style>
