<template>
    <view class="bg-[var(--page-bg-color)] min-h-[100vh]" :style="themeColor()">
        <view class="sidebar-margin bg-[#fff] rounded-[16rpx] mt-[30rpx]">
            <view class="card-template">
                <view class="mb-[30rpx px-[20rpx]">
                    <view class="flex items-center mb-[30rpx]">
                        <text class="iconfont iconshangyibu text-[#303133] text-[20rpx]" @click="changeMonth('prev')"></text>
                        <view class="mx-[24rpx] w-[600rpx] text-[30rpx] font-500 text-[#303133] leading-[45rpx] text-center">{{ state.curYear }}年{{ state.curMonth+1 }}月</view>
                        <text class="iconfont iconxiayibu1 text-[#303133] ml-auto text-[20rpx]" @click="changeMonth('next')"></text>
                    </view>
                </view>
                
                <view class="relative z-9 bg-[#fff] rounded-[18rpx]">
                    <view>
                        <view class="flex items-center justify-between text-[var(--text-color-light9)] text-[24rpx] mb-[16rpx]">
                            <text class="w-[14.28%] leading-[36rpx] text-center">周一</text>
                            <text class="w-[14.28%] leading-[36rpx] text-center">周二</text>
                            <text class="w-[14.28%] leading-[36rpx] text-center">周三</text>
                            <text class="w-[14.28%] leading-[36rpx] text-center">周四</text>
                            <text class="w-[14.28%] leading-[36rpx] text-center">周五</text>
                            <text class="w-[14.28%] leading-[36rpx] text-center">周六</text>
                            <text class="w-[14.28%] leading-[36rpx] text-center">周日</text>
                        </view>
                        
                        <view class="flex flex-wrap items-center justify-start">
                            <block v-for="(item,index) in state.dataCount" :key="index">
                                <view  class="w-[14.28%] flex flex-col justify-center items-center">
                                    <view v-if="filteredDate(item)" class="w-[74rpx] h-[92rpx] bg-[#F6FAFF] text-[var(--text-color-light6)] box-border py-[10rpx] rounded-[8rpx] flex flex-col  items-center" :class="{'!text-[var(--primary-color)]': isVerDate(item) ,'!bg-[#FDFDFD] border-[1rpx] border-[#F0F4FA] border-solid': !isVerDate(item) && item < state.curDate && (state.curMonth + 1) == (new Date().getMonth() + 1) && state.curYear == new Date().getFullYear() ,'mb-[20rpx]':isCurrentDate(item),'mb-[30rpx]':!isCurrentDate(item)}" @click="getDayWorkFn(item)">
                                        <text class="text-[24rpx] leading-[28rpx] mb-[6rpx]">{{ filteredDate(item) }}</text>
                                        <view class="flex items-center pt-[20rpx] justufy-center">
        									
        									<view v-if="isVerDate(item)" class="w-[15rpx] h-[15rpx] rounded-[50%] bg-[var(--primary-color)]" ></view>
        									<view v-else class="w-[15rpx] h-[15rpx] rounded-[50%] bg-[var(--primary-color-dark)]"></view>
                                        </view>
                                    </view>
                                    
                                </view>
                            </block>
                        </view>
                    </view>
                </view>
            
				<view class="mx-[20rpx]">
					<view>本月出勤<text class="text-[var(--primary-color)] mx-[5rpx]">{{state.workInList.length}}</text>天</view> 
					<view class="flex mt-[20rpx]">
						<view class="mr-[10rpx] mb-[20rpx] w-[50rpx] text-[26rpx] text-center py-10rpx text-[#fff] bg-[var(--primary-color)] rounded-[8rpx]" v-for="(item,index) in state.workInList" :key="index" @click="getDayWorkFn(item)">{{item}}</view>
					</view>
					
				</view>
				
			</view>
			
			
        </view>
		
		<!-- 添加打卡弹窗 -->
		<u-popup :show="showWorkPopup" @close="showWorkPopup = false" mode="bottom" :round="10" :closeable="true">
		    <view class="text-center p-[30rpx] font-500">{{workDate}} 考勤详细记录</view>
		    <scroll-view scroll-y="true" class="h-[50vh]">
				
				<view class="m-[30rpx]">
					<view v-for="(item,index) in workList" :key="index" class="flex mb-[30rpx]">
						
						<view :class="item.status" class="w-[100rpx] text-[#fff] h-[100rpx] text-center leading-[100rpx] rounded-[16rpx]">
							{{item.status_name}}
						</view>
						
						<view class="ml-[20rpx]">
							<view>{{item.create_time}}</view>
							<view class="text-[var(--primary-color-dark)]">{{item.full_address}}</view>
						</view>
					</view>
				</view>
		       
		    </scroll-view>
		   
			
		</u-popup>
        
		<u-loading-page bg-color="var(--page-bg-color)" :loading="loading" fontSize="16" color="var(--primary-color-dark)"></u-loading-page>
    </view>
</template>

<script setup lang="ts">
import { reactive, ref, toRefs, toRaw, computed } from 'vue'
import { redirect, img,pxToRpx } from '@/utils/common'
import { onLoad } from '@dcloudio/uni-app'
import { getBarberWorkInfo,getBarberWorkDateInfo } from '@/addon/zzhc/api/merchant'
import { currStoreId } from '@/addon/zzhc/utils/common';
import { topTabar } from '@/utils/topTabbar'

const state = reactive({
    dataCount:[], //当月所有天数
    weekCount:[], //如果打卡周期是7天
    curYear:0, // 当前年
    curMonth:0, //当前月
    curDate:0, //当前日
    curWeek:0, //当前星期
    workInList:[], // 打卡列表
})
const week = reactive({
    weekDay:0, //当前天
    week:0 //当前星期
})
const loading = ref(false)
const flag = ref(true)
const storeId = ref(0);
let currentYear: any = null
let currentMonth: any = null

const workList = ref([]);
const showWorkPopup = ref(false);
const workDate = ref('');

// 考勤状态
let workStatusList = ref([
	{ name: '上班中', value: 'working',icon:'zzhc icon-shangban' },
	{ name: '用餐中', value: 'meal',icon:'zzhc icon-yongcan' },
	{ name: '处理事情中', value: 'thing',icon:'zzhc icon-shimang' },
	{ name: '停止接单', value: 'stop',icon:'zzhc icon-tingzhi' },
	{ name: '下班休息', value: 'rest',icon:'zzhc icon-xiaban' },
])

onLoad(() =>{
    let date=new Date()
    state.curYear=date.getFullYear()
    state.curMonth=date.getMonth()
    state.curDate=date.getDate()
    state.curWeek=date.getDay()
	storeId.value = currStoreId();
    if(state.curWeek==0) state.curWeek = 7

    currentYear=toRaw(state.curYear)
    currentMonth=toRaw(state.curMonth)

    //初始化执行
    getDayCounts()
    getWeekCounts()
    getBarberWorkInfoFn({store_id:storeId.value,year:state.curYear,month:state.curMonth+1})
})


// 每个周期打卡日期
const getBarberWorkInfoFn =  (data:any) =>{
    getBarberWorkInfo(data).then((res:any) =>{
        state.workInList = []
		console.log('res.data.length',res.data.length)
        if(res.data.days.length){
            state.workInList = res.data.days.map((el:any) =>{
                return Number(el)
            })
        }
    })
}

//获取当月总天数
const getDayCounts= () => {
    let counts = new Date(state.curYear,state.curMonth+1,0).getDate()
    //获取当前第一天是星期几
    let firstWeekDay = new Date(state.curYear,state.curMonth,1).getDay()
    state.dataCount = []
    for(let i=1;i<counts+firstWeekDay;i++){
        let val=i-firstWeekDay+ 1
        state.dataCount.push(val)
    }
}
// 获取7天的日期打卡
const getWeekCounts = () =>{
    let now = `${state.curYear}-${state.curMonth+1 > 10 ? state.curMonth+1 : '0'+(state.curMonth+1)}-${state.curDate > 10 ? state.curDate : '0'+state.curDate }`
    for (let i = state.curWeek - 1; i >= 0; i --) {
        const day = new Date(now).getDate() - i
        state.weekCount.push(day)
    }
    for (let i = 1; i <= 7 - state.curWeek; i++) {
        const day = new Date(now).getDate() + i
        state.weekCount.push(day)
    }
}

//更改月份
const changeMonth=(type: string)=>{
 state.dataCount=[]
 if(type == 'prev'){
    state.curMonth--
    if(state.curMonth < 0){
        state.curMonth = 11
        state.curYear--
    }
    week.weekDay = 1
 }else{
    state.curMonth++
    if(state.curMonth > 11){
        state.curMonth = 0
        state.curYear++
    }
    week.weekDay = 1
 }
 let data = {store_id:currStoreId(),year:state.curYear,month:state.curMonth + 1}
 getBarberWorkInfoFn(data)
 getDayCounts()
}

// 查看每日打卡
const getDayWorkFn = (date:number) =>{
    console.log(date)
	if(isVerDate(date)){
		workDate.value = state.curYear+'-'+(state.curMonth + 1)+'-'+date;
		let data = {store_id:currStoreId(),date: workDate.value }
		getBarberWorkDateInfo(data).then((res:any) =>{
			
			showWorkPopup.value = true;
		    workList.value = res.data;
		})
	}else{
		uni.showToast({
			title:'无打卡记录',
			icon:'none'
		})
	}
}

// 判断是否打卡
const isVerDate = (val:any) => {
    return state.workInList.includes(val)
}

//判断是否是当前日期
const isCurrentDate=(date: any)=>{
    if(date> 0 && date <= state.dataCount.length){
        if(date == state.curDate && currentYear == state.curYear && currentMonth == state.curMonth){
            return true
        }
    }else{
        return false
    }
}


// 过滤日期
const filteredDate=(date :any)=>{
    return date > 0 ? date : ''
}


</script>

<style>
	@import '@/addon/zzhc/styles/common.scss';
</style>
<style lang="scss">
	.working{background-color: var(--primary-color);}
	.meal{background-color: var(--price-text-color);}
	.thing,.stop{background-color: #FA8241;}
	.rest{background-color: var(--main-text-color);}
</style>