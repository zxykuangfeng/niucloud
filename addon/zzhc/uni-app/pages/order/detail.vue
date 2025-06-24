<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()" >
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
				<view class="flex">
					<view class="text-[32rpx] font-bold h-[48rpx] leading-[48rpx]">
						{{detail.store.store_name}}
					</view>
					<view class="flex ml-auto">
						<image :src="img('/addon/zzhc/dh.png')" @click="openMap.openMap(detail.store.latitude,detail.store.longitude, detail.store.full_address)" class="w-[48rpx] h-[48rpx]" />
						<image :src="img('/addon/zzhc/phone.png')" @click="callPhone(detail.store.store_mobile)" class="w-[48rpx] h-[48rpx] ml-[30rpx]" />
					</view>
				</view>
				
				<view class="order-goods-item flex  justify-between flex-wrap mt-[50rpx]">
					<view class="w-[180rpx] h-[180rpx]">
						<image class="rounded-[10rpx] h-[180rpx] w-[180rpx]" :src="img(detail.goods_image)"  />
					</view>
					<view class="ml-[20rpx] flex flex-1 flex-col justify-between">
						<view>
							<text class="font-bold text-item leading-[40rpx]">{{ detail.goods_name }}</text>
							<view class="flex">
								<text class="block text-[20rpx] text-item mt-[10rpx] text-[#ccc] bg-[#f5f5f5] px-[16rpx] py-[6rpx] rounded-[999rpx]">{{detail.duration }}分钟</text>
							</view>
						</view>
						<view class="flex leading-[60rpx]">
							<image :src="img(detail.staff_headimg)" class="w-[60rpx] h-[60rpx] rounded-full" />
							<text class="ml-[10rpx]">{{detail.staff_name}}</text>
						</view>

					</view>
					
				</view>
			</view>
			<view class="bg-[#fff] mx-[30rpx] p-[30rpx] mt-[30rpx] rounded-[10rpx]">
				<view
					class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1]">
					<view>{{ t('orderNo') }}</view>
					<view>{{ detail.order_no }}</view>
				</view>
				<view v-if="detail.out_trade_no"
					class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>{{ t('orderTradeNo') }}</view>
					<view>{{ detail.out_trade_no }}</view>
				</view>
				<view
					class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>{{ t('createTime') }}</view>
					<view>{{ detail.create_time }}</view>
				</view>
				
				<view v-if="detail.pay"
					class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>{{ t('payTypeName') }}</view>
					<view>{{ detail.pay.type_name }}</view>
				</view>
				<view v-if="detail.pay"
					class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>{{ t('payTime') }}</view>
					<view>{{ detail.pay.pay_time }}</view>
				</view>
			</view>

			<view class="bg-[#fff] mx-[30rpx] p-[30rpx] mt-[30rpx] rounded-[10rpx]" v-if="detail.coupon_member_id == 0 && detail.status == 3">
			    <!-- 优惠券 -->
			    <view class="flex items-center" @click="couponRef.open()">
			        <view class="text-[28rpx] font-500 w-[150rpx]">优惠券</view>
			        <view class="flex-1 w-0 text-right">
			            <text class="text-[28rpx] text-gray-subtitle">请选择优惠券</text>
			        </view>
			        <text class="iconfont iconxiangyoujiantou text-[28rpx] text-gray-subtitle"></text>
			    </view>
			</view>


			<view class="bg-[#fff] mx-[30rpx] p-[30rpx] mt-[30rpx] rounded-[10rpx]">
				<view class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1]">
					<view>{{ t('goodsMoney') }}</view>
					<view class=" text-[var(--price-text-color)] price-font">￥{{ detail.goods_money }}</view>
				</view>
				<view class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>{{ t('goodsVipMoney') }}</view>
					<view class=" text-[var(--price-text-color)] price-font">￥{{ detail.goods_vip_money }}</view>
				</view>
				<view class="flex justify-between text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view>{{ t('discountMoney') }}</view>
					<view class=" text-[var(--price-text-color)] price-font">￥{{ detail.discount_money }}</view>
				</view>
				<view class="flex justify-end text-[28rpx] pt-[20rpx] border-top-[2rpx] border-[solid] border-[#f1f1f1] mt-[40rpx]">
					<view><text>{{ t('orderMoney') }}：</text><text class=" text-[var(--price-text-color)] price-font">￥{{ detail.order_money }}</text></view>
				</view>
			</view>
			
			
			<view
				class="flex z-2 justify-between items-center bg-[#fff] fixed left-0 right-0 bottom-0 min-h-[100rpx] px-1 flex-wrap  pb-[env(safe-area-inset-bottom)]">
				<view class="flex ml-[30rpx] w-[70rpx] flex-col justify-center items-center" @click="orderBtnFn('index')">
					<text class="iconfont iconshouye text-[32rpx]"></text>
					<text class="text-xs mt-1">{{ t('index') }}</text>
				</view>
				<view class="flex justify-end mr-[30rpx]">
					<view class="inline-block text-[26rpx] leading-[56rpx] px-[30rpx] border-[3rpx] border-solid border-[#999] rounded-full" v-if="detail.status == 1" @click="orderBtnFn('cancel')">{{ t('orderCancel') }}</view>
					<view class="inline-block text-[26rpx] leading-[56rpx] px-[30rpx] border-[3rpx] border-solid text-[#fff] bg-primary border-primary rounded-full ml-[20rpx]" @click="orderBtnFn('pay')" v-if="detail.status == 3">{{ t('topay') }}</view>
				</view>
			</view>
		</view>
		<view class="tab-bar-placeholder"></view>
		
		<pay ref="payRef" @close="payClose"></pay>
		
		<!-- 选择优惠券 -->
		<select-coupon :order-id="detail.order_id" ref="couponRef" @confirm="confirmSelectCoupon" />
		<u-loading-page bg-color="var(--page-bg-color)" :loading="loading" fontSize="16" color="var(--primary-color-dark)"></u-loading-page>
	</view>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { onLoad } from '@dcloudio/uni-app'
import { t } from '@/locale'
import { img, redirect } from '@/utils/common';
import { getOrderDetail,orderCancel, orderCoupon,orderUseCoupon,orderPay } from '@/addon/zzhc/api/order';
import openMap from '@/addon/zzhc/utils/map/openMap.js';
import { callPhone } from '@/addon/zzhc/utils/common';
import selectCoupon from './components/select-coupon/select-coupon'

let detail = ref<Object>({});
let loading = ref<boolean>(true);
let type = ref('')
let orderId = ref('')
const couponRef = ref()

onLoad((option) => {
	orderId.value = option.order_id;
	orderDetailFn(orderId.value);
});


const orderDetailFn = (orderId: number) => {
	loading.value = true;
	getOrderDetail(orderId).then((res) => {
		detail.value = res.data;
		loading.value = false;
	}).catch(() => {
		loading.value = false;
	})
}

const couponList = computed(() => {
    return couponRef.value?.couponList || []
})


//取消订单
const cancel = (item: any) => {
	uni.showModal({
		title: '提示',
		content: '您确定要取消该订单吗？',
		success: res => {
			if (res.confirm) {
				orderCancel(item.order_id).then(() => {
					orderDetailFn(item.order_id);
				})
			}
		}
	})
}


const payRef = ref(null)
const orderBtnFn = (type = '') => {
	if (type == 'pay'){
		
		orderPay(detail.value.order_id).then(() => {
			payRef.value?.open(detail.value.order_type, detail.value.order_id, `/addon/zzhc/pages/order/detail?order_id=${detail.value.order_id}`);
		})
		
	} else if (type == 'cancel') {
		cancel(detail.value);
	} else if (type == 'index') {
		redirect({
			url: '/addon/zzhc/pages/index'
		});
	} 
}

/**
 * 使用优惠券
 */
const confirmSelectCoupon = (coupon: any) => {
   console.log(coupon);
   
   if(coupon != null){
	   let data = {order_id:detail.value.order_id,coupon_member_id:coupon.id};
	   orderUseCoupon(data).then(() => {
	   		orderDetailFn(detail.value.order_id);
	   })
   }
   
}

</script>
<style>
	@import '@/addon/zzhc/styles/common.scss';
</style>
