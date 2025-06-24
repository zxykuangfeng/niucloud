<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh] order-list" :style="themeColor()">
		<view class="fixed left-0 top-0 right-0 z-10" v-if="statusLoading">
			<scroll-view scroll-x="true" class="scroll-Y box-border px-[24rpx] bg-white">
				<view class="flex whitespace-nowrap justify-around">
					<view :class="['text-sm leading-[90rpx]', { 'class-select': orderState == item.status }]"
						@click="orderStateFn(item.status)" v-for="(item, index) in orderStateList">{{ item.name }}</view>
				</view>
			</scroll-view>
		</view>

		<mescroll-body ref="mescrollRef" top="114rpx" @init="mescrollInit" @down="downCallback" @up="getManageOrderListFn">
			<view class="order-wrap">
				<template v-for="(item,index) in list" :key="index">
					<view class="mb-[30rpx] bg-[#fff] p-[30rpx] rounded-[20rpx] mx-[30rpx]">
						
						<view class="text-center mb-[30rpx] flex pb-[25rpx] border border-solid border-0 border-bottom-[2rpx] border-[var(--page-bg-color)]" v-if="item.status == 1">
							<view class="w-[210rpx]">
								<view class="text-[32rpx] font-bold">{{item.wait_people}}</view>
								<view class="text-[var(--primary-color-dark)] mt-[10rpx]">前面人数</view>
							</view>
							<view class="w-[210rpx]">
								<view class="text-[32rpx] font-bold">{{item.wait_duration}}<text class="text-[var(--primary-color-dark)] text-[22rpx] pl-[5rpx] font-400">分钟</text></view>
								<view class="text-[var(--primary-color-dark)] mt-[10rpx]">预计等待</view>
							</view>
							<view class="w-[210rpx]">
								<view class="text-[32rpx] font-bold">{{item.mobile.substr(item.mobile.length - 4)}}</view>
								<view class="text-[var(--primary-color-dark)] mt-[10rpx]">手机尾号</view>
							</view>
						</view>
						
						<view class="order-goods-item flex  justify-between flex-wrap" @click="toLink(item)">
							<view class="w-[180rpx] h-[180rpx]">
								<image class="rounded-[10rpx] h-[180rpx] w-[180rpx]" :src="img(item.staff_headimg)"  />
							</view>
							<view class="ml-[20rpx] flex flex-1 flex-col justify-between">
								<view class="font-bold text-item ">
									{{ item.goods_name }} ({{item.staff_name}})
								</view>
								<view class="text-[26rpx] flex">
									<view>
										<text class="text-[var(--primary-color-dark)]">VIP: </text>
										<text class="text-[var(--price-text-color)]">¥</text>
										<text class="text-[var(--price-text-color)] text-[32rpx]">{{item.goods_money}}</text>
									</view>
									<view class="ml-[30rpx]">
										<text class="text-[var(--primary-color-dark)]">原价: </text>
										<text class="text-[var(--price-text-color)]">¥</text>
										<text class="text-[var(--price-text-color)] text-[32rpx]">{{item.goods_vip_money}}</text>
									</view>
								</view>
								<view class="flex" @click.stop="callPhone(item.member.phone)">
									<u-avatar v-if="item.member" :src="img(item.member.headimg)" size="32" leftIcon="none" :default-url="img('static/resource/images/default_headimg.png')" />
									<view v-if="item.member" class="ml-[10rpx] leading-[70rpx] h-[70rpx]">{{item.member.nickname}}</view>
									<view class="ml-auto pt-[7rpx]">
										<u-icon name="phone-fill" color="var(--primary-color)" size="28"></u-icon>
									</view>
								</view>
												
							</view>
						</view>
						
						<view class="flex justify-between text-[28rpx] mt-[30rpx]">
							<text class="text-[#999]">时间：{{ item.create_time }}</text>
						</view>
						<view class="flex justify-between text-[28rpx] mt-[20rpx]">
							<text class="text-[#999]">状态：{{ item.status_name.name }}</text>
						</view>
						
						<view class="mt-[34rpx] flex justify-end z-10 ">
							
							<view class="inline-block text-[26rpx] leading-[56rpx] px-[30rpx] border-[3rpx] border-solid border-[#999] rounded-full " @click="toLink(item)">详情</view>
							
							<view class="inline-block text-[26rpx] leading-[56rpx] px-[30rpx] border-[3rpx] border-solid border-[#999] rounded-full ml-[20rpx]" v-if="item.status == 1" @click="orderBtnFn(item, 'cancel')">取消订单</view>
							
							
						</view>
					</view>
				</template>
			</view>
			<mescroll-empty :option="{ 'icon': img('static/resource/images/empty.png') }" v-if="!list.length && loading"></mescroll-empty>
		</mescroll-body>
	</view>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { t } from '@/locale'
import { img, redirect } from '@/utils/common';
import { getOrderStatus } from '@/addon/zzhc/api/order';
import { getManageOrderList, manageOrderCancel } from '@/addon/zzhc/api/merchant';
import MescrollBody from '@/components/mescroll/mescroll-body/mescroll-body.vue';
import MescrollEmpty from '@/components/mescroll/mescroll-empty/mescroll-empty.vue';
import useMescroll from '@/components/mescroll/hooks/useMescroll.js';
import { onLoad, onPageScroll, onReachBottom } from '@dcloudio/uni-app';
import openMap from '@/addon/zzhc/utils/map/openMap.js';
import { callPhone,currStoreId } from '@/addon/zzhc/utils/common';

const { mescrollInit, downCallback, getMescroll } = useMescroll(onPageScroll, onReachBottom);
let list = ref<Array<Object>>([]);
let loading = ref<boolean>(false);
let statusLoading = ref<boolean>(false);
let orderState = ref('')
let orderStateList = ref([]);


onLoad((option) => {
	orderState.value = option.status || '';
	getOrderStatusFn();
});

const getManageOrderListFn = (mescroll) => {
	loading.value = false;
	let data: object = {
		page: mescroll.num,
		limit: mescroll.size,
		store_id: currStoreId(),
		status: orderState.value
	};

	getManageOrderList(data).then((res) => {
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

const getOrderStatusFn = () => {
	statusLoading.value = false;
	orderStateList.value = [];
	let obj = { name: '全部', status: '' };
	orderStateList.value.push(obj);

	getOrderStatus().then((res) => {
		Object.values(res.data).forEach((item, index) => {
			orderStateList.value.push(item);
		});
		statusLoading.value = true;
	}).catch(() => {
		statusLoading.value = true;
	})
}


const orderStateFn = (status:number) => {
	orderState.value = status;
	list.value = [];
	getMescroll().resetUpScroll();
};

const toLink = (data: any) => {
	redirect({ url: '/addon/zzhc/pages/merchant/manage/order/detail', param: { order_id: data.order_id } })
}

//取消订单
const cancel = (item: any) => {
	uni.showModal({
		title: '提示',
		content: '您确定要取消该订单吗？',
		success: res => {
			if (res.confirm) {
				let param = {order_id:item.order_id,store_id:currStoreId()};
				manageOrderCancel(param).then((data) => {
					getMescroll().resetUpScroll();
				})
			}
		}
	})
}



const orderBtnFn = (data, type = '') => {
	if (type == 'cancel') {
		cancel(data);
	}
}

</script>
<style>
@import '@/addon/zzhc/styles/common.scss';
</style>
