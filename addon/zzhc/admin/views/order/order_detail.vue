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
		<el-form :model="formData" label-width="100px" ref="formRef" class="page-form" v-loading="loading" label-position="left">
			<el-card class="box-card !border-none relative" shadow="never" v-if="formData">
				<h3 class="panel-title">{{ t('orderInfo') }}</h3>
				<el-row class="row-bg px-[30px] mb-[20px]" :gutter="20">
					<el-col :span="8">
						<el-form-item :label="t('orderNo')">
							<div class="input-width">{{ formData.order_no }}</div>
						</el-form-item>
						<el-form-item :label="t('orderForm')">
							<div class="input-width">{{ formData.order_from_name }}</div>
						</el-form-item>
						<el-form-item :label="t('outTradeNo')" v-if="formData.out_trade_no">
							<div class="input-width">{{ formData.out_trade_no }}</div>
						</el-form-item>
						<el-form-item :label="t('payType')" v-if="formData.pay">
							<div class="input-width">{{ formData.pay.type_name }}</div>
						</el-form-item>
					</el-col>
					
					<el-col :span="8">
						
						<el-form-item :label="t('会员昵称')">
							<div class="input-width">{{ formData.nickname }}</div>
						</el-form-item>
						<el-form-item :label="t('会员手机号')">
							<div class="input-width">{{ formData.mobile }}</div>
						</el-form-item>
						<el-form-item :label="t('是否VIP')">
							{{formData.is_vip ? '是' : '否'}}
						</el-form-item>
						<el-form-item :label="t('创建时间')">
							<div class="input-width">{{ formData.create_time }}</div>
						</el-form-item>
					</el-col>
					
					
				</el-row>
				<h3 class="panel-title">{{ t('预约信息') }}</h3>
				<el-row class="row-bg px-[30px] mb-[20px]" :gutter="20">
					<el-col >
						<el-form-item :label="t('预约门店')">
							<div class="input-width">{{ formData.store.store_name }}</div>
						</el-form-item>
						<el-form-item :label="t('预约发型师')">
							<div >{{ formData.staff_name}}</div>
						</el-form-item>
						<el-form-item :label="t('预约项目')">
							<div class="input-width">{{ formData.goods_name }}</div>
						</el-form-item>
						
					</el-col>
					
				</el-row>
				
				<h3 class="panel-title">{{ t('orderStatus') }}</h3>
				<div class="mb-[20px]">
					<p>
						<span class="ml-[30px] text-[14px] mr-[20px]">{{ t('orderStatus') }}：</span>
						<span class="text-[14px]">{{ formData.status_name.name }}</span>
					</p>
					<div class="flex mt-[10px]">
						<span class="text-[14px] px-[15px] py-[5px] ml-[30px] text-[#5c96fc] bg-[#ebf3ff] cursor-pointer" @click="cancel" v-if="formData.status == 1">{{ t('取消订单') }}</span>
					</div>
				</div>
				
				<div class="py-[12px] px-[16px] border-b border-color flex justify-end">
					<div class="w-[310px] flex flex-col text-right">
						<div class="flex mb-[10px]">
							<div class="text-base flex-1">{{ t('项目价格') }}</div>
							<div class="text-base flex-1 pl-[30px]">¥{{ formData.goods_money }}</div>
						</div>
						<div class="flex mb-[10px]">
							<div class="text-base flex-1">{{ t('VIP价格') }}</div>
							<div class="text-base flex-1 pl-[30px]">¥{{ formData.goods_vip_money }}</div>
						</div>
						<div class="flex mb-[10px]">
							<div class="text-base flex-1">{{ t('优惠金额') }}</div>
							<div class="text-base flex-1 pl-[30px]">¥{{ formData.discount_money }}</div>
						</div>
						<div class="flex">
							<div class="text-base flex-1">{{ t('订单金额') }}</div>
							<div class="text-base flex-1 pl-[30px] text-[red]">¥{{ formData.order_money }}</div>
						</div>
					</div>
				</div>
			</el-card>
		</el-form>
	</div>
</template>

<script lang="ts" setup>
	import { ref } from 'vue'
	import { t } from '@/lang'
	import { getOrderInfo, orderCancel } from '@/addon/zzhc/api/order'
	import { useRoute, useRouter } from 'vue-router'
	import { ElMessageBox } from 'element-plus'

	const route = useRoute()
	const router = useRouter()
	const pageName = route.meta.title
	const orderId: number = parseInt(route.query.order_id as string)
	const loading = ref(true)

	const formData: Record<string, any> | null = ref(null)

	const setFormData = async (orderId: number = 0) => {
		loading.value = true
		formData.value = null
		await getOrderInfo(orderId)
			.then(({ data }) => {
				formData.value = data
			})
			.catch(() => {

			})
		loading.value = false
	}
	if (orderId) setFormData(orderId)
	else loading.value = false


	const cancel = () => {
		ElMessageBox.confirm(t('确定要取消订单吗？'), t('warning'),
			{
				confirmButtonText: t('confirm'),
				cancelButtonText: t('cancel'),
				type: 'warning'
			}).then(() => {
			orderCancel(orderId).then(() => {
				setFormData(orderId)
			})
		})
	}
	
	const back = () => {
		history.back()
	}

</script>