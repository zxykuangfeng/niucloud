<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()">
		<block v-if="!loading">
			<view class="photo relative">
				<swiper class="w-[750rpx] h-[650rpx] overflow-hidden" @change="photoChange">
					<swiper-item v-for="(image, index) in barberDetail.staff_image_arr" :key="index">
						<image :src="img(image)" class="w-[750rpx] h-[750rpx]" />
					</swiper-item>
				</swiper>
				<view
					class="absolute top-[40rpx] rounded-[10rpx] text-[28rpx] py-[5rpx] px-[15rpx] right-[40rpx] text-[#ffffff] bg-[#000000] opacity-85">
					<text>{{photoIndex}}</text>/<text>{{barberDetail.staff_image_arr.length}}</text>
				</view>
			</view>
			
			<view class="info p-[30rpx] box-border w-[690rpx] mx-[auto] border-[1rpx] border-[#fff] border-solid rounded-md relative z-100 mt-[-100rpx] bg-white">
				<view class="flex">
					<view class="leading-[150rpx] h-[150rpx]">
						<image :src="img(barberDetail.staff_headimg)" class="w-[150rpx] h-[150rpx] rounded-[75rpx] " />
					</view>
					<view class="ml-[20rpx]">
						<view class="text-[32rpx] mt-[10rpx]">{{barberDetail.staff_name}}</view>
						<view class="text-[26rpx] mt-[20rpx]"><text class="text-[var(--primary-color-dark)]">职级：</text>{{barberDetail.staff_position}}</view>
						<view class="flex text-[26rpx] mt-[10rpx] leading-[56rpx]">
							<view class="text-[var(--primary-color-dark)]">
								已服务<text class="text-[var(--primary-color)]">{{barberDetail.finish_order}}</text>单
							</view>
						</view>
					</view>
				</view>
			</view>
			
			<view class="p-[30rpx] mt-[30rpx] bg-white mx-[30rpx] rounded-md">
				<view class="font-bold mb-[30rpx]">个人介绍</view>
				<u-parse :content="barberDetail.staff_content" :tagStyle="style"></u-parse>
			</view>
			
			<view class="common-tab-bar-placeholder"></view>
			<view class="common-tab-bar bg-white fixed left-0 right-0 bottom-[0] flex py-[20rpx]">
				<button :loading="loading" class="bg-[#FA8241] text-[#fff] h-[80rpx] w-[260rpx] leading-[80rpx] rounded-[100rpx] text-[26rpx] font-500" @click="callPhone(barberDetail.staff_mobile)">电话咨询</button>
				<button :loading="loading" class="bg-[var(--primary-color)] text-[#fff] w-[260rpx] h-[80rpx] leading-[80rpx] rounded-[100rpx] text-[26rpx] font-500" @click="toSelectGoods()" v-if="barberDetail.work != null && (barberDetail.work.status == 'working' || barberDetail.work.status == 'meal' || barberDetail.work.status == 'thing') ">去取号</button>
				<button v-else-if="barberDetail.work != null && barberDetail.work.status == 'stop'" class="bg-[var(--page-bg-color)] text-[var(--primary-color-dark)] w-[260rpx] h-[80rpx] leading-[80rpx] rounded-[100rpx] text-[26rpx] font-500" >停止接单</button>
				
				<button v-else class="bg-[var(--page-bg-color)] text-[var(--primary-color-dark)] w-[260rpx] h-[80rpx] leading-[80rpx] rounded-[100rpx] text-[26rpx] font-500" >休息中</button>
				
			</view>
		</block>
		<u-loading-page :loading="loading" fontSize="16" color="#333"></u-loading-page>
	</view>
</template>

<script setup lang="ts">
	import { reactive, ref } from 'vue'
	import { onLoad } from '@dcloudio/uni-app'
	import { redirect, img} from '@/utils/common';
	import { getBarberDetail } from '@/addon/zzhc/api/staff';
	import { useShare } from '@/hooks/useShare'
	import { callPhone } from '@/addon/zzhc/utils/common';
	const { setShare, onShareAppMessage, onShareTimeline } = useShare()
	onShareAppMessage()
	onShareTimeline()
	
	let photoIndex = ref(1);
	let barberDetail = ref<Array<any>>([]);
	let loading = ref<boolean>(true);
	let style = {
		h2: 'margin-bottom: 20rpx;',
		p: 'margin-bottom: 10rpx;line-height: 1.5;',
		img: 'margin: 20rpx 0;',
	};
	onLoad((option) => {
		loading.value = true;
		getBarberDetail(option.staff_id).then((res) => {
			barberDetail.value = res.data;
			loading.value = false;
			let share = {
				title: barberDetail.value.staff_name,
				desc: barberDetail.value.staff_name,
				url: barberDetail.value.staff_headimg
			}
            uni.setNavigationBarTitle({
                title: barberDetail.value.staff_name
            })
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
	
	const photoChange = (e : any) => {
		photoIndex.value = e.detail.current + 1;
	}
	
	const toSelectGoods = () => {
		redirect({ url: '/addon/zzhc/pages/goods/select?staff_id='+barberDetail.value.staff_id, mode: 'navigateTo' })
	}
</script>
<style>
	@import '@/addon/zzhc/styles/common.scss';
</style>