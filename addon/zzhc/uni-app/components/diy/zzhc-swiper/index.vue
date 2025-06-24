<template>
	<view :style="warpCss" class="zzhc-swiper">
		<view class="relative">
			<!-- 轮播图 -->
			<view class="relative">
				<swiper class="swiper" :style="{ height: imgHeight }" autoplay="true" circular="true" @change="swiperChange"
					:class="{
						'swiper-left': diyComponent.swiper.indicatorAlign == 'left',
						'swiper-right': diyComponent.swiper.indicatorAlign == 'right',
						'ns-indicator-dots': diyComponent.swiper.indicatorStyle == 'style-2'
					}"
					:previous-margin="swiperStyle2 ? 0 : '30rpx'" :next-margin="swiperStyle2 ? 0 : '30rpx'"
				    :interval="diyComponent.swiper.interval * 1000" :indicator-dots="isShowDots"
				    :indicator-color="diyComponent.swiper.indicatorColor" :indicator-active-color="diyComponent.swiper.indicatorActiveColor">
					<swiper-item class="swiper-item" v-for="(item,index) in diyComponent.swiper.list" :key="item.id" :style="swiperWarpCss">
						<view @click="diyStore.toRedirect(item.link)">
							<view class="item" :style="{height: imgHeight}">
								<image v-if="item.imageUrl" :src="img(item.imageUrl)" mode="scaleToFill" :style="swiperWarpCss" :class="['w-full h-full',{'swiper-animation': swiperIndex != index}]" :show-menu-by-longpress="true"/>
								<image v-else :src="img('static/resource/images/diy/figure.png')" :style="swiperWarpCss" mode="scaleToFill" :class="['w-full h-full',{'swiper-animation': swiperIndex != index}]" :show-menu-by-longpress="true"/>
							</view>
						</view>
					</swiper-item>
				</swiper>
				<!-- #ifdef MP-WEIXIN -->
				<view v-if="diyComponent.swiper.list.length > 1" :class="[
						'swiper-dot-box',
						{ 'straightLine': diyComponent.swiper.indicatorStyle == 'style-2' },
						{ 'swiper-left': diyComponent.swiper.indicatorAlign == 'left' },
						{ 'swiper-right': diyComponent.swiper.indicatorAlign == 'right' }
					]">
					<view v-for="(numItem, numIndex) in diyComponent.swiper.list" :key="numIndex" :class="['swiper-dot', { active: numIndex == swiperIndex }]" :style="[numIndex == swiperIndex ? { backgroundColor: diyComponent.swiper.indicatorActiveColor } : { backgroundColor: diyComponent.swiper.indicatorColor }]"></view>
				</view>
				<!-- #endif -->
			</view>
			
		</view>
		
	</view>
</template>

<script setup lang="ts">
	// 轮播广告
	import { ref, computed, watch, onMounted } from 'vue';
	import { img } from '@/utils/common';
	import useDiyStore from '@/app/stores/diy';

	const props = defineProps(['component', 'index', 'pullDownRefreshCount', 'global', 'scrollBool']);
	const diyStore = useDiyStore();
	
	const diyComponent = computed(() => {
		if (diyStore.mode == 'decorate') {
			return diyStore.value[props.index];
		} else {
			return props.component;
		}
	})
	
	const warpCss = computed(() => {
		var style = '';
        if(diyComponent.value.componentStartBgColor) {
            if (diyComponent.value.componentStartBgColor && diyComponent.value.componentEndBgColor) style += `background:linear-gradient(${diyComponent.value.componentGradientAngle},${diyComponent.value.componentStartBgColor},${diyComponent.value.componentEndBgColor});`;
            else style += 'background-color:' + diyComponent.value.componentStartBgColor + ';';
        }
		if (diyComponent.value.topRounded) style += 'border-top-left-radius:' + diyComponent.value.topRounded * 2 + 'rpx;';
		if (diyComponent.value.topRounded) style += 'border-top-right-radius:' + diyComponent.value.topRounded * 2 + 'rpx;';
		if (diyComponent.value.bottomRounded) style += 'border-bottom-left-radius:' + diyComponent.value.bottomRounded * 2 + 'rpx;';
		if (diyComponent.value.bottomRounded) style += 'border-bottom-right-radius:' + diyComponent.value.bottomRounded * 2 + 'rpx;';
		return style;
	})

	// 轮播样式二
	const swiperStyle2 = computed(()=>{
		var style = diyComponent.value.swiper.swiperStyle == 'style-2' ? true : false;
		return style;
	})

    const imgHeight = computed(() => {
        return (diyComponent.value.swiper.imageHeight * 2) + 'rpx';
    })

    const swiperIndex = ref(0);

    const swiperChange = e => {
        swiperIndex.value = e.detail.current;
    };

    const swiperWarpCss = computed(() => {
        var style = '';
        if (diyComponent.value.swiper.topRounded) style += 'border-top-left-radius:' + diyComponent.value.swiper.topRounded * 2 + 'rpx;';
        if (diyComponent.value.swiper.topRounded) style += 'border-top-right-radius:' + diyComponent.value.swiper.topRounded * 2 + 'rpx;';
        if (diyComponent.value.swiper.bottomRounded) style += 'border-bottom-left-radius:' + diyComponent.value.swiper.bottomRounded * 2 + 'rpx;';
        if (diyComponent.value.swiper.bottomRounded) style += 'border-bottom-right-radius:' + diyComponent.value.swiper.bottomRounded * 2 + 'rpx;';
        return style;
    })
	
	watch(
		() => props.pullDownRefreshCount,
		(newValue, oldValue) => {
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
                    if (newValue && newValue.componentName == 'ZzhcSwiper') {
                        refresh();
                    }
                }
            )
        }
    });
	
	const refresh = ()=> {
        diyComponent.value.swiper.list.forEach((item : any) => {
            if (item.imageUrl == '') {
                item.imgWidth = 690;
                item.imgHeight = 294;
            }
        });
    }
	
	// 轮播指示器
	let isShowDots = ref(true)
	// #ifdef H5
	isShowDots.value = true;
	// #endif
	
	// #ifdef MP-WEIXIN
	isShowDots.value = false;
	// #endif

</script>

<style lang="scss" scoped>
	@import '@/addon/zzhc/styles/common.scss';
	.swiper-animation{
		transform: scale(0.96, 0.96);
		transition-duration: 0.3s;
		transition-timing-function: ease;
	}
	// 轮播指示器
	.swiper-right :deep(.uni-swiper-dots-horizontal) {
		right: 80rpx;
		display: flex;
		justify-content: flex-end;
		transform: translate(0);
	}
	.swiper-left :deep(.uni-swiper-dots-horizontal) {
		left: 80rpx;
		transform: translate(0);
	}
	.swiper :deep(.uni-swiper-dot) {
		width: 12rpx;
		height: 12rpx;
	}
	.swiper.ns-indicator-dots :deep(.uni-swiper-dot) {
		width: 18rpx;
		height: 6rpx;
		border-radius: 4rpx;
	}
	.swiper.ns-indicator-dots :deep(.uni-swiper-dot-active) {
		width: 36rpx;
	}
	.swiper-dot-box {
		position: absolute;
		bottom: 20rpx;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 0 80rpx 8rpx;
		box-sizing: border-box;
	
		&.swiper-left {
			justify-content: flex-start;
		}
	
		&.swiper-right {
			justify-content: flex-end;
		}
	
		.swiper-dot {
			background-color: #b2b2b2;
			width: 12rpx;
			border-radius: 50%;
			height: 12rpx;
			margin: 8rpx;
		}
	
		&.straightLine {
			.swiper-dot {
				width: 18rpx;
				height: 6rpx;
				border-radius: 4rpx;
	
				&.active {
					width: 36rpx;
				}
			}
		}
	}
</style>