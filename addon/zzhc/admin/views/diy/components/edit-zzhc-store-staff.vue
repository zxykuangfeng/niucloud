<template>
	<!-- 内容 -->
	<div class="content-wrap" v-show="diyStore.editTab == 'content'">
		
		<div class="edit-attr-item-wrap">
			<h3 class="mb-[10px]">门店设置</h3>
			<el-form label-width="100px" class="px-[10px]">
				<el-form-item label="展示门店">
					<el-radio-group v-model="diyStore.editComponent.store.source">
						<el-radio label="distance">距离最近</el-radio>
						<!--el-radio label="custom">手动选择</el-radio-->
					</el-radio-group>
				</el-form-item>
			</el-form>
		</div>
		
		<div class="edit-attr-item-wrap">
			<h3 class="mb-[10px]">发型师设置</h3>
			<el-form label-width="80px" class="px-[10px]">
				<el-form-item label="显示数量">
					<div class="flex items-center w-full ml-[5px]">
						<el-slider class="flex-1" v-model="diyStore.editComponent.staff.num" max="10" size="small" />
						<span class="ml-[15px]">{{ diyStore.editComponent.staff.num }}</span>
					</div>
				</el-form-item>
			</el-form>
		</div>
	</div>

	<!-- 样式 -->
	<div class="style-wrap" v-show="diyStore.editTab == 'style'">
		<!-- 组件样式 -->
		<slot name="style"></slot>
	</div>
</template>

<script lang="ts" setup>
import { t } from '@/lang'
import { img } from '@/utils/common'
import useDiyStore from '@/stores/modules/diy'
import { ref, reactive, onMounted } from 'vue'
import { ElTable } from 'element-plus'

const diyStore:any = useDiyStore()
diyStore.editComponent.ignore = [] // 忽略公共属性
onMounted(() => {
})

defineExpose({})

</script>

<style lang="scss" scoped></style>
