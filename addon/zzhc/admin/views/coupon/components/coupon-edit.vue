<template>
    <el-dialog v-model="showDialog" :title="formData.coupon_id ? t('updateCoupon') : t('addCoupon')" width="50%" class="diy-dialog-wrap" :destroy-on-close="true">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" class="page-form" v-loading="loading">
               
                <el-form-item :label="t('name')" prop="name">
                    <el-input v-model="formData.name" clearable :placeholder="t('namePlaceholder')" class="input-width" />
                </el-form-item>
				
				<el-form-item :label="t('money')" prop="money">
					
					<el-input type="number" οninput="value=value.replace(/[^\d.]/g,'')" v-model="formData.money" clearable
						:placeholder="t('moneyPlaceholder')" class="input-width" maxlength="60">
						<template #append>元</template>
					</el-input>
					
				</el-form-item>
                
                <el-form-item :label="t('atleast')" prop="atleast">
					<el-input type="number" οninput="value=value.replace(/[^\d.]/g,'')" v-model="formData.atleast" clearable
						:placeholder="t('atleastPlaceholder')" class="input-width" maxlength="60">
						<template #append>元</template>
					</el-input>
                </el-form-item>
               
				<el-form-item :label="t('validityType')" prop="validity_type">
					<el-radio-group v-model="formData.validity_type">
						<el-radio :label="0">天数</el-radio>
						<el-radio :label="1">固定时间</el-radio>
						<el-radio :label="2">长期有效</el-radio>
					</el-radio-group>
				</el-form-item>
				
				<el-form-item v-show="formData.validity_type == 0" prop="fixed_term">
					领劵后
					<div class="flex items-center px-[10px]">
						<el-input type="number" v-model="formData.fixed_term" clearable class="!w-[100px]" />
					</div>
					天过期
				</el-form-item>
				
				<el-form-item prop="end_usetime" v-show="formData.validity_type == 1">
					领劵后，截止至
					<div class="px-[10px]">
						<el-date-picker v-model="formData.end_usetime" type="datetime" />
					</div>
					过期
				</el-form-item>
				
				<el-form-item :label="t('isShow')" prop="is_show">
					<div>
						<el-radio-group v-model="formData.is_show">
							<el-radio :label="1">是</el-radio>
							<el-radio :label="0">否</el-radio>
						</el-radio-group>
					</div>
					<div class="form-tip">开启允许领取后，会员可以直接在优惠券列表中领取</div>
				</el-form-item>
				
				<el-form-item :label="t('领取时间')" v-show="formData.is_show == 1" prop="is_show">
					<el-radio-group v-model="formData.receive_time_type">
						<el-radio :label="1">限时</el-radio>
						<el-radio :label="2">不限时</el-radio>
					</el-radio-group>
				</el-form-item>
				
				<el-form-item prop="receive_time" v-show="formData.is_show == 1 && formData.receive_time_type == 1">
					<div class="w-[180px]">
						<el-date-picker v-model="formData.receive_time" type="datetimerange" range-separator="至"
							start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
					</div>
				</el-form-item>
				
				<el-form-item :label="t('count')" prop="count" v-show="formData.is_show == 1">
					<el-input-number v-model="formData.count" clearable :placeholder="t('countPlaceholder')"  class="input-width"  :min = "0" />
				</el-form-item>
                
                <el-form-item :label="t('maxFetch')" prop="max_fetch" v-show="formData.is_show == 1">
					<el-input-number v-model="formData.max_fetch" clearable :placeholder="t('maxFetchPlaceholder')"  class="input-width"  :min = "0" />
                </el-form-item>
                
                <el-form-item :label="t('sort')" prop="sort">
					<el-input-number v-model="formData.sort" clearable :placeholder="t('sortPlaceholder')"  class="input-width"  :min = "0" />
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
import { addCoupon, editCoupon, getCouponInfo } from '@/addon/zzhc/api/coupon'

let showDialog = ref(false)
const loading = ref(false)
const start = new Date()
const end = new Date()
end.setTime(end.getTime() + 3600 * 1000 * 2 * 360) // 设置结束默认时间为当前时间30天后

/**
 * 表单数据
 */
const initialFormData = {
    coupon_id: '',
    name: '',
    count: '',
    money: '',
    atleast: '',
    is_show: 1,
	receive_time_type:2,
	receive_time: [start, end],
    validity_type: 0,
    end_usetime: '',
    fixed_term: 30,
    max_fetch: 1,
    sort: 0,
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
		type: [
			{ required: true, message: t('typePlaceholder'), trigger: 'blur' },
		],
		name: [
			{ required: true, message: t('namePlaceholder'), trigger: 'blur' },
		],
		count: [
			{ required: true, message: t('countPlaceholder'), trigger: 'blur' },
		],
		money: [
			{ required: true, message: t('moneyPlaceholder'), trigger: 'blur' },
		],
		atleast: [
			{ required: true, message: t('atleastPlaceholder'), trigger: 'blur' },
		],
		is_show: [
			{ required: true, message: t('isShowPlaceholder'), trigger: 'blur' },
		],
		discount: [
			{ required: true, message: t('discountPlaceholder'), trigger: 'blur' },
		],
		validity_type: [
			{ required: true, message: t('validityTypePlaceholder'), trigger: 'blur' },
		],
		receive_time: [
			{ required: true, message: t('validityTimePlaceholder'), trigger: 'blur' },
		],

		fixed_term: [
			{ required: true, message: t('fixedTermPlaceholder'), trigger: 'blur' },
		],
		max_fetch: [
			{ required: true, message: t('maxFetchPlaceholder'), trigger: 'blur' },
		],
		sort: [
			{ required: true, message: t('sortPlaceholder'), trigger: 'blur' },
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
    let save = formData.coupon_id ? editCoupon : addCoupon

    await formEl.validate(async (valid) => {
		
		console.log('valid',valid);
		
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

    
const setFormData = async (row: any = null) => {
    Object.assign(formData, initialFormData)
    loading.value = true
    if(row){
        const data = await (await getCouponInfo(row.coupon_id)).data
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
