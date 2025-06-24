<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()">
		<view v-if="!loading" class="pb-20rpx">
			<view v-if="detail.status_name" class="flex status-item text-[32rpx] bg-[var(--primary-color)] h-[240rpx]">
				<view class="ml-[50rpx]">
					<img v-if="detail.status == 1" class="w-[70rpx] h-[70rpx] mt-[45rpx]" :src="img('addon/zzhc/wait_service.png')" />
					<img v-if="detail.status == 2" class="w-[70rpx] h-[70rpx] mt-[45rpx]" :src="img('addon/zzhc/in_service.png')" />
					<img v-if="detail.status == 3" class="w-[70rpx] h-[70rpx] mt-[45rpx]" :src="img('addon/zzhc/wait_pay.png')" />
					<img v-if="detail.status == 5" class="w-[70rpx] h-[70rpx] mt-[45rpx]" :src="img('addon/zzhc/finish.png')" />
					<img v-if="detail.status == -1" class="w-[70rpx] h-[70rpx] mt-[45rpx]" :src="img('addon/zzhc/cancel.png')" />
				</view>
				<view class="ml-[20rpx] text-[#fff] mt-[50rpx] text-[40rpx]">
					{{ detail.status_name.name }}
				</view>
			</view>
			<view class="bg-[#fff] mx-[30rpx] p-[30rpx] mt-[-70rpx] text-center rounded-[10rpx] flex" v-if="detail.status == 1">
				<view class="w-[210rpx]">
					<view class="text-[32rpx] font-bold">{{detail.wait_people}}</view>
					<view class="text-[var(--primary-color-dark)] mt-[10rpx]">前面人数</view>
				</view>
				<view class="w-[210rpx]">
					<view class="text-[32rpx] font-bold">{{detail.wait_duration}}<text class="text-[var(--primary-color-dark)] text-[22rpx] pl-[5rpx] font-400">分钟</text></view>
					<view class="text-[var(--primary-color-dark)] mt-[10rpx]">预计等待</view>
				</view>
				<view class="w-[210rpx]">
					<view class="text-[32rpx] font-bold">{{detail.mobile.substr(detail.mobile.length - 4)}}</view>
					<view class="text-[var(--primary-color-dark)] mt-[10rpx]">手机尾号</view>
				</view>
			</view>
			<view class="bg-[#fff] mx-[30rpx] p-[30rpx] rounded-[10rpx]" :style="detail.status != 1 ? 'margin-top: -70rpx' : 'margin-top: 30rpx'">
				<view class="order-goods-item flex  justify-between flex-wrap ">
					<view class="w-[180rpx] h-[180rpx]">
						<image class="rounded-[10rpx] h-[180rpx] w-[180rpx]" :src="img(detail.staff_headimg)"  />
					</view>
					<view class="ml-[20rpx] flex flex-1 flex-col justify-between">
						<view>
							<text class="font-bold text-item leading-[40rpx]">{{ detail.goods_name }} ({{detail.staff_name}})</text>
							<view class="flex">
								<text class="block text-[20rpx] text-item mt-[10rpx] text-[#ccc] bg-[#f5f5f5] px-[16rpx] py-[6rpx] rounded-[999rpx]">{{detail.duration }}分钟</text>
							</view>
						</view>
						
						<view class="flex" @click.stop="callPhone(detail.member.phone)">
							<u-avatar :src="img(detail.member.headimg)" size="32" leftIcon="none" :default-url="img('static/resource/images/default_headimg.png')" />
							<view class="ml-[10rpx] leading-[70rpx] h-[70rpx]">{{detail.member.nickname}}</view>
							<view class="ml-auto pt-[7rpx]">
								<u-icon name="phone-fill" color="var(--primary-color)" size="28"></u-icon>
							</view>
						</view>

					</view>
					
				</view>
			</view>
			<view class="bg-[#fff] mx-[30rpx] p-[30rpx] mt-[30rpx] rounded-[10rpx]">
				<view
					class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1]">
					<view>订单号</view>
					<view>{{ detail.order_no }}</view>
				</view>
				<view v-if="detail.out_trade_no" class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>支付流水号</view>
					<view>{{ detail.out_trade_no }}</view>
				</view>
				<view class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>创建时间</view>
					<view>{{ detail.create_time }}</view>
				</view>
				
				<view v-if="detail.pay" class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>支付方式</view>
					<view>{{ detail.pay.type_name }}</view>
				</view>
				<view v-if="detail.pay" class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>支付时间</view>
					<view>{{ detail.pay.pay_time }}</view>
				</view>
			</view>

			<view class="bg-[#fff] mx-[30rpx] p-[30rpx] mt-[30rpx] rounded-[10rpx]">
				<view class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1]">
					<view>项目价格</view>
					<view class=" text-[var(--price-text-color)] price-font">￥{{ detail.goods_money }}</view>
				</view>
				<view class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>VIP价格</view>
					<view class=" text-[var(--price-text-color)] price-font">￥{{ detail.goods_vip_money }}</view>
				</view>
				<view class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]" v-if="detail.pay">
					<view>优惠金额</view>
					<view class=" text-[var(--price-text-color)] price-font">￥{{ detail.discount_money }}</view>
				</view>
				<view class="flex justify-end text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]" v-if="detail.pay">
					<view><text>订单金额：</text><text class=" text-[var(--price-text-color)] price-font">￥{{
						detail.order_money }}</text></view>
				</view>
			</view>

			<view class="flex z-2 justify-between items-center bg-[#fff] fixed left-0 right-0 bottom-0 min-h-[100rpx] px-1 flex-wrap  pb-[env(safe-area-inset-bottom)]">
				<view class="flex ml-[30rpx] flex-col justify-center items-center" @click="orderBtnFn('back')">
					<u-icon name="arrow-left" label = "返回" labelPos="right" color="#999" size="20"></u-icon>
				</view>
				<view class="flex justify-end mr-[30rpx]">
					<view class="inline-block text-[26rpx] leading-[56rpx] px-[30rpx] border-[3rpx] border-solid border-[#999] rounded-full" v-if="detail.status == 1" @click="orderBtnFn('cancel')">取消订单</view>
				</view>
			</view>
		</view>
		<view class="tab-bar-placeholder"></view>
		<u-loading-page bg-color="var(--page-bg-color)" :loading="loading" fontSize="16" color="var(--primary-color-dark)"></u-loading-page>
	</view>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { onLoad } from '@dcloudio/uni-app'
import { t } from '@/locale'
import { img, redirect } from '@/utils/common';
import { getManageOrderDetail,manageOrderCancel } from '@/addon/zzhc/api/merchant';
import openMap from '@/addon/zzhc/utils/map/openMap.js';
import { callPhone,currStoreId } from '@/addon/zzhc/utils/common';

let detail = ref<Object>({});
let loading = ref<boolean>(true);
let type = ref('')
let orderId = ref('')

onLoad((option) => {
	orderId.value = option.order_id;
	orderDetailFn();
});


const orderDetailFn = () => {
	loading.value = true;
	let data = {order_id:orderId.value,store_id:currStoreId()};
	getManageOrderDetail(data).then((res) => {
		detail.value = res.data;
		loading.value = false;
	}).catch(() => {
		loading.value = false;
	})
}


//取消订单
const cancel = (item: any) => {
	uni.showModal({
		title: '提示',
		content: '您确定要取消该订单吗？',
		success: res => {
			if (res.confirm) {
				let param = {order_id:item.order_id,store_id:currStoreId()};
				manageOrderCancel(param).then(() => {
					orderDetailFn(param.order_id);
				})
			}
		}
	})
}

//完成服务
const finish = (item: any) => {
	uni.showModal({
		title: '提示',
		content: '您确定已完成服务吗？',
		success: res => {
			if (res.confirm) {
				let param = {order_id:item.order_id,store_id:currStoreId()};
				manageOrderFinish(param).then((data) => {
					orderDetailFn(item.order_id);
				})
			}
		}
	})
}

//开始服务
const service = (item: any) => {
	let param = {order_id:item.order_id,store_id:currStoreId()};
	manageOrderService(param).then((data) => {
		orderDetailFn(item.order_id);
	})
}

//退回排队
const revert = (item: any) => {
	uni.showModal({
		title: '提示',
		content: '您确定将该订单退回排队吗？',
		success: res => {
			if (res.confirm) {
				let param = {order_id:item.order_id,store_id:currStoreId()};
				manageOrderRevert(param).then((data) => {
					orderDetailFn(item.order_id);
				})
			}
		}
	})
	
}

const orderBtnFn = (type = '') => {
	if (type == 'back') {
		redirect({ url: '/addon/zzhc/pages/merchant/manage/order/list' })
	}else if(type == 'cancel') {
		cancel(detail.value);
	}else if(type == 'service') {
		service(detail.value);
	}else if(type == 'revert') {
		revert(detail.value);
	}else if(type == 'finish') {
		finish(detail.value);
	} 
}

</script>
<style>
	@import '@/addon/zzhc/styles/common.scss';
</style>
