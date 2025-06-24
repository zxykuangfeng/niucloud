<template>
    <div class="main-container">
        <div class="detail-head">
            <div class="left" @click="back()">
                <span class="iconfont iconxiangzuojiantou !text-xs"></span>
                <span class="ml-[1px]">{{t('returnToPreviousPage')}}</span>
            </div>
            <span class="adorn">|</span>
            <span class="right">{{ pageName }}</span>
        </div>
        <el-card class="box-card !border-none" shadow="never">
            <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules" class="page-form">
                <el-form-item :label="t('storeId')" prop="store_id">
                    <el-select class="input-width" v-model="formData.store_id" filterable clearable :placeholder="t('storeIdPlaceholder')">
                       <el-option label="请选择" value=""></el-option>
                        <el-option
                            v-for="(item, index) in storeIdList"
                            :key="index"
                            :label="item['store_name']"
                            :value="item['store_id']"
                        />
                    </el-select>
                </el-form-item>
                
				<el-form-item :label="t('memberId')">
				    <el-select v-model="formData.member_id" filterable remote reserve-keyword clearable :placeholder="t('memberIdPlaceholder')" :remote-method="searchMember" :loading="searchLoading" class="input-width">
				        <el-option v-for="item in memberList" :key="item.member_id" :label="item.nickname" :value="item.member_id" />
				    </el-select>
				</el-form-item>
				
				<el-form-item :label="t('staffRole')" prop="staff_role">
					<el-checkbox-group v-model="formData.staff_role">
					    <el-checkbox :label="key" v-for="(item,key) in roleList" :key="key">
							{{ item }}
					    </el-checkbox>
					</el-checkbox-group>
				</el-form-item>
                <el-form-item :label="t('staffHeadimg')" prop="staff_headimg">
					<div>
						<upload-image v-model="formData.staff_headimg" />
						<p class="text-[12px] text-[#a9a9a9]">建议图片尺寸：200*200像素；图片格式：jpg、png、jpeg。</p>
					</div>
                </el-form-item>
                
                <el-form-item :label="t('staffName')" prop="staff_name">
                    <el-input v-model="formData.staff_name" clearable :placeholder="t('staffNamePlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('staffMobile')" prop="staff_mobile">
                    <el-input v-model="formData.staff_mobile" clearable :placeholder="t('staffMobilePlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('staffPosition')" prop="staff_position">
                    <el-input v-model="formData.staff_position" clearable :placeholder="t('staffPositionPlaceholder')" class="input-width" />
                </el-form-item>
                
                <el-form-item :label="t('staffExperience')" prop="staff_experience">
                    <el-input-number v-model="formData.staff_experience" clearable :placeholder="t('staffExperiencePlaceholder')" class="input-width" in = "0">
						<template #append>{{ t('年') }}</template>
					</el-input-number>
                </el-form-item>
                
                <el-form-item :label="t('staffImage')" prop="staff_image">
					<div>
						<upload-image v-model="formData.staff_image" />
						<p class="text-[12px] text-[#a9a9a9]">建议图片尺寸：750*750像素；图片格式：jpg、png、jpeg。</p>
					</div>
                </el-form-item>
                
                <el-form-item :label="t('staffContent')" prop="staff_content">
                    <editor v-model="formData.staff_content" />
                </el-form-item>
                <el-form-item :label="t('sort')" prop="sort">
					<el-input-number v-model="formData.sort" clearable :placeholder="t('sortPlaceholder')"  class="input-width"  :min = "0" />
                </el-form-item>
				<el-form-item :label="t('status')" prop="status">
					<el-radio-group v-model="formData.status">
					    <el-radio :label="'normal'">在职</el-radio>
					    <el-radio :label="'quit'">离职</el-radio>
					</el-radio-group>
				</el-form-item>
                
            </el-form>
        </el-card>
         <div class="fixed-footer-wrap">
            <div class="fixed-footer">
                <el-button type="primary" @click="onSave(formRef)">{{ t('save') }}</el-button>
                <el-button @click="back()">{{ t('cancel') }}</el-button>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, reactive, computed, watch } from 'vue'
import { t } from '@/lang'
import type { FormInstance } from 'element-plus'
import { getStaffInfo,addStaff,editStaff,getStaffRole } from '@/addon/zzhc/api/staff';
import { getMemberList } from '@/app/api/member'
import { getStoreAll } from '@/addon/zzhc/api/store'
import { useRoute } from 'vue-router'

const route = useRoute()
const id:number = parseInt(route.query.id);
const loading = ref(false)
const pageName = route.meta.title



/**
 * 表单数据
 */
const initialFormData = {
    staff_id: 0,
    store_id: '',
    member_id: null,
	staff_role:[],
    staff_headimg: '',
    staff_name: '',
    staff_mobile: '',
    staff_position: '',
    staff_experience: '',
    staff_image: '',
    work_status: '',
    staff_content: '',
    sort: 0,
    status: 'normal',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const setFormData = async (id:number = 0) => {
    Object.assign(formData, initialFormData)
    const data = await (await getStaffInfo(id)).data
	
	if(data['member'] != null){
		searchMember(data['member']['nickname']);
	}
	
    Object.keys(formData).forEach((key: string) => {
        if (data[key] != undefined) formData[key] = data[key]
    })
}
if(id) setFormData(id);

const formRef = ref<FormInstance>()
// 选中数据
const selectData = ref<any[]>([])

// 字典数据
const storeIdList = ref([] as any[])
const setStoreIdList = async () => {
    storeIdList.value = await (await getStoreAll({})).data
}
setStoreIdList()

const roleList = ref([] as any[])
const setRoleList = async () => {
    roleList.value = await (await getStaffRole()).data
}
setRoleList()
	
/**
 * 查询会员信息
 */
const memberList = ref<any>([])
const searchLoading = ref(false)
const searchMember = (query: string) => {
	if (query) {
		searchLoading.value = true
		getMemberList({ keyword: query }).then(res => {
			memberList.value = res.data.data
			searchLoading.value = false
		}).catch()
	} else {
		memberList.value = []
		searchLoading.value = false
	}
}
	
// 表单验证规则
const formRules = computed(() => {
    return {
		store_id: [
			{ required: true, message: t('storeIdPlaceholder'), trigger: 'blur' },
		],
		member_id: [
			{ required: true, message: t('memberIdPlaceholder'), trigger: 'blur' },
		],
		staff_role: [
			{ required: true, message: t('staffRolePlaceholder'), trigger: 'blur' },
		],
		staff_headimg: [
			{ required: true, message: t('staffHeadimgPlaceholder'), trigger: 'blur' },
		],
		staff_name: [
			{ required: true, message: t('staffNamePlaceholder'), trigger: 'blur' },
		],
		staff_mobile: [
			{ required: true, message: t('staffMobilePlaceholder'), trigger: 'blur' },
		],
		staff_position: [
			{ required: true, message: t('staffPositionPlaceholder'), trigger: 'blur' },
		],
		staff_experience: [
			{ required: true, message: t('staffExperiencePlaceholder'), trigger: 'blur' },
		],
		staff_image: [
			{ required: true, message: t('staffImagePlaceholder'), trigger: 'blur' },
		],
		work_status: [
			{ required: true, message: t('workStatusPlaceholder'), trigger: 'blur' },
		],
		staff_content: [
			{ required: true, message: t('staffContentPlaceholder'), trigger: 'blur' },
		],
		sort: [
			{ required: true, message: t('sortPlaceholder'), trigger: 'blur' },
		],
		status: [
			{ required: true, message: t('statusPlaceholder'), trigger: 'blur' },
		]
    }
})

const onSave = async (formEl: FormInstance | undefined) => {
    if (loading.value || !formEl) return
    await formEl.validate(async (valid) => {
       if (valid) {
           loading.value = true
           let data = formData

           const save = id ? editStaff : addStaff
           save(data).then(res => {
               loading.value = false
               history.back()
           }).catch(err => {
               loading.value = false
           })

       }
    })
}

const back = () => {
    history.back()
}
</script>

<style lang="scss" scoped></style>
