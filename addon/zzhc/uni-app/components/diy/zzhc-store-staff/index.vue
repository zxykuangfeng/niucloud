<template>
	<view :style="warpCss" class="zzhc-store-staff">
		<block v-if="!loading && storeInfo.store_id">
			<view class="store-info flex bg-white rounded-[20rpx] p-[30rpx]">
				<view class="l" @click="toStoreDetail(storeInfo.store_id)">
					<view class="flex">
						<u--image :src="img(storeInfo.store_logo)" shape="circle" width="72rpx" height="72rpx" class="rounded-[36rpx]" >
							<template #error>
								<u-icon name="photo" color="var(--primary-color-dark)" size="50" sh ></u-icon>
							</template>
						</u--image>
						
						<view class="ml-[10rpx] text-[36rpx] leading-[72rpx] using-hidden">{{storeInfo.store_name}}</view>
					</view> 
					<view class="text-[var(--primary-color-dark)] text-[24rpx] mt-[20rpx]">
						<view class="flex leading-[24rpx]">
							<u-icon name="map" color="var(--primary-color-dark)" size="17"></u-icon>
							<text class="ml-[5rpx]">{{storeInfo.address}}</text>
						</view>
						<view class="flex leading-[24rpx] mt-[15rpx]">
							<u-icon name="clock" color="var(--primary-color-dark)" size="15"></u-icon>
							<text class="ml-[5rpx]">营业时间:{{storeInfo.trade_time}}</text>
						</view>
					</view>
				</view>
				
				<view class="ml-auto w-[182rpx] flex">
					<view class="bg-[var(--page-bg-color)] w-[2rpx] my-[15rpx]"></view>
					<view class="ml-[28rpx]">
						<view class="text-[var(--primary-color)] text-center border-[var(--primary-color)] border-solid text-[24rpx] rounded-[26rpx] h-[48rpx] leading-[48rpx] border-[2rpx] w-[140rpx]" @click="toStore">切换门店</view>
						<view class="text-[var(--primary-color-dark)] leading-[24rpx] mt-[20rpx] text-[24rpx]">距离{{distanceFormat(storeInfo.distance)}}</view>
						<view class="flex mt-[20rpx]">
							<image :src="img('/addon/zzhc/dh.png')" @click="openMap.openMap(storeInfo.latitude,storeInfo.longitude, storeInfo.full_address)" class="w-[48rpx] h-[48rpx]" />
							<image :src="img('/addon/zzhc/phone.png')" @click="callPhone(storeInfo.store_mobile)" class="w-[48rpx] h-[48rpx] ml-[30rpx]" />
						</view>
					</view>
					
				</view>
			</view>
			<view class="staff-list bg-white rounded-[20rpx] mt-[30rpx] p-[20rpx]">
				<view class="flex border-[var(--page-bg-color)] border-solid border-0 border-bottom-[2rpx] leading-[32rpx] pb-[20rpx]">
					<view class="text-[32rpx] leading-[32rpx]">发型师推荐</view>
					<view class="text-[var(--primary-color-dark)] flex ml-auto text-[24rpx] leading-[24rpx] h-[24rpx] mt-[4rpx]" @click="toBarber">
						<text>查看更多</text>
						<u-icon name="arrow-right" size="15"></u-icon>
					</view>
				</view>
				
				<view v-for="(item,index) in barberList" :key="index" class="flex mt-[30rpx]" @click="toBarberDetail(item.staff_id)">
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
						<view class="text-[26rpx] mt-[20rpx] text-[var(--primary-color-dark)]"><text>职级：</text>{{item.staff_position}}</view>
						<view class="flex text-[26rpx] mt-[25rpx] leading-[56rpx]">
							<view class="text-[var(--primary-color-dark)]">前面<text class="text-[var(--primary-color)]">{{item.wait_people}}</text>人｜约<text class="text-[#FA8241]">{{item.wait_duration}}</text>分钟</view>
							
							<view class="ml-auto text-[#FA8241] w-[130rpx] h-[56rpx] rounded-[28rpx] bg-[#FFEDE4] text-center" @click.stop="toSelectGoods(item.staff_id)" v-if="item.work != null && (item.work.status == 'working' || item.work.status == 'meal' || item.work.status == 'thing') ">去取号</view>
							
							<view v-else-if="item.work != null && item.work.status == 'stop'" class="ml-auto text-[var(--primary-color-dark)] w-[130rpx] h-[56rpx] rounded-[28rpx] bg-[var(--page-bg-color)] text-center" >停止接单</view>
							
							<view v-else class="ml-auto text-[var(--primary-color-dark)] w-[130rpx] h-[56rpx] rounded-[28rpx] bg-[var(--page-bg-color)] text-center" >休息中</view>
						</view>
					</view>
				</view>
				<view v-if="barberList.length == 0">
					<u-empty mode="data" text="暂无发型师" />
				</view>
			</view>
		</block>
		<block v-else>
			<view class="mx-[auto] text-[var(--primary-color)] text-center border-[var(--primary-color)] border-solid text-[24rpx] rounded-[26rpx] h-[48rpx] leading-[48rpx] border-[2rpx] w-[140rpx]" @click="toStore">选择门店</view>
		</block>
	</view>
</template>

<script setup lang="ts">
	// 商品列表
	import { ref, computed, watch, onMounted } from 'vue';
	import { redirect, img } from '@/utils/common';
	import { currStoreId,distanceFormat,getLoc,callPhone } from '@/addon/zzhc/utils/common';
	import useDiyStore from '@/app/stores/diy';
	import { getStoreStaffComponents } from '@/addon/zzhc/api/store';
	import openMap from '@/addon/zzhc/utils/map/openMap.js'

	const props = defineProps(['component', 'index', 'pullDownRefreshCount']);
	const diyStore = useDiyStore();

	const storeInfo = ref({
		
	});
	const barberList = ref<Array<any>>([]);
	const loading = ref(true);

	const diyComponent = computed(() => {
		if (diyStore.mode == 'decorate') {
			return diyStore.value[props.index];
		} else {
			return props.component;
		}
	})

	const warpCss = computed(() => {
		var style = '';
		if (diyComponent.value.componentBgColor) style += 'background-color:' + diyComponent.value.componentBgColor + ';';
		if (diyComponent.value.topRounded) style += 'border-top-left-radius:' + diyComponent.value.topRounded * 2 + 'rpx;';
		if (diyComponent.value.topRounded) style += 'border-top-right-radius:' + diyComponent.value.topRounded * 2 + 'rpx;';
		if (diyComponent.value.bottomRounded) style += 'border-bottom-left-radius:' + diyComponent.value.bottomRounded * 2 + 'rpx;';
		if (diyComponent.value.bottomRounded) style += 'border-bottom-right-radius:' + diyComponent.value.bottomRounded * 2 + 'rpx;';
		return style;
	})

	watch(() => props.pullDownRefreshCount,(newValue, oldValue) => {
			// 处理下拉刷新业务
		}
	)

	onMounted(() => {
		refresh();
		// 装修模式下刷新
		if (diyStore.mode == 'decorate') {
			watch(
				() => diyComponent.value,
				(newValue, oldValue) => {
					if (newValue && newValue.componentName == 'ZzhcStoreStaff') {
						refresh();
					}
				}
			)
		}
	});

	const refresh = async () => {
		// 装修模式下设置默认数据
		if (diyStore.mode == 'decorate') {
			
			storeInfo.value = {
				store_id:1,
				store_logo:"",
				store_name:"门店名称",
				address:"门店地址",
				trade_time:"营业时间",
				distance:'800'
			};
			
			let staffInfo = {
				staff_headimg: "",
				staff_name: "理发师姓名",
				staff_position: "理发师职位",
				people: 0,
				time:0
			};
			
			
			barberList.value.push(staffInfo);
			loading.value = false;
		}else{
			let storeId = currStoreId(false);
			console.log('storeId',storeId)
			getLoc().then((res: any) => {
				console.log('res',res)
				getStoreStaffFn({store_id:storeId,longitude:res.longitude,latitude:res.latitude});
			},(res: any)=>{
				console.log('res',res)
				getStoreStaffFn({store_id:storeId,longitude:0,latitude:0});
			});
		}
	}
	
	const getStoreStaffFn = (params:any) => {
		let data = {
			store_id:params.store_id??0,
			latitude:params.latitude??'',
			longitude:params.longitude??'',
			source: diyComponent.value.store.source,
			num: diyComponent.value.staff.num,
		}
		getStoreStaffComponents(data).then((res) => {
			loading.value = false;
			barberList.value = res.data.barber_list;
			storeInfo.value = res.data.store_info;
			if(!data.store_id){
				uni.setStorageSync("zzhc:store",res.data.store_info);
			}
		});
	}

	const toBarber = () => {
		redirect({ url: '/addon/zzhc/pages/barber/list', mode: 'navigateTo' })
	}
	
	const toBarberDetail = (staff_id:number) => {
		redirect({ url: '/addon/zzhc/pages/barber/detail?staff_id='+staff_id, mode: 'navigateTo' })
	}
	
	const toStore = () => {
		redirect({ url: '/addon/zzhc/pages/store/list', mode: 'navigateTo' })
	}
	
	const toStoreDetail = (store_id:number) => {
		redirect({ url: '/addon/zzhc/pages/store/detail?store_id='+store_id, mode: 'navigateTo' })
	}
	
	const toSelectGoods = (staff_id:number) => {
		redirect({ url: '/addon/zzhc/pages/goods/select?staff_id='+staff_id, mode: 'navigateTo' })
	}
	
</script>

