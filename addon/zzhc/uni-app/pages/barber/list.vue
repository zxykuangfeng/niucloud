<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()">
		<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="getBarberListFn">
			<view class="staff-list">
				<view v-for="(item,index) in barberList" :key="index" class="flex bg-white rounded-[20rpx] mt-[30rpx] p-[20rpx] mx-[30rpx]" @click="toDetail(item.staff_id)">
					<view>
						<u--image class="rounded-[10rpx] overflow-hidden" width="175rpx" height="175rpx"
							:src="img(item.staff_headimg)"
							model="aspectFill">
							<template #error>
								<u-icon name="photo" color="var(--primary-color-dark)" size="50"></u-icon>
							</template>
						</u--image>
					</view>
					<view class="ml-[20rpx] w-[100%] pt-[14rpx]">
						<view class="text-[32rpx]">{{item.staff_name}}</view>
						<view class="text-[26rpx] mt-[20rpx]"><text class="text-[var(--primary-color-dark)]">职级：</text>{{item.staff_position}}</view>
						<view class="flex text-[26rpx] mt-[25rpx] leading-[56rpx]">
							<view class="text-[var(--primary-color-dark)]">前面<text class="text-[var(--primary-color)]">{{item.wait_people}}</text>人｜约<text class="text-[#FA8241]">{{item.wait_duration}}</text>分钟</view>
							
							<view class="ml-auto text-[#FA8241] w-[130rpx] h-[56rpx] rounded-[28rpx] bg-[#FFEDE4] text-center" @click.stop="toSelectGoods(item.staff_id)" v-if="item.work != null && (item.work.status == 'working' || item.work.status == 'meal' || item.work.status == 'thing') ">去取号</view>
							
							<view v-else-if="item.work != null && item.work.status == 'stop'" class="ml-auto text-[var(--primary-color-dark)] w-[130rpx] h-[56rpx] rounded-[28rpx] bg-[var(--page-bg-color)] text-center" >停止接单</view>
							
							<view v-else class="ml-auto text-[var(--primary-color-dark)] w-[130rpx] h-[56rpx] rounded-[28rpx] bg-[var(--page-bg-color)] text-center" >休息中</view>
							
						</view>
					</view>
				</view>
			</view>
			
			<mescroll-empty :option="{tip : '暂无发型师'}" v-if="!barberList.length && loading"></mescroll-empty>
		</mescroll-body>
		<tabbar addon="zzhc"/>
	</view>
</template>

<script setup lang="ts">
	import { reactive, ref, onMounted } from 'vue'
	import { onLoad, onShow } from '@dcloudio/uni-app'
	import { t } from '@/locale'
	import { redirect, img } from '@/utils/common';
	import { getBarberList } from '@/addon/zzhc/api/staff';
	import MescrollBody from '@/components/mescroll/mescroll-body/mescroll-body.vue';
	import MescrollEmpty from '@/components/mescroll/mescroll-empty/mescroll-empty.vue';
	import useMescroll from '@/components/mescroll/hooks/useMescroll.js';
	import { onPageScroll, onReachBottom } from '@dcloudio/uni-app';
	import { currStoreId,distanceFormat } from '@/addon/zzhc/utils/common';
    const { mescrollInit, downCallback, getMescroll } = useMescroll(onPageScroll, onReachBottom);

	let barberList = ref<Array<any>>([]);
	let mescrollRef = ref(null);
	let loading = ref<boolean>(false);
	
	onShow(() => {
		if(currStoreId() == 0){
			redirect({ url: '/addon/zzhc/pages/store/list', mode: 'navigateTo' });
		}
	})

	interface mescrollStructure {
		num : number,
		size : number,
		endSuccess : Function,
		[propName : string] : any
	}
	
	const getBarberListFn = (mescroll : mescrollStructure) => {
		loading.value = false;
		let data : object = {
			page: mescroll.num,
			limit: mescroll.size,
			store_id: currStoreId()
		};

		getBarberList(data).then((res) => {
			let newArr = (res.data.data as Array<Object>);
			//设置列表数据
			if (mescroll.num == 1) {
				barberList.value = []; //如果是第一页需手动制空列表
			}
			barberList.value = barberList.value.concat(newArr);
			mescroll.endSuccess(newArr.length);
			loading.value = true;
		}).catch(() => {
			loading.value = true;
			mescroll.endErr(); // 请求失败, 结束加载
		})
	}
	
	const toDetail = (staff_id:number) => {
		redirect({ url: '/addon/zzhc/pages/barber/detail?staff_id='+staff_id, mode: 'navigateTo' })
	}
	
	const toSelectGoods = (staff_id:number) => {
		redirect({ url: '/addon/zzhc/pages/goods/select?staff_id='+staff_id, mode: 'navigateTo' })
	}

	onMounted(() => {
		setTimeout(() => {
			getMescroll().optUp.textNoMore = t("end");
		}, 500)
	});
</script>

<style >
	@import '@/addon/zzhc/styles/common.scss';
</style>