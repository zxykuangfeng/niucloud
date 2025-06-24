<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()">
		<view class="fixed left-0 top-0 right-0 z-10">
			<view class="barber bg-white p-[30rpx]">
				<view class="flex">
					<view class="leading-[150rpx] h-[150rpx]">
						<image :src="img(barberDetail.staff_headimg)" class="w-[150rpx] h-[150rpx] rounded-[75rpx] " />
					</view>
					<view class="ml-[20rpx]">
						<view class="text-[32rpx] mt-[10rpx]">{{barberDetail.staff_name}}</view>
						<view class="text-[26rpx] mt-[20rpx]"><text class="text-[var(--primary-color-dark)]">职级：</text>{{barberDetail.staff_position}}</view>
						<view class="flex text-[26rpx] mt-[10rpx] leading-[56rpx]">
							<view class="text-[var(--primary-color-dark)]">
								已服务<text class="text-[var(--primary-color)]">2</text>单
							</view>
						</view>
					</view>
				</view>
				
			</view>
			
			<scroll-view scroll-x="true" class="scroll-Y box-border px-[24rpx] bg-white">
				<view class="flex whitespace-nowrap">
					<view :class="['text-sm leading-[90rpx] mr-[40rpx]', { 'class-select': categoryId == 0 }]" @click="categoryChangeFn(0)">全部</view>
					<view :class="['text-sm leading-[90rpx] mr-[40rpx]', { 'class-select': categoryId == item.category_id }]" v-for="(item, index) in categoryList" @click="categoryChangeFn(item.category_id)" :key="index">{{ item.category_name }}</view>
				</view>
			</scroll-view>
		</view>
		
		<mescroll-body ref="mescrollRef" top="300rpx" @init="mescrollInit" @down="downCallback" @up="getGoodsListFn">
			<view class="staff-list">
				<view v-for="(item,index) in goodsList" :key="index" class="flex bg-white rounded-[20rpx] mt-[30rpx] p-[20rpx] mx-[30rpx]" @click="currGoods = item">
					<view>
						<u--image class="rounded-[10rpx] overflow-hidden" width="175rpx" height="175rpx"
							:src="img(item.goods_image)"
							model="aspectFill">
							<template #error>
								<u-icon name="photo" color="var(--primary-color-dark)" size="50"></u-icon>
							</template>
						</u--image>
					</view>
					<view class="ml-[20rpx] w-[100%] pt-[14rpx]">
						<view class="text-[32rpx]">{{item.goods_name}}</view>
						<view class="text-[26rpx] mt-[25rpx] flex">
							<view v-if="vipConfig.is_enable == 1" class="mr-[30rpx]">
								<text class="text-[var(--primary-color-dark)]">VIP: </text>
								<text class="text-[var(--price-text-color)]">¥</text>
								<text class="text-[var(--price-text-color)] text-[32rpx]">{{moneyFormat(item.price * (vipConfig.discount/10))}}</text>
							</view>
							<view>
								<text class="text-[var(--primary-color-dark)]">原价: </text>
								<text class="text-[var(--price-text-color)]">¥</text>
								<text class="text-[var(--price-text-color)] text-[32rpx]">{{item.price}}</text>
							</view>
						</view>
						<view class="flex text-[26rpx] mt-[20rpx] leading-[56rpx]">
							<view class="text-[var(--primary-color-dark)]">约<text class="text-[#FA8241]">{{item.duration}}</text>分钟</view>
							
							<view class="ml-auto text-[#ffffff] w-[130rpx] h-[56rpx] rounded-[28rpx] bg-[var(--primary-color)] text-center" v-if="currGoods.goods_id == item.goods_id">选中</view>
							<view class="ml-auto text-[#FA8241] w-[130rpx] h-[56rpx] rounded-[28rpx] bg-[#FFEDE4] text-center" v-else>选择</view>
							
							
						</view>
					</view>
				</view>
			</view>
			
			<mescroll-empty :option="{tip : '暂无项目'}" v-if="!goodsList.length && loading"></mescroll-empty>
		</mescroll-body>
		
		<u-tabbar :fixed="true" :placeholder="true" :safeAreaInsetBottom="true">
		    <view class="flex-1 flex items-center justify-between">
		        <view class="whitespace-nowrap flex px-[30rpx] text-color font-600 leading-[45rpx]">
					<view class="mr-[30rpx]" v-if="vipConfig.is_enable == 1 && currGoods.price">
						<text class="text-[#333333] text-[26rpx]">VIP：</text>
						<text class="text-[24rpx] font-500 text-[var(--price-text-color)] price-font">￥</text>
						<text class="text-[34rpx] mr-[10rpx]  font-500  text-[var(--price-text-color)] price-font">
							{{moneyFormat(currGoods.price * (vipConfig.discount/10))}}
						</text>
					</view>
					
					<view>
						<text class="text-[#333333] text-[26rpx]">原价：</text>
						<text class="text-[24rpx] font-500 text-[var(--price-text-color)] price-font">￥</text>
						<text class="text-[34rpx] mr-[10rpx]  font-500  text-[var(--price-text-color)] price-font">
							{{currGoods.price ? currGoods.price : 0}} 
						</text>
					</view>
		        </view>
				
		        <button class="!w-[204rpx] !h-[70rpx] text-[32rpx] mr-[30rpx] leading-[70rpx] rounded-full text-white bg-[var(--primary-color)] remove-border" :disabled="!currGoods.goods_id" :loading="createLoading" @click="create">立即取号</button>
		    </view>
		</u-tabbar>
	</view>
</template>

<script setup lang="ts">
	import { reactive, ref, onMounted } from 'vue'
	import { onLoad, onShow } from '@dcloudio/uni-app'
	import { t } from '@/locale'
	import { redirect, img,moneyFormat } from '@/utils/common';
	import { getBarberDetail } from '@/addon/zzhc/api/staff';
	import { getCategoryList,getGoodsList } from '@/addon/zzhc/api/goods';
	import { getVipConfig } from '@/addon/zzhc/api/vip';
	import { orderCreate } from '@/addon/zzhc/api/order';
	import MescrollBody from '@/components/mescroll/mescroll-body/mescroll-body.vue';
	import MescrollEmpty from '@/components/mescroll/mescroll-empty/mescroll-empty.vue';
	import useMescroll from '@/components/mescroll/hooks/useMescroll.js';
	import { onPageScroll, onReachBottom } from '@dcloudio/uni-app';
	import { currStoreId,distanceFormat } from '@/addon/zzhc/utils/common';
	import useMemberStore from '@/stores/member'
    const { mescrollInit, downCallback, getMescroll } = useMescroll(onPageScroll, onReachBottom);

	const goodsList = ref<Array<any>>([]);
	const currGoods = ref<Array<any>>([]);
	const categoryList = ref<Array<any>>([]);
	const categoryId = ref(0);
	const mescrollRef = ref(null);
	const staffId = ref(0);
	const barberDetail = ref<Array<any>>([]);
	const createLoading = ref(false);
	const vipConfig = ref({});
	const loading = ref(false);
	
	onLoad((option) => {
		staffId.value = option.staff_id;
		getVipConfigFn();
		getBarberDetailFn();
		getCategoryListFn();
	})
	
	onShow(() => {
		if(currStoreId() == 0){
			redirect({ url: '/addon/zzhc/pages/store/list', mode: 'navigateTo' });
		}
	})
	
	const getBarberDetailFn = () => {
		getBarberDetail(staffId.value).then((res) => {
			barberDetail.value = res.data;
		});
	}
	
	const getVipConfigFn = () => {
		getVipConfig().then((res) => {
			vipConfig.value = res.data;
		});
	}
	
	const getCategoryListFn = () => {
		getCategoryList().then((res) => {
			categoryList.value = res.data;
		});
	}
	

	interface mescrollStructure {
		num : number,
		size : number,
		endSuccess : Function,
		[propName : string] : any
	}
	
	const getGoodsListFn = (mescroll : mescrollStructure) => {
		loading.value = false;
		let data : object = {
			page: mescroll.num,
			limit: mescroll.size,
			category_id: categoryId.value
		};

		getGoodsList(data).then((res) => {
			let newArr = (res.data.data as Array<Object>);
			//设置列表数据
			if (mescroll.num == 1) {
				goodsList.value = []; //如果是第一页需手动制空列表
			}
			goodsList.value = goodsList.value.concat(newArr);
			mescroll.endSuccess(newArr.length);
			loading.value = true;
		}).catch(() => {
			loading.value = true;
			mescroll.endErr(); // 请求失败, 结束加载
		})
	}
	
	const categoryChangeFn = (category_id: number) => {
		categoryId.value = category_id;
		goodsList.value = [];
		getMescroll().resetUpScroll();
	};
	
	//创建订单
	const create = () => {
		
		let memberInfo = useMemberStore().info;

		if(memberInfo.mobile == ""){
			uni.showModal({
				title:'温馨提示',
				content:'请先绑定手机号',
				confirmText:'去绑定',
				success(e) {
					if(e.confirm){
						redirect({ url: '/app/pages/member/personal' })
					}
				}
			})
		}else{
			createLoading.value = true
			let data = {store_id:currStoreId(),staff_id:staffId.value,goods_id:currGoods.value.goods_id};
			orderCreate(data).then((res) => {
				redirect({ url: '/addon/zzhc/pages/order/detail', param: { order_id: res.data.order_id }, mode: 'redirectTo' })
			}).catch(() => {
				createLoading.value = false
			})
		}
		
		
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