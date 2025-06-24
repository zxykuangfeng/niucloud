<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh] p-[30rpx]" :style="themeColor()">
		
		<view v-if="!loading">
			<view class="bg-white rounded-[20rpx] p-[30rpx] pr-0 flex">
				<view class="flex">
					<image :src="img(info.staff_headimg)" class="w-[140rpx] h-[140rpx] rounded-[10rpx]" />
				</view>
				<view class="ml-[10rpx]">
					<view class="font-bold">{{info.staff_name}}</view>
					<view class="text-[var(--primary-color-dark)] text-[26rpx] mt-[15rpx]">{{info.staff_position}}</view>
					<view class="text-[var(--primary-color-dark)] text-[26rpx] mt-[20rpx]">{{store.store_name}}</view>
				</view>
			</view>
			
			
			<view class="bg-white p-[30rpx] rounded-[20rpx] mt-[30rpx]">
				<view class="flex">
					<text class="font-bold text-[30rpx]">数据概况</text>
				</view>
				<view class="text-center mt-[40rpx]">
					<view class="text-[var(--primary-color-dark)]">总收入</view>
					<view class="text-[var(--price-text-color)] font-bold text-[40rpx] mt-[20rpx]">¥{{orderMoney.all}}</view>
				</view>
				<view class="flex text-center mt-[50rpx]">
					<view class="w-[210rpx]">
						<view>¥{{orderMoney.today}}</view>
						<view class="text-[var(--primary-color-dark)]">今日收入</view>
					</view>
					<view class="w-[210rpx]">
						<view>¥{{orderMoney.month}}</view>
						<view class="text-[var(--primary-color-dark)]">本月收入</view>
					</view>
					<view class="w-[210rpx]">
						<view>¥{{orderMoney.year}}</view>
						<view class="text-[var(--primary-color-dark)]">今年收入</view>
					</view>
				</view>
			</view>
			
			<view class="bg-white p-[30rpx] rounded-[20rpx] mt-[30rpx]">
				<view class="flex" @click="redirect({ url: '/addon/zzhc/pages/merchant/manage/order/list'})">
					<text class="font-bold text-[30rpx]">订单数据</text>
					<text class="ml-auto"><u-icon name="arrow-right" color="var(--primary-color-dark)" label="查看" labelSize="13" labelPos="left" size="16"></u-icon></text>
				</view>
				<view class="flex text-center mt-[55rpx]">
					
					<view class="w-[210rpx]" @click="toOrder(3)">
						<view class="relative">
							<image :src="img('addon/zzhc/merchant/wait_pay.png')" class="w-[50rpx] h-[50rpx]" />
							<view class="w-[34rpx] h-[34rpx] rounded-[17rpx] leading-[34rpx] absolute top-[-20rpx] right-[50rpx] bg-[var(--primary-color)] text-[20rpx] text-white">{{orderCount.wait_pay_count}}</view>
						</view>
						<view>待付款</view>
					</view>
					
					<view class="w-[210rpx]" @click="toOrder(1)">
						<view class="relative">
							<image :src="img('addon/zzhc/merchant/wait_service.png')" class="w-[50rpx] h-[50rpx]" />
							<view class="w-[34rpx] h-[34rpx] rounded-[17rpx] leading-[34rpx] absolute top-[-20rpx] right-[50rpx] bg-[var(--primary-color)] text-[20rpx] text-white">{{orderCount.wait_service_count}}</view>
						</view>
						<view>待服务</view>
					</view>
					
					<view class="w-[210rpx]" @click="toOrder(2)">
						<view class="relative">
							<image :src="img('addon/zzhc/merchant/in_service.png')" class="w-[50rpx] h-[50rpx]" />
							<view class="w-[34rpx] h-[34rpx] rounded-[17rpx] leading-[34rpx] absolute top-[-20rpx] right-[50rpx] bg-[var(--primary-color)] text-[20rpx] text-white">{{orderCount.in_service_count}}</view>
						</view>
						<view>服务中</view>
					</view>
					
				</view>
			</view>
			
			<view class="bg-white p-[30rpx] rounded-[20rpx] mt-[30rpx]">
				<view class="flex">
					<text class="font-bold text-[30rpx]">功能选项</text>
				</view>
				<view class="flex text-center mt-[40rpx]">
					<view class="w-[315rpx]" @click="redirect({ url: '/addon/zzhc/pages/merchant/manage/order/list'})">
						<view><image :src="img('addon/zzhc/merchant/menu4.png')" class="w-[90rpx] h-[90rpx] rounded-[32rpx]" /></view>
						<view>预约订单</view>
					</view>
					
					
					<view class="w-[315rpx]" @click="redirect({ url: '/addon/zzhc/pages/merchant/manage/work/list'})">
						<view><image :src="img('addon/zzhc/merchant/menu2.png')" class="w-[90rpx] h-[90rpx] rounded-[32rpx]" /></view>
						<view>员工考勤</view>
					</view>
					
				</view>
			</view>
			
		</view>
		<u-loading-page bg-color="var(--page-bg-color)" :loading="loading" fontSize="16" color="var(--primary-color-dark)"></u-loading-page>
		
	</view>
</template>

<script setup lang="ts">
	import { reactive, ref, watch } from 'vue'
	import { img, redirect } from '@/utils/common'
	import { onLoad,onShow } from '@dcloudio/uni-app'
	import { getManageInfo } from '@/addon/zzhc/api/merchant'
	import { currStoreId } from '@/addon/zzhc/utils/common';

	let info = ref({});
	let store = ref({});
	let orderCount = ref({});
	let orderMoney = ref({});
	let loading = ref(true);
	let showAddWorkPopup = ref(false);
	let storeId = ref(0);
	
	// 考勤状态
	let workStatusList = ref([
		{ name: '上班中', value: 'working',icon:'zzhc icon-shangban' },
		{ name: '用餐中', value: 'meal',icon:'zzhc icon-yongcan' },
		{ name: '处理事情中', value: 'thing',icon:'zzhc icon-shimang' },
		{ name: '停止接单', value: 'stop',icon:'zzhc icon-tingzhi' },
		{ name: '下班休息', value: 'rest',icon:'zzhc icon-xiaban' },
	])
	// 当前考勤
	let workStatusIndex = ref(-1);
	let duration = ref(0); //预计用时
	let full_address = ref('');
	let latitude = ref('');
	let longitude = ref('');
	let isLoc = ref(true);
	
	
	onLoad(() => {
		loading.value = true;
		storeId.value = currStoreId();
		getManageInfoFn();
	})
	
	
	const getManageInfoFn = () => {
		getManageInfo(storeId.value).then((res) => {
			loading.value = false;
			info.value = res.data.info;
			store.value = res.data.store;
			orderCount.value = res.data.order_count;
			orderMoney.value = res.data.order_money;
			
		}).catch((res)=>{
			console.log('res',res);
			uni.showModal({
				title: '温馨提示',
				content: res.msg,
				confirmText:'切换门店',
				cancelText:'返回',
				success: res => {
					if (res.confirm) {
						redirect({ url: '/addon/zzhc/pages/store/list' })
					}else{
						uni.navigateBack({
							delta: 1
						});
					}
				}
			})
			
		});
	}
	
	const toOrder = (status:number) => {
		redirect({ url: '/addon/zzhc/pages/merchant/manage/order/list', param: { status } })
	}
	
</script>
<style>
	@import '@/addon/zzhc/styles/common.scss';
</style>