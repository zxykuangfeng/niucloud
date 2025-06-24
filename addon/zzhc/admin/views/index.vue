<template>
    <div v-if="!loading">
        <el-card class="box-card !border-none" shadow="never">
            <div class="p-[30px] box-border border-[1px] border-[var(--el-border-color)] border-solid bg-[var(--el-bg-color)]">
                <el-card class="box-card !border-none profile-data" shadow="never">
                    <template #header>
                        <div class="card-header mb-[20px] w-full">
                            <span class="text-[18px]">{{ t("数据概况") }}</span>
                            <span class="text-[12px] text-[#666] leading-[16px] ml-[18px]">更新时间 : </span>
                            <span class="text-[12px] text-[#666] leading-[16px]">{{ time }}</span>
                        </div>
                    </template>

                    <el-row :gutter="20" class="mt-[20px] top">
						<el-col>
						    <div @click="toLink('/order/order')" class="cursor-pointer">
						        <el-statistic :value="statInfo.order_money.total" precision = "2">
						            <template #title>
						                <div class="text-[14px] mb-[9px] text-[#666]">
						                    总收入
						                </div>
						            </template>
						        </el-statistic>
						    </div>
						</el-col>
                        <el-col>
                            <div @click="toLink('/order/order')" class="cursor-pointer">
                                <el-statistic :value="statInfo.order_money.today" precision = "2">
                                    <template #title>
                                        <div class="text-[14px] mb-[9px] text-[#666]">
                                            今日收入
                                        </div>
                                    </template>
                                </el-statistic>
                            </div>
                        </el-col>
                        <el-col>
                            <div @click="toLink('/order/order')" class="cursor-pointer">
                                <el-statistic :value="statInfo.order_money.yesterday" precision = "2">
                                    <template #title>
                                        <div class="text-[14px] mb-[9px] text-[#666]">
                                            昨日收入
                                        </div>
                                    </template>
                                </el-statistic>
                            </div>
                        </el-col>
                        <el-col>
                            <div @click="toLink('/order/order')" class="cursor-pointer">
                                <el-statistic :value="statInfo.order_money.month" precision = "2">
                                    <template #title>
                                        <div class="text-[14px] mb-[9px] text-[#666]">
                                            本月收入
                                        </div>
                                    </template>
                                </el-statistic>
                            </div>
                        </el-col>
                        <el-col>
                            <div @click="toLink('/order/order')" class="cursor-pointer">
                                <el-statistic :value="statInfo.order_money.year" precision = "2">
                                    <template #title>
                                        <div class="text-[14px] mb-[9px] text-[#666]">
                                            今年收入
                                        </div>
                                    </template>
                                </el-statistic>
                            </div>
                        </el-col>
                        
                    </el-row>
					
					<el-row :gutter="20" class="mt-[20px] top">
						<el-col>
						    <div @click="toLink('/resident/resident')" class="cursor-pointer">
						        <el-statistic :value="statInfo.order_money.wait_pay" precision = "2">
						            <template #title>
						                <div class="text-[14px] mb-[9px] text-[#666]">
						                    待支付金额
						                </div>
						            </template>
						        </el-statistic>
						    </div>
						</el-col>
					    <el-col>
					        <div @click="toLink('/resident/resident')" class="cursor-pointer">
					            <el-statistic :value="statInfo.order_count.wait_pay" >
					                <template #title>
					                    <div class="text-[14px] mb-[9px] text-[#666]">
					                        待支付订单
					                    </div>
					                </template>
					            </el-statistic>
					        </div>
					    </el-col>
					    <el-col>
					        <div @click="toLink('/resident/resident')" class="cursor-pointer">
					            <el-statistic :value="statInfo.order_count.finish" >
					                <template #title>
					                    <div class="text-[14px] mb-[9px] text-[#666]">
					                        已完成订单
					                    </div>
					                </template>
					            </el-statistic>
					        </div>
					    </el-col>
					    <el-col>
					        <div @click="toLink('/resident/resident')" class="cursor-pointer">
					            <el-statistic :value="statInfo.order_count.wait_service">
					                <template #title>
					                    <div class="text-[14px] mb-[9px] text-[#666]">
					                        待服务订单
					                    </div>
					                </template>
					            </el-statistic>
					        </div>
					    </el-col>
					    
					    <el-col>
					        <div @click="toLink('/member/member')" class="cursor-pointer">
					            <el-statistic :value="statInfo.order_count.in_service">
					                <template #title>
					                    <div class="text-[14px] mb-[9px] text-[#666]">
					                        服务中订单
					                    </div>
					                </template>
					            </el-statistic>
					        </div>
					    </el-col>
					</el-row>
                </el-card>
                
                <div class="flex justify-between mt-[15px]">
                    
                    <div class="flex-1 h-[145px] bg-[var(--el-color-info-light-9)] flex justify-center flex-col items-center cursor-pointer mr-[25px]" @click="toLink('/build/house')">
                    	<div class="flex">
                    		<img class="w-[56px] h-[56px] rounded-[28px]" src="@/addon/zzhc/assets/store.png" />
							<div class="ml-[10px]">
								<div>
									<span class="text-[16px] ">门店</span>
								</div>
								<div>
									<span class="text-[20px]">{{ statInfo.people_count.store }}</span>
								</div>
							</div>
                    	</div>
                    </div>
					<div class="flex-1 h-[145px] bg-[var(--el-color-info-light-9)] flex justify-center flex-col items-center cursor-pointer mr-[25px]" @click="toLink('/build/shop')">
						<div class="flex">
							<img class="w-[56px] h-[56px] rounded-[28px]" src="@/addon/zzhc/assets/barber.png" />
							<div class="ml-[10px]">
								<div>
									<span class="text-[16px] ">发型师</span>
								</div>
								<div>
									<span class="text-[20px]">{{ statInfo.people_count.barber }}</span>
								</div>
							</div>
						</div>
					</div>
					<div class="flex-1 h-[145px] bg-[var(--el-color-info-light-9)] flex justify-center flex-col items-center cursor-pointer mr-[25px]" @click="toLink('/build/parking')">
						<div class="flex">
							<img class="w-[56px] h-[56px] rounded-[28px]" src="@/addon/zzhc/assets/manage.png" />
							<div class="ml-[10px]">
								<div>
									<span class="text-[16px] ">店长</span>
								</div>
								<div>
									<span class="text-[20px]">{{ statInfo.people_count.manage }}</span> 
								</div>
							</div>
						</div>
					</div>
					<div class="flex-1 h-[145px] bg-[var(--el-color-info-light-9)] flex justify-center flex-col items-center cursor-pointer mr-[25px]" @click="toLink('/build/garage')">
						<div class="flex">
							<img class="w-[56px] h-[56px] rounded-[28px]" src="@/addon/zzhc/assets/member.png" />
							<div class="ml-[10px]">
								<div>
									<span class="text-[16px] ">会员</span>
								</div>
								<div>
									<span class="text-[20px]">{{ statInfo.people_count.member }}</span> 个
								</div>
							</div>
						</div>
					</div>
                </div>

                <div class="mt-[30px] flex site">
                    <el-card class="box-card !border-none flex-1 profile-data mr-[30px]" shadow="never">
						<div ref="visitStat" :style="{ width: '100%', height: '300px' }"></div>
                    </el-card>
                    <el-card class="box-card !border-none flex-1 profile-data" shadow="never">
                        <div ref="hourStat" :style="{ width: '100%', height: '300px' }"></div>
                    </el-card>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { t } from '@/lang'
import { getStatInfo } from '@/addon/zzhc/api/stat'
import * as echarts from 'echarts'
import { useRoute, useRouter } from 'vue-router'
import { AnyObject } from '@/types/global'

const loading = ref(true)

const visitStat = ref<any>(null)
const hourStat = ref<any>(null)


const statTotal = ref([])
const statToday = ref([])
const statYesterday = ref([])
const statCount = ref([])
const statOrder = ref([])
const statGoods = ref([])


const statInfo = ref([]);
const getStatInfoFn = async () => {
    statInfo.value = await (await getStatInfo()).data
    loading.value = false
    setTimeout(() => {
        drawChart('')
        drawChartTo('')
    }, 20)
}
getStatInfoFn()

const drawChart = (item:any) => {
	
    let value = statInfo.value.stat_order.order_num
    if (item) value = item
    if (!visitStat.value) return
	
    const visitStatChart = echarts.init(visitStat.value)
    const visitStatOption = ref({
        title: {
            text: '预约订单'
        },
        legend: {},
        xAxis: {
            data: []
        },
        yAxis: {},
        tooltip: {
            trigger: 'axis'
        },
        series: [
            {
                type: 'line',
                data: []
            }
        ]
    })
    visitStatOption.value.xAxis.data = statInfo.value.stat_order.date
    visitStatOption.value.series[0].data = value
    visitStatChart.setOption(visitStatOption.value)
}
const drawChartTo = (item:any) => {
    let valueTo = statInfo.value.stat_order.order_money
    if (item) valueTo = item
    if (!hourStat.value) return
    const hourStatChart = echarts.init(hourStat.value)
    const hourStatOption = ref({
        title: {
            text: '收入金额'
        },
        legend: {},
        xAxis: {
            data: []
        },
        yAxis: {},
        tooltip: {
            trigger: 'axis'
        },
        series: [
            {
                type: 'line',
                data: []
            }
        ]
    })
    hourStatOption.value.xAxis.data = statInfo.value.stat_order.date
    hourStatOption.value.series[0].data = valueTo
    hourStatChart.setOption(hourStatOption.value)
}

const router = useRouter()
const route = useRoute()

/**
 * 链接跳转
 */
const toLink = (link:any) => {
    router.push(link)
}

// 更新时间
const time = ref('')
const nowTime = () => {
    const date = new Date()
    const year = date.getFullYear()
    const month = date.getMonth() + 1
    const day = date.getDate()
    const hh = checkTime(date.getHours())
    const mm = checkTime(date.getMinutes())
    const ss = checkTime(date.getSeconds())
    function checkTime (i:any) {
        if (i < 10) {
            return '0' + i
        }
        return i
    }
    time.value = year + '-' + month + '-' + day + ' ' + hh + ':' + mm + ':' + ss
}
nowTime()

</script>

<style lang="scss" scoped>
    .profile-data {
        background-color: transparent !important;
    }

    :deep(.profile-data .el-card__header) {
        padding: 0 !important;
    }

    :deep(.profile-data .el-card__body) {
        padding: 20px 0 !important;
    }
    .top :deep(.el-col){
        max-width: calc(100% / 5) !important;
    }
</style>
