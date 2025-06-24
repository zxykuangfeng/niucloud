import request from '@/utils/request'

/**
 * 项目分类列表
 */
export function getCategoryList() {
	return request.get(`zzhc/goods/category`)
}

/**
 * 项目列表
 */
export function getGoodsList(params : Record<string, any>) {
	return request.get(`zzhc/goods`, params)
}
