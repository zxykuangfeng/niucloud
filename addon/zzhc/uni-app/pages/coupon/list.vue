<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()">

		<mescroll-body ref="mescrollRef" @init="mescrollInit" @down="downCallback" @up="getCouponListFn">
			<view class="py-[20rpx] px-[24rpx]">
				<template v-for="(item, index) in list">
					<view v-if="item.btnType === 'collected'"
						class="flex items-center relative w-[100%] rounded-[12rpx] overflow-hidden bg-[#EEEEEE]"
						:class="{'mt-[20rpx]':index}">
						<view class="relative pt-[50rpx] w-[254rpx] h-[222rpx] bg-[#93979D] text-[#fff] text-center box-border px-[40rpx] box-border">
							<view class="w-[100%]">
								<view class="leading-[40rpx] font-500 price-font">
									<text class="text-[28rpx]">￥</text><text class="text-[50rpx]">{{ item.money }}</text>
								</view>
								<view class="mt-[35rpx] text-[23rpx] leading-[32rpx] font-500">
									<text v-if="item.atleast === '0.00'">无门槛</text>
									<text v-else>满{{ item.atleast }}元可用</text>
								</view>
							</view>
						</view>
						<view class="h-[222rpx] flex flex-1 flex-wrap pt-[24rpx] box-border ml-[19rpx] mr-[9rpx]">
							<view class="text-[27rpx] text-[#303133] leading-[38rpx] w-[100%]">
								<view>{{ item.name }}</view>
								<view class="flex mt-[5rpx]">
									<text class="flex items-center bg-[#93979D] text-[#fff] text-[20rpx] h-[32rpx] leading-[32rpx] px-[16rpx] rounded-[16rpx]">所有门店通用</text>
								</view>
							</view>
							<view
								class="self-end w-[100%] pt-[19rpx] pb-[20rpx] text-[22rpx] leading-[30rpx] text-[#90939A] border-0 border-t-[1px] border-dashed border-[#ccc]">
								<text v-if="item.validity_type == 0">领取之日起<text class="text-[var(--primary-color)]">{{item.fixed_term || '' }}</text>天内有效</text>
								<text v-else-if="item.validity_type == 1"> 有效期至<text class="text-[var(--primary-color)]">{{ item.end_usetime ? item.end_usetime.slice(0, 10) : '' }}</text></text>
								<text v-else>永久有效</text>
							</view>
						</view>
						<button class="!w-[128rpx] !h-[50rpx] text-[23rpx] !text-[#93979D]  !mr-[34rpx] !border-0 !bg-[#EEEEEE] !border-0 rounded-full text-white leading-[50rpx] remove-border" disabled>已领完</button>
						<view class="absolute top-[50%] left-0 mt-[-20rpx] h-[40rpx] w-[20rpx] rounded-tr-[20rpx] rounded-br-[20rpx] bg-[var(--page-bg-color)] "></view>
						<view class="absolute top-[50%] right-0 mt-[-20rpx] h-[40rpx] w-[20rpx] rounded-tl-[20rpx] rounded-bl-[20rpx] bg-[var(--page-bg-color)] "></view>
					</view>
					<view v-else class ="flex items-center relative w-[100%] rounded-[16rpx] overflow-hidden bg-white"
						:class="{'mt-[20rpx]':index}">
						<view class="relative pt-[50rpx] w-[254rpx] h-[222rpx] bg-[var(--primary-color)] text-[#fff] text-center box-border px-[40rpx] box-border">
							<view class="w-[100%]">
								<view class="leading-[40rpx] font-500 price-font">
									<text class="text-[28rpx]">￥</text><text class="text-[50rpx]">{{ item.money }}</text>
								</view>
								<view class="mt-[35rpx] text-[23rpx] leading-[32rpx] font-500">
									<text v-if="item.atleast === '0.00'">无门槛</text>
									<text v-else>满{{ item.atleast }}元可用</text>
								</view>
							</view>
						</view>
						<view class="h-[222rpx] flex flex-1 flex-wrap pt-[24rpx] box-border ml-[19rpx] mr-[9rpx]">
							<view class="text-[27rpx] text-[#303133] leading-[38rpx] w-[100%]">
								<view>{{ item.name }}</view>
								<view class="flex mt-[5rpx]">
									<text class="flex items-center bg-[var(--primary-color)] text-[#fff] text-[20rpx] h-[32rpx] leading-[32rpx] px-[16rpx] rounded-[16rpx]">所有门店通用</text>
								</view>
							</view>
							<view class="self-end w-[100%] pt-[20rpx] pb-[20rpx] text-[22rpx] leading-[30rpx] text-[#90939A] border-0 border-t-[1px] border-dashed border-[#ccc]">
								<text v-if="item.validity_type == 0">领取之日起<text class="text-[var(--primary-color)]">{{item.fixed_term || '' }}</text>天内有效</text>
								<text v-else-if="item.validity_type == 1"> 有效期至<text class="text-[var(--primary-color)]">{{ item.end_usetime ? item.end_usetime.slice(0, 10) : '' }}</text></text>
								<text v-else>永久有效</text>
							</view>
						</view>
						<view v-if="item.btnType === 'collecting'" @click="collecting(item.coupon_id, index)" class="!mr-[34rpx]">
							<button class="w-[128rpx] h-[50rpx] text-[23rpx] !border-0  !bg-[var(--primary-color)] rounded-full text-white leading-[50rpx] remove-border">领取</button>
						</view>
						<view v-if="item.btnType === 'using'" @click="toLink()" class="!mr-[34rpx]">
							<button class="w-[128rpx] h-[50rpx] text-[23rpx] !border-0 !bg-[var(--primary-color)] rounded-full text-white leading-[50rpx] remove-border">去使用</button>
						</view>

						<view class="absolute top-[50%] left-0 mt-[-20rpx] h-[40rpx] w-[20rpx] rounded-tr-[20rpx] rounded-br-[20rpx] bg-[var(--page-bg-color)]"></view>
						<view class="absolute top-[50%] right-0 mt-[-20rpx] h-[40rpx] w-[20rpx] rounded-tl-[20rpx] rounded-bl-[20rpx] bg-[var(--page-bg-color)]"></view>
					</view>
				</template>
			</view>
			<mescroll-empty :option="{ 'icon': img('static/resource/images/empty.png') }" v-if="!list.length && !loading"></mescroll-empty>
		</mescroll-body>
	</view>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { img, redirect } from '@/utils/common'
import { getCouponList, receiveCoupon } from '@/addon/zzhc/api/coupon'
import MescrollBody from '@/components/mescroll/mescroll-body/mescroll-body.vue'
import MescrollEmpty from '@/components/mescroll/mescroll-empty/mescroll-empty.vue'
import useMescroll from '@/components/mescroll/hooks/useMescroll.js'
import { onPageScroll, onReachBottom } from '@dcloudio/uni-app'
import useMemberStore from '@/stores/member'
import { useLogin } from '@/hooks/useLogin'
import { t } from '@/locale'

const { mescrollInit, downCallback, getMescroll } = useMescroll(onPageScroll, onReachBottom)
const list = ref<Array<Object>>([]);
const loading = ref<boolean>(true);
const memberStore = useMemberStore()
const userInfo = computed(() => memberStore.info)
const getCouponListFn = (mescroll) => {
	loading.value = true;
	let data: object = {
		page: mescroll.num,
		limit: mescroll.size,
	};

	getCouponList(data).then((res) => {
		let newArr = (res.data.data as Array<Object>).map((el: any) => {
			
			console.log('userInfo',userInfo.value)
			
			if (el.lead_count == el.count) {
				el.btnType = 'collected'//已领完
			}
			if (!userInfo.value) {
				el.btnType = 'collecting'//领用
			} else {
				if (el.is_receive && el.max_fetch === el.member_receive_count) {
					el.btnType = 'using'//去使用
				} else {
					el.btnType = 'collecting'//领用
				}
			}
			return el
		})
		//设置列表数据
		if (mescroll.num == 1) {
			list.value = []; //如果是第一页需手动制空列表
		}
		list.value = list.value.concat(newArr);
		mescroll.endSuccess(newArr.length);
		loading.value = false;
	}).catch(() => {
		loading.value = false;
		mescroll.endErr(); // 请求失败, 结束加载
	})
}
const collecting = (coupon_id: any, index: number) => {
	
	if (!userInfo.value) {
		useLogin().setLoginBack({ url: '/addon/zzhc/pages/coupon/list' })
		return false
	}
	receiveCoupon({ coupon_id, number: 1,type:'receive' }).then(res => {
		list.value[index].btnType = 'using'
	})
}

const toLink = () => {
	redirect({ url: '/addon/zzhc/pages/order/list' })
}
</script>

<style>
@import '@/addon/zzhc/styles/common.scss';
</style>
