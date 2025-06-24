<template>
	<view class="bg-[var(--page-bg-color)] min-h-[100vh] p-[30rpx]" :style="themeColor()">
		<view v-if="!loading">
			<view class="bg-white rounded-[20rpx] p-[30rpx] pr-0 flex">
				<view class="flex">
					<image :src="img(info.staff_headimg)" class="w-[140rpx] h-[140rpx] rounded-[10rpx]" />
				</view>
				<view class="ml-[10rpx]">
					<view class="font-bold">{{info.staff_name}}</view>
					<view class="text-[var(--primary-color-dark)] text-[26rpx] mt-[15rpx]">{{info.staff_position}}</view>
					<view class="text-[var(--primary-color-dark)] text-[26rpx] mt-[20rpx]">{{store.store_name}}</view>
				</view>
				<view class="ml-auto bg-[var(--primary-color)] text-[#ffffff] mt-[44rpx] px-[30rpx] h-[52rpx] leading-[52rpx] rounded-tl-[26rpx] rounded-bl-[26rpx] text-[28rpx]" @click="showAddWorkPopup = true">
					
					<text v-if="work == null">
						考勤打卡
					</text>
					<block v-else v-for="(item, index) in workStatusList" :key="index">
						<text v-if="item.value == work.status"  :class="item.icon" ></text>
						<text v-if="item.value == work.status"  class="ml-[10rpx]">{{item.name}}</text>
					</block>
					
				</view>
			</view>
			
			<view class="bg-white p-[30rpx] rounded-[20rpx] mt-[30rpx]">
				<view class="flex" @click="redirect({ url: '/addon/zzhc/pages/merchant/barber/order/list'})">
					<text class="font-bold text-[30rpx]">预约订单</text>
					<text class="ml-auto"><u-icon name="arrow-right" color="var(--primary-color-dark)" label="查看" labelSize="13" labelPos="left" size="16"></u-icon></text>
				</view>
				<view class="flex text-center mt-[55rpx]">
					<view class="w-[210rpx]" @click="toOrder(1)">
						<view class="relative">
							<image :src="img('addon/zzhc/merchant/wait_service.png')" class="w-[50rpx] h-[50rpx]" />
							<view class="w-[34rpx] h-[34rpx] rounded-[17rpx] leading-[34rpx] absolute top-[-20rpx] right-[50rpx] bg-[var(--primary-color)] text-[20rpx] text-white">{{orderCount.wait_service_count}}</view>
						</view>
						<view>待服务</view>
					</view>
					<view class="w-[210rpx]" @click="toOrder(2)">
						<view class="relative">
							<image :src="img('addon/zzhc/merchant/in_service.png')" class="w-[50rpx] h-[50rpx]" />
							<view class="w-[34rpx] h-[34rpx] rounded-[17rpx] leading-[34rpx] absolute top-[-20rpx] right-[50rpx] bg-[var(--primary-color)] text-[20rpx] text-white">{{orderCount.in_service_count}}</view>
						</view>
						<view>服务中</view>
					</view>
					<view class="w-[210rpx]" @click="toOrder(3)">
						<view class="relative">
							<image :src="img('addon/zzhc/merchant/wait_pay.png')" class="w-[50rpx] h-[50rpx]" />
							<view class="w-[34rpx] h-[34rpx] rounded-[17rpx] leading-[34rpx] absolute top-[-20rpx] right-[50rpx] bg-[var(--primary-color)] text-[20rpx] text-white">{{orderCount.wait_pay_count}}</view>
						</view>
						<view>待付款</view>
					</view>
				</view>
			</view>
			
			<view class="bg-white p-[30rpx] rounded-[20rpx] mt-[30rpx]">
				<view class="flex">
					<text class="font-bold text-[30rpx]">我的业绩</text>
				</view>
				<view class="text-center mt-[40rpx]">
					<view class="text-[var(--primary-color-dark)]">总业绩</view>
					<view class="text-[var(--price-text-color)] font-bold text-[40rpx] mt-[20rpx]">¥{{orderMoney.all}}</view>
				</view>
				<view class="flex text-center mt-[50rpx]">
					<view class="w-[210rpx]" >
						<view>{{orderCount.finish_count}}笔</view>
						<view class="text-[var(--primary-color-dark)]">完成订单</view>
					</view>
					<view class="w-[210rpx]">
						<view>¥{{orderMoney.today}}</view>
						<view class="text-[var(--primary-color-dark)]">今日业绩</view>
					</view>
					<view class="w-[210rpx]">
						<view>¥{{orderMoney.month}}</view>
						<view class="text-[var(--primary-color-dark)]">本月业绩</view>
					</view>
				</view>
			</view>
			
			<view class="bg-white p-[30rpx] rounded-[20rpx] mt-[30rpx]">
				<view class="flex">
					<text class="font-bold text-[30rpx]">功能选项</text>
				</view>
				<view class="flex text-center mt-[40rpx]">
					<view class="w-[210rpx]" @click="showAddWorkPopup = true" >
						<view><image :src="img('addon/zzhc/merchant/menu1.png')" class="w-[90rpx] h-[90rpx] rounded-[32rpx]" /></view>
						<view>考勤打卡</view>
					</view>
					<view class="w-[210rpx]" @click="redirect({ url: '/addon/zzhc/pages/merchant/barber/work/list'})">
						<view><image :src="img('addon/zzhc/merchant/menu2.png')" class="w-[90rpx] h-[90rpx] rounded-[32rpx]" /></view>
						<view>考勤记录</view>
					</view>
					<view class="w-[210rpx]" @click="redirect({ url: '/addon/zzhc/pages/merchant/barber/order/list'})">
						<view><image :src="img('addon/zzhc/merchant/menu4.png')" class="w-[90rpx] h-[90rpx] rounded-[32rpx]" /></view>
						<view>预约订单</view>
					</view>
				</view>
			</view>
		</view>
		
		<u-loading-page bg-color="var(--page-bg-color)" :loading="loading" fontSize="16" color="var(--primary-color-dark)"></u-loading-page>
		<!-- 添加打卡弹窗 -->
		<u-popup :show="showAddWorkPopup" @close="showAddWorkPopup = false" mode="bottom" :round="10" :closeable="true">
		    <view class="text-center p-[30rpx] font-500">设置工作状态</view>
		    <scroll-view scroll-y="true" class="h-[50vh]">
				
				<view class="px-[50rpx]">
					<view class="text-[30rpx] leading-[30rpx] py-[30rpx] border-[var(--page-bg-color)] border-solid border-0 border-bottom-[2rpx]" v-for="(item, index) in workStatusList" :key="index" @click="changeWorkStatus(index)">
						<view class="flex leading-[36rpx]">
							<text :class="item.icon" class="text-[36rpx]"></text>
							<text class="ml-[15rpx]">{{item.name}} </text>
							<text class="text-color-[var(--primary-color)]" v-if="index == workStatusIndex">（当前状态）</text>
							<text :class="(index == workStatusIndex) ? 'ml-auto text-[36rpx] text-color-[var(--primary-color)] zzhc icon-select' : 'ml-auto text-[36rpx]  zzhc icon-noselect'" ></text>
						</view>
						<view class="mt-[40rpx] text-[var(--primary-color-dark)] border-[var(--page-bg-color)] border-solid border-[2rpx] p-[30rpx]" v-if="workStatusIndex == index && workStatusIndex== 4">
							下班休息,顾客不能再预约。
						</view>
						
						<view class="mt-[40rpx] text-[var(--primary-color-dark)] border-[var(--page-bg-color)] border-solid border-[2rpx] p-[30rpx]" v-if="workStatusIndex == index && workStatusIndex== 3">
							停止接单,顾客不能再预约。
						</view>
						
						<view class="mt-[40rpx] text-[var(--primary-color-dark)] border-[var(--page-bg-color)] border-solid border-[2rpx] p-[30rpx]" v-if="workStatusIndex == index && (workStatusIndex== 1 || workStatusIndex== 2)">
							<view class="flex h-[60rpx] leading-[60rpx]">
								<text>预计用时:</text>
								<u-input type="number" v-model="duration" class="mx-[20rpx]" />
								<text>分钟</text>
							</view>
							<view class="mt-[30rpx]">用时计入排队时间,可继续预约。</view>
						</view>
					</view>
				
					<view class="mt-[50rpx] flex ">
						<view class="text-[26rpx] leading-[48rpx] text-[var(--primary-color-dark)]">位置：{{isLoc ? '定位失败' : full_address}}</view>
						<view class="ml-auto bg-[var(--primary-color)] rounded-[10rpx] text-[26rpx] px-[30rpx] h-[48rpx] leading-[48rpx] text-[#ffffff]" @click="isLoc = true;resetLoc()">定位</view>
					</view>
				</view>
		       
		    </scroll-view>
		    <view class="p-[30rpx]">
		        <u-button type="primary" shape="circle" @click="confirmAddWork">确认考勤打卡</u-button>
		    </view>
			
		</u-popup>
	</view>
</template>

<script setup lang="ts">
	import { reactive, ref, watch } from 'vue'
	import { img, redirect } from '@/utils/common'
	import { onLoad,onShow } from '@dcloudio/uni-app'
	import { getBarberInfo, barberWorkAdd } from '@/addon/zzhc/api/merchant'
	import { currStoreId,getLoc } from '@/addon/zzhc/utils/common';
	import useSystemStore from '@/stores/system';
	import { getAddressByLatlng } from '@/app/api/system';

	let info = ref({});
	let work = ref({});
	let store = ref({});
	let orderCount = ref({});
	let orderMoney = ref({});
	let loading = ref(true);
	let showAddWorkPopup = ref(false);
	let storeId = ref(0);
	
	// 考勤状态
	let workStatusList = ref([
		{ name: '上班中', value: 'working',icon:'zzhc icon-shangban' },
		{ name: '用餐中', value: 'meal',icon:'zzhc icon-yongcan' },
		{ name: '处理事情中', value: 'thing',icon:'zzhc icon-shimang' },
		{ name: '停止接单', value: 'stop',icon:'zzhc icon-tingzhi' },
		{ name: '下班休息', value: 'rest',icon:'zzhc icon-xiaban' },
	])
	// 当前考勤
	let workStatusIndex = ref(-1);
	let duration = ref(0); //预计用时
	let full_address = ref('');
	let latitude = ref('');
	let longitude = ref('');
	let isLoc = ref(true);
	
	
	onLoad(() => {
		loading.value = true;
		storeId.value = currStoreId();
		getBarberInfoFn();
		resetLoc();
	})
	
	
	const getBarberInfoFn = () => {
		getBarberInfo(storeId.value).then((res) => {
			loading.value = false;
			info.value = res.data.info;
			work.value = res.data.work;
			store.value = res.data.store;
			orderCount.value = res.data.order_count;
			orderMoney.value = res.data.order_money;
			
			if(work.value){
				workStatusList.value.forEach((item,index)=>{
					if(item.value == work.value.status){
						workStatusIndex.value = index;
						duration.value = work.value.duration;
					}
				})
			}
			
		}).catch((res)=>{
			console.log('res',res);
			uni.showModal({
				title: '温馨提示',
				content: res.msg,
				confirmText:'切换门店',
				cancelText:'返回',
				success: res => {
					if (res.confirm) {
						redirect({ url: '/addon/zzhc/pages/store/list' })
					}else{
						uni.navigateBack({
							delta: 1
						});
					}
				}
			})
			
		});
	}
	
	const toOrder = (status:number) => {
		redirect({ url: '/addon/zzhc/pages/merchant/barber/order/list', param: { status } })
	}
	
	// 确认考勤
	const confirmAddWork = () => {
		
		if(work.value && work.value.status == workStatusList.value[workStatusIndex.value]['value']){
			wx.showToast({title:'工作状态未改变',icon:'none'});
			return false;
		}
		
		if(workStatusIndex.value == -1){
			wx.showToast({title:'请设置工作状态',icon:'none'});
			return false;
		}
		if((workStatusIndex.value == 1 || workStatusIndex.value == 2) && duration.value == ''){
			wx.showToast({title:'预计用时不能为空',icon:'none'});
			return false;
		}
		let data = {store_id:storeId.value,status:workStatusList.value[workStatusIndex.value]['value'],duration:duration.value,full_address:full_address.value,latitude:latitude.value,longitude:longitude.value};
		barberWorkAdd(data).then((res) => {
			showAddWorkPopup.value = false;
			getBarberInfoFn();
		})
	}
	
	// 更改考勤状态
	const changeWorkStatus = (index: number) => {
		workStatusIndex.value = index;
		if(index == 0 || index == 3 || index == 4){
			duration.value = 0;
		}else{
			duration.value = 30;
		}
	}
	
	// 定位
	const resetLoc = () => {
		getLoc().then((res: any) => {
			console.log(res);
			let data = {
				latlng: res.latitude + ',' + res.longitude
			};
			latitude.value = res.latitude;
			longitude.value = res.longitude;
			getAddressByLatlng(data).then((res : any) => {
				if (res.data && Object.keys(res.data).length) {
					isLoc.value = false;
					full_address.value = res.data.full_address;
				}
			})
		},(res: any)=>{
			console.log('res',res)
		});
	}
	
</script>
<style>
	@import '@/addon/zzhc/styles/common.scss';
</style>