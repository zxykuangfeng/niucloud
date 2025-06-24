<template>
    <el-dialog v-model="showDialog" :title="formData.id ? t('updateLog') : t('addLog')" width="50%" class="diy-dialog-wrap" :destroy-on-close="true">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" class="page-form" v-loading="loading">
                <el-form-item :label="t('siteId')" prop="site_id">
                    <el-input v-model="formData.site_id" clearable :placeholder="t('siteIdPlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('vipMemberId')" prop="vip_member_id">
                    <el-input v-model="formData.vip_member_id" clearable :placeholder="t('vipMemberIdPlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('mainType')" prop="main_type">
                    <el-input v-model="formData.main_type" clearable :placeholder="t('mainTypePlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('mainId')" prop="main_id">
                    <el-input v-model="formData.main_id" clearable :placeholder="t('mainIdPlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('days')" >
                    <el-input v-model="formData.days" clearable :placeholder="t('daysPlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('type')" prop="type">
                    <el-input v-model="formData.type" clearable :placeholder="t('typePlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('content')"  class="input-width">
                    <el-date-picker 
                        class="flex-1 !flex"
                        v-model="formData.content"
                        clearable
                        type="datetime"
                        value-format="YYYY-MM-DD HH:mm:ss"
                        :placeholder="t('contentPlaceholder')">
                    </el-date-picker>
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
import { addLog, editLog, getLogInfo } from '@/addon/zzhc/api/vip'

let showDialog = ref(false)
const loading = ref(false)

/**
 * 表单数据
 */
const initialFormData = {
    id: '',
    vip_member_id: '',
    main_type: '',
    main_id: '',
    days: '',
    type: '',
    content: '',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
    vip_member_id: [
        { required: true, message: t('vipMemberIdPlaceholder'), trigger: 'blur' },
        
    ]
,
    main_type: [
        { required: true, message: t('mainTypePlaceholder'), trigger: 'blur' },
        
    ]
,
    main_id: [
        { required: true, message: t('mainIdPlaceholder'), trigger: 'blur' },
        
    ]
,
    days: [
        { required: true, message: t('daysPlaceholder'), trigger: 'blur' },
        
    ]
,
    type: [
        { required: true, message: t('typePlaceholder'), trigger: 'blur' },
        
    ]
,
    content: [
        { required: true, message: t('contentPlaceholder'), trigger: 'blur' },
        
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
    let save = formData.id ? editLog : addLog

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
        const data = await (await getLogInfo(row.id)).data
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
</style>
