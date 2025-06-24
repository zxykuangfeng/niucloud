<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh] order-list" :style="themeColor()">
		<view class="fixed left-0 top-0 right-0 z-10">
			<scroll-view scroll-x="true" class="scroll-Y box-border px-[24rpx] bg-white">
				<view class="flex whitespace-nowrap justify-around">
					<view :class="['text-sm leading-[90rpx]', { 'class-select': dateState === item.value }]"
						@click="dateStateFn(item.value)" v-for="(item, index) in dateStateList" :key="index">{{ item.name }}</view>
				</view>
			</scroll-view>
		</view>

		<mescroll-body ref="mescrollRef" top="90rpx" @init="mescrollInit" @down="downCallback" @up="getManageWorkListFn">
			<view class="order-wrap">
				<view v-for="(item,index) in list" :key="index" class="flex mb-[30rpx] bg-[#fff] m-[30rpx] p-[20rpx] rounded-[10rpx]">
					
					<view :class="item.status" class="w-[130rpx] text-[#fff] h-[130rpx] text-center leading-[130rpx] rounded-[16rpx]">
						{{item.status_name}}
					</view>
					
					<view class="ml-[20rpx]">
						<view>{{item.staff.staff_name}}</view>
						<view class="text-[26rpx] text-[var(--primary-color-dark)] mt-[5rpx]">{{item.create_time}}</view>
						<view class="text-[26rpx] text-[var(--primary-color-dark)] mt-[15rpx]">{{item.full_address}}</view>
					</view>
				</view>
			</view>
			<mescroll-empty :option="{ 'icon': img('static/resource/images/empty.png') }" v-if="!list.length && loading"></mescroll-empty>
		</mescroll-body>
		
	</view>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { t } from '@/locale'
import { img, redirect } from '@/utils/common';
import { getManageWorkList } from '@/addon/zzhc/api/merchant'
import MescrollBody from '@/components/mescroll/mescroll-body/mescroll-body.vue';
import MescrollEmpty from '@/components/mescroll/mescroll-empty/mescroll-empty.vue';
import useMescroll from '@/components/mescroll/hooks/useMescroll.js';
import { onLoad, onPageScroll, onReachBottom } from '@dcloudio/uni-app';
import openMap from '@/addon/zzhc/utils/map/openMap.js';
import { callPhone, currStoreId } from '@/addon/zzhc/utils/common';

const { mescrollInit, downCallback, getMescroll } = useMescroll(onPageScroll, onReachBottom);
let list = ref<Array<Object>>([]);
let loading = ref<boolean>(false);
let statusLoading = ref<boolean>(false);
let dateState = ref(0)
let dateStateList = ref([
	{'name':'全部','value':0,},
	{'name':'今日','value':1,},
	{'name':'昨日','value':2,},
	{'name':'本月','value':3,}
]);

const getManageWorkListFn = (mescroll) => {
	loading.value = false;
	let data: object = {
		page: mescroll.num,
		limit: mescroll.size,
		date: dateState.value,
		store_id: currStoreId()
	};

	getManageWorkList(data).then((res) => {
		let newArr = (res.data.data as Array<Object>);
		if (mescroll.num == 1) {
			list.value = [];
		}
		list.value = list.value.concat(newArr);
		mescroll.endSuccess(newArr.length);
		loading.value = true;
	}).catch(() => {
		loading.value = true;
		mescroll.endErr(); 
	})
}

const dateStateFn = (value: number) => {
	dateState.value = value;
	list.value = [];
	getMescroll().resetUpScroll();
};


</script>
<style>
@import '@/addon/zzhc/styles/common.scss';
</style>

<style lang="scss">
	.working{background-color: var(--primary-color);}
	.meal{background-color: var(--price-text-color);}
	.thing,.stop{background-color: #FA8241;}
	.rest{background-color: var(--primary-color-dark);}
</style>
