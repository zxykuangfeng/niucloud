<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()">
		<block v-if="!loading">
			<view class="bg-white p-[30rpx]">
				<view class="flex">
					<view>
						<image class="w-[132rpx] h-[132rpx] rounded-[10rpx]" :src="img(storeDetail.store_logo)" />
					</view>
					<view class="font-bold ml-[20rpx] text-[32rpx] leading-[132rpx]">
						{{storeDetail.store_name}}
					</view>
				</view>
				<view class="text-[var(--primary-color-dark)]">
					<view>营业时间：{{storeDetail.trade_time}}</view>
					<view>联系电话：{{storeDetail.store_mobile}}</view>
					<view>门店地址：{{storeDetail.full_address}}</view>
				</view>
			</view>
			<view class="p-[30rpx] mt-[20rpx] bg-white">
				<view class="font-bold mb-[20rpx]">门店照片</view>
				<view>
					<view class="photo relative">
						<swiper class="w-[690rpx] h-[368rpx] overflow-hidden" @change="photoChange">
							<swiper-item v-for="(photo, index) in storeDetail.store_image_arr" :key="index" @click="imgListPreview(index)">
								<image :src="img(photo)" class="w-[690rpx] h-[368rpx]" />
							</swiper-item>
						</swiper>
						<view class="absolute bottom-[30rpx] rounded-[10rpx] text-[28rpx] py-[5rpx] px-[15rpx] right-[40rpx] text-[#ffffff] bg-[#000000] opacity-85"><text>{{photoIndex}}</text>/<text>{{storeDetail.store_image_arr.length}}</text></view>
					</view>
				</view>
			</view>
			<view class="p-[30rpx] mt-[20rpx] bg-white">
				<view class="font-bold mb-[30rpx]">门店介绍</view>
				<u-parse :content="storeDetail.store_content" :tagStyle="style"></u-parse>
			</view>
			<view class="common-tab-bar-placeholder"></view>
			<view class="common-tab-bar bg-white fixed left-0 right-0 bottom-[0] flex py-[20rpx]">
				<button :loading="loading" class="bg-[#FA8241] text-[#fff] h-[80rpx] w-[260rpx] leading-[80rpx] rounded-[100rpx] text-[26rpx] font-500" @click="callPhone(storeDetail.store_mobile)">电话咨询</button>
				<button :loading="loading" class="bg-[var(--primary-color)] text-[#fff] w-[260rpx] h-[80rpx] leading-[80rpx] rounded-[100rpx] text-[26rpx] font-500" @click="openMap.openMap(storeDetail.latitude,storeDetail.longitude, storeDetail.full_address)">门店导航</button>
			</view>
		</block>
		<u-loading-page :loading="loading" fontSize="16" color="#333"></u-loading-page>
	</view>
</template>

<script setup lang="ts">
	import { reactive, ref } from 'vue'
	import { onLoad } from '@dcloudio/uni-app'
	import { t } from '@/locale'
	import { redirect, img} from '@/utils/common';
	import { getStoreDetail } from '@/addon/zzhc/api/store';
	import { useShare } from '@/hooks/useShare'
	import { callPhone } from '@/addon/zzhc/utils/common';
	import openMap from '@/addon/zzhc/utils/map/openMap.js'

	const { setShare, onShareAppMessage, onShareTimeline } = useShare()
	onShareAppMessage()
	onShareTimeline()

	let photoIndex = ref(1);
	let storeDetail = ref<Array<any>>([]);
	let loading = ref<boolean>(true);
	let style = {
		h2: 'margin-bottom: 20rpx;',
		p: 'margin-bottom: 10rpx;line-height: 1.5;',
		img: 'margin: 20rpx 0;',
	};
	onLoad((option) => {
		loading.value = true;
		getStoreDetail(option.store_id).then((res) => {
			storeDetail.value = res.data;
			loading.value = false;
			let share = {
				title: storeDetail.value.store_name,
				desc: storeDetail.value.store_name,
				url: storeDetail.value.store_logo
			}
			setShare({
				wechat: {
					...share
				},
				weapp: {
					...share
				}
			});
		});
	})
	
	const photoChange = (e: any)=>{
		console.log(e);
		photoIndex.value = e.detail.current+1;
	}
	
	//预览图片
	const imgListPreview = (index:number) => {
		
		const urls = [];
		storeDetail.value.store_image_arr.forEach((item)=>{
			urls.push(img(item));
		})
		uni.previewImage({
			current: index,
			urls: urls,
			loop:true,
		})
	}
	
	
</script>
<style>
	@import '@/addon/zzhc/styles/common.scss';
</style>