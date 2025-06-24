<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()" v-if="loading">
		<view class="bg-white p-[30rpx] flex">
			<image :src="img(config.banner)" class="w-[690rpx] h-[330rpx] rounded-[20rpx]"  />
			
			<view>
				<view></view>
			</view>
		</view>
		<view class="p-[30rpx] mt-[20rpx] bg-white">
			<view class="font-bold mb-[20rpx]">会员卡套餐</view>
			<view class="flex">
				<view v-for="(item,index) in vipList" :key="index" class="border-[4rpx] border-solid w-[202rpx] text-center mr-[30rpx] rounded-[20rpx]" :class="vipIndex == index ?'border-primary':'border-[#f5f5f5]'" @click="changeVip(index)">
					<view class="text-[30rpx] font-bold mt-[30rpx]">{{item.vip_name}}</view>
					
					<view class="mt-[30rpx]">
						<text class="text-[24rpx] font-500 text-[var(--price-text-color)] price-font">￥</text>
						<text class="text-[34rpx] mr-[10rpx]  font-500  text-[var(--price-text-color)] price-font">
							{{moneyFormat(item.price)}}
						</text>
					</view>
					<view class="mb-[30rpx] mt-[20rpx]">{{item.days}}天</view>
				</view>
			</view>
		</view>
		<view class="p-[30rpx] mt-[20rpx] bg-white">
			<view class="font-bold mb-[10rpx]">权益说明</view>
			<view>
				{{config.statement}}
			</view>
		</view>
		<u-tabbar :fixed="true" :placeholder="true" :safeAreaInsetBottom="true">
		    <view class="flex-1 flex items-center justify-between">
		        <view class="whitespace-nowrap flex px-[30rpx] text-color font-600 leading-[45rpx]">
					<view class="mr-[30rpx]" >
						<text class="text-[24rpx] font-500 text-[var(--price-text-color)] price-font">￥</text>
						<text class="text-[34rpx] mr-[10rpx]  font-500  text-[var(--price-text-color)] price-font">
							{{moneyFormat(vipList[vipIndex].price)}}
						</text>
						<text class="text-[24rpx] font-500 price-font">{{vipList[vipIndex].days}}天</text>
					</view>
		        </view>
				
		        <button class="!w-[204rpx] !h-[70rpx] text-[32rpx] mr-[30rpx] leading-[70rpx] rounded-full text-white bg-[var(--primary-color)] remove-border" :loading="createLoading" @click="create">立即开通</button>
		    </view>
		</u-tabbar>
		<pay ref="payRef" @close="payClose"></pay>
	</view>
</template>

<script setup lang="ts">
	import { reactive, ref } from 'vue'
	import { onLoad } from '@dcloudio/uni-app'
	import { orderCreate } from '@/addon/zzhc/api/vip';
	import { t } from '@/locale'
	import { redirect, img,moneyFormat} from '@/utils/common';
	import { getVipList,getVipConfig } from '@/addon/zzhc/api/vip';
	const payRef = ref(null)

	let config = ref<Array<any>>([]);
	let vipList = ref<Array<any>>([]);
	let vipIndex = ref(0);
	let loading = ref(false);
	let createLoading = ref(false);
	let orderId = ref<number>(0);
	let tradeType =  ref<string>('');
	
	onLoad(() => {
		getVipConfigFn();
	})
	
	const getVipListFn = () => {
		getVipList().then((res) => {
			vipList.value = res.data;
			loading.value = true;
		});
	}							
	
	const getVipConfigFn = () => {
		getVipConfig().then((res) => {
			config.value = res.data;
			getVipListFn();
		});
	}		
	
	const changeVip = (index: number) => {
		vipIndex.value = index;
		orderId.value = 0;
		tradeType.value = '';
	}
	
	//创建订单
	const create = () => {
		createLoading.value = true
		let data = {vip_id:vipList.value[vipIndex.value]['vip_id']};
		
		if(orderId.value != 0 && tradeType.value != ''){
			payRef.value?.open(tradeType.value, orderId.value,`/addon/zzhc/pages/member/index`);
		}else{
			orderCreate(data).then((res) => {
				orderId.value = res.data.trade_id;
				tradeType.value = res.data.trade_type;
				payRef.value?.open(res.data.trade_type, res.data.trade_id,`/addon/zzhc/pages/member/index`);
			}).catch(() => {
				createLoading.value = false
			})
		}
		
		
	}
	
	/**
	 * 支付弹窗关闭
	 */
	const payClose = () => {
	    createLoading.value = false
	}
	
	
</script>
<style>
	@import '@/addon/zzhc/styles/common.scss';
</style>