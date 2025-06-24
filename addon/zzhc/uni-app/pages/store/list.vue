<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()">
		<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="getStoreListFn">
			<view class="store-list">
				<view v-for="(item,index) in storeList" :key="index" class="flex bg-white rounded-[20rpx] mt-[30rpx] p-[20rpx] mx-[30rpx]" @click="selectStore(item)">
					<view>
						<u--image class="rounded-[10rpx] overflow-hidden" width="155rpx" height="155rpx"
							:src="img(item.store_logo)"
							model="aspectFill">
							<template #error>
								<u-icon name="photo" color="var(--primary-color-dark)" size="50"></u-icon>
							</template>
						</u--image>
					</view>
					<view class="ml-[20rpx] w-[100%] pt-[10rpx]">
						<view class="text-[32rpx] flex">
							<view>{{item.store_name}}</view>
							<view class="ml-auto text-[#FA8241] text-[24rpx] leading-[40rpx] w-[130rpx] h-[40rpx] rounded-[20rpx] bg-[#FFEDE4] text-center"> {{currStoreId() == item.store_id ? '当前门店' : '选择门店'}}</view>
						</view>
						<view class="text-[26rpx] mt-[20rpx] text-[var(--primary-color-dark)] flex">
							<u-icon name="map" color="var(--primary-color-dark)" size="17"></u-icon>
							{{item.address}}
						</view>
						<view class="flex text-[26rpx] mt-[25rpx]">
							<view class="text-[var(--primary-color-dark)]">距离<text class="text-[var(--primary-color)]">{{distanceFormat(item.distance)}}</text></view>
						</view>
					</view>
				</view>
			</view>
			
			<mescroll-empty :option="{tip : '暂无门店'}" v-if="!storeList.length && loading"></mescroll-empty>
		</mescroll-body>
	</view>
</template>

<script setup lang="ts">
	import { reactive, ref, onMounted } from 'vue'
	import { onLoad } from '@dcloudio/uni-app'
	import { t } from '@/locale'
	import { redirect, img } from '@/utils/common';
	import { currStoreId,distanceFormat,getLoc } from '@/addon/zzhc/utils/common';
	import { getStoreList } from '@/addon/zzhc/api/store';
	import MescrollBody from '@/components/mescroll/mescroll-body/mescroll-body.vue';
	import MescrollEmpty from '@/components/mescroll/mescroll-empty/mescroll-empty.vue';
	import useMescroll from '@/components/mescroll/hooks/useMescroll.js';
	import { onPageScroll, onReachBottom } from '@dcloudio/uni-app';
    const { mescrollInit, downCallback, getMescroll } = useMescroll(onPageScroll, onReachBottom);

	let storeList = ref<Array<any>>([]);
	let currCategoryId = ref<number | string>('');
	let storeTitle = ref<string>('');
	let mescrollRef = ref(null);
	let loading = ref<boolean>(false);
	const longitude = ref(0);
	const latitude = ref(0);
	
	onLoad(async () => {
		await getLoc().then((res: any) => {
			longitude.value = res.longitude;
			latitude.value = res.latitude;
		});
	})
	
	interface mescrollStructure {
		num : number,
		size : number,
		endSuccess : Function,
		[propName : string] : any
	}
	const getStoreListFn = (mescroll : mescrollStructure) => {
		loading.value = false;
		let data : object = {
			longitude: longitude.value,
			latitude: latitude.value,
			page: mescroll.num,
			limit: mescroll.size
		};

		getStoreList(data).then((res) => {
			let newArr = (res.data.data as Array<Object>);
			//设置列表数据
			if (mescroll.num == 1) {
				storeList.value = []; //如果是第一页需手动制空列表
			}
			storeList.value = storeList.value.concat(newArr);
			mescroll.endSuccess(newArr.length);
			loading.value = true;
		}).catch(() => {
			loading.value = true;
			mescroll.endErr(); // 请求失败, 结束加载
		})
	}

	const selectStore = (store : any) => {
		uni.setStorageSync("zzhc:store",store);
		redirect({ url: '/addon/zzhc/pages/index','mode':'reLaunch' })
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