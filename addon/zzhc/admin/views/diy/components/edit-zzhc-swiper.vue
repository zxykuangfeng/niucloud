<template>
	
	<!-- 内容 -->
	<div class="content-wrap" v-show="diyStore.editTab == 'content'">

		<el-form label-width="100px" class="px-[10px]">
			
			<el-form-item label="切换间隔 / 秒">
				<el-slider v-model="diyStore.editComponent.swiper.interval" show-input size="small" class="ml-[10px] horz-blank-slider" :min="1" :max="10"/>
			</el-form-item>
		
			<div class="text-sm text-gray-400 mb-[10px]">建议上传尺寸相同的图片，推荐尺寸750*320；鼠标拖拽可调整图片顺序</div>
		
			<div ref="imageBoxRef">
				<div v-for="(item,index) in diyStore.editComponent.swiper.list" :key="item.id" class="item-wrap p-[10px] pb-0 relative border border-dashed border-gray-300 mb-[16px]">
					<el-form-item :label="t('image')">
						<upload-image v-model="item.imageUrl" :limit="1" @change="selectImg" />
					</el-form-item>
		
					<div class="del absolute cursor-pointer z-[2] top-[-8px] right-[-8px]" v-show="diyStore.editComponent.swiper.list.length > 1" @click="diyStore.editComponent.swiper.list.splice(index,1)">
						<icon name="element CircleCloseFilled" color="#bbb" size="20px"/>
					</div>
		
					<el-form-item :label="t('link')">
						<diy-link v-model="item.link"/>
					</el-form-item>
				</div>
			</div>
		
			<el-button v-show="diyStore.editComponent.swiper.list.length < 10" class="w-full" @click="addImageAd">{{ t('addImageAd') }}</el-button>
		
		</el-form>

	</div>

	<!-- 样式 -->
	<div class="style-wrap" v-show="diyStore.editTab == 'style'">

		<div class="edit-attr-item-wrap">
			<h3 class="mb-[10px]">轮播图设置</h3>
			<el-form label-width="100px" class="px-[10px]">
                <el-form-item label="轮播样式" @change="changeSwiperStyle">
					<el-radio-group v-model="diyStore.editComponent.swiper.swiperStyle">
						<el-radio label="style-1">样式1</el-radio>
						<el-radio label="style-2">样式2</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="上圆角">
					<el-slider v-model="diyStore.editComponent.swiper.topRounded" show-input size="small" class="ml-[10px] horz-blank-slider" :max="50" />
				</el-form-item>
				<el-form-item label="下圆角">
					<el-slider v-model="diyStore.editComponent.swiper.bottomRounded" show-input size="small" class="ml-[10px] horz-blank-slider" :max="50" />
				</el-form-item>
			</el-form>
		</div>
		
		<!-- 组件样式 -->
		<slot name="style"></slot>

		<div class="edit-attr-item-wrap">
			<h3 class="mb-[10px]">指示器设置</h3>
			<el-form label-width="100px" class="px-[10px]">
				<el-form-item label="指示器样式">
					<el-radio-group v-model="diyStore.editComponent.swiper.indicatorStyle">
						<el-radio label="style-1">样式1</el-radio>
						<el-radio label="style-2">样式2</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="显示位置">
					<el-radio-group v-model="diyStore.editComponent.swiper.indicatorAlign">
						<el-radio label="left">居左</el-radio>
						<el-radio label="center">居中</el-radio>
						<el-radio label="right">居右</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="常规颜色">
					<el-color-picker v-model="diyStore.editComponent.swiper.indicatorColor" show-alpha :predefine="diyStore.predefineColors"/>
				</el-form-item>
				<el-form-item label="选中颜色">
					<el-color-picker v-model="diyStore.editComponent.swiper.indicatorActiveColor" show-alpha :predefine="diyStore.predefineColors"/>
				</el-form-item>
			</el-form>
		</div>
	</div>

</template>

<script lang="ts" setup>
import { t } from '@/lang'
import { img } from '@/utils/common'
import useDiyStore from '@/stores/modules/diy'
import { ref, reactive, watch, onMounted, nextTick } from 'vue'
import { ElTable } from 'element-plus'
import Sortable from 'sortablejs'
import { range,cloneDeep } from 'lodash-es'

import { getDiyPageListByCarouselSearch } from '@/app/api/diy'

const diyStore = useDiyStore()
diyStore.editComponent.ignore = ['componentBgColor','componentBgUrl','topRounded','bottomRounded','pageBgColor'] // 忽略公共属性

// 组件验证
diyStore.editComponent.verify = (index: number) => {
    const res = { code: true, message: '' }
    
    diyStore.value[index].swiper.list.forEach((item: any) => {
        if(item.imageUrl == ''){
            res.code = false
            res.message = t('imageUrlTip')
            return res
        }
    });

    return res
}

diyStore.editComponent.swiper.list.forEach((item: any) => {
    if (!item.id) item.id = diyStore.generateRandom()
})


onMounted(() => {
    loadDiyPageList()
})


const imageBoxRef = ref()

onMounted(() => {
    nextTick(() => {

        const imageSortable = Sortable.create(imageBoxRef.value, {
            group: 'item-wrap',
            animation: 200,
            onEnd: event => {
                const temp = diyStore.editComponent.swiper.list[event.oldIndex!]
                diyStore.editComponent.swiper.list.splice(event.oldIndex!, 1)
                diyStore.editComponent.swiper.list.splice(event.newIndex!, 0, temp)
                imageSortable.sort(
                    range(diyStore.editComponent.swiper.list.length).map(value => {
                        return value.toString()
                    })
                )
                handleHeight(true)
            }
        })
    })
})

const diyPageShowDialog = ref(false)

const diyPageTable = reactive({
    page: 1,
    limit: 10,
    total: 0,
    loading: true,
    data: [],
    searchParam: {
    }
})
const diyPageTableRef = ref<InstanceType<typeof ElTable>>()

/**
 * 获取自定义页面列表
 */
const loadDiyPageList = (page: number = 1) => {
    diyPageTable.loading = true
    diyPageTable.page = page

    getDiyPageListByCarouselSearch({
        page: diyPageTable.page,
        limit: diyPageTable.limit,
        ...diyPageTable.searchParam
    }).then(res => {
        diyPageTable.loading = false
        let data = res.data.data;
        let newData: any = [];
        let isExistCount = 0;

        // 排除当前编辑的微页面以及存在 置顶组件的数据
        if (diyStore.id) {
            for (let i = 0; i < data.length; i++) {
                if (data[i].id == diyStore.id) {
                    isExistCount++;
                } else {
                    newData.push(data[i]);
                }
            }
        } else {
            newData = cloneDeep(data); // 添加
        }
        if (isExistCount) {
            res.data.total = res.data.total - isExistCount;
        }
        diyPageTable.data = newData
        diyPageTable.total = res.data.total
    }).catch(() => {
        diyPageTable.loading = false
    })
}

// 选择微页面
let currDiyPage:any = {}
let currTabIndexForDiyPage = 0;
const handleCurrentDiyPageChange = (val: string | any[]) => {
    currDiyPage = val
}

const saveDiyPageId = () => {
    diyStore.editComponent.tab.list[currTabIndexForDiyPage].diy_id = currDiyPage.id;
    diyStore.editComponent.tab.list[currTabIndexForDiyPage].diy_title = currDiyPage.title;
    diyPageShowDialog.value = false
}

const diyPageShowDialogOpen = (index:any) => {
    diyPageShowDialog.value = true
    currTabIndexForDiyPage = index;
    if (currDiyPage) {
        setTimeout(() => {
            diyPageTableRef.value!.setCurrentRow(currDiyPage)
        }, 200)
    }
}

watch(
    () => diyStore.editComponent.swiper.list,
    (newValue, oldValue) => {
        // 设置图片宽高
        handleHeight()
    },
    { deep: true }
)

const addImageAd = () => {
    diyStore.editComponent.swiper.list.push({
        id: diyStore.generateRandom(),
        imageUrl: '',
        imgWidth: 0,
        imgHeight: 0,
        link: { name: '' }
    })
}

const selectImg = (url:string) => {
    handleHeight(true)
}

const changeSwiperStyle = (value:any) => {
    handleHeight(true)
}

// 处理高度
const handleHeight = (isCalcHeight:boolean = false)=> {
    diyStore.editComponent.swiper.list.forEach((item: any, index: number) => {
        const image = new Image()
        image.src = img(item.imageUrl)
        image.onload = async () => {
            item.imgWidth = image.width
            item.imgHeight = image.height
            // 计算第一张图片高度
            if (isCalcHeight && index == 0) {
                const ratio = item.imgHeight / item.imgWidth
                if(diyStore.editComponent.swiper.swiperStyle == 'style-1') {
                    item.width = 375 * 0.92 // 0.92：前端缩放比例
                }else{
                    item.width = 355
                }
                item.height = item.width * ratio
                diyStore.editComponent.swiper.imageHeight = parseInt(item.height)
            }
        }
    })
}

defineExpose({})
</script>

<style lang="scss" scoped></style>

<style lang="scss">
	.select-diy-page-input .el-input__inner{
		cursor: pointer;
	}
	.collapse-wrap{
		.el-collapse-item__header{
			font-size: 16px;
		}
	}
</style>
