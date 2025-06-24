import request from '@/utils/request'

// USER_CODE_BEGIN -- zzhc_goods_category
/**
 * 获取项目分类列表
 * @param params
 * @returns
 */
export function getCategoryList(params: Record<string, any>) {
    return request.get(`zzhc/category`, {params})
}

/**
 * 获取项目分类列表（所有）
 * @param params
 * @returns
 */
export function getCategoryAll() {
    return request.get(`zzhc/category_all`)
}

/**
 * 获取项目分类详情
 * @param id 项目分类id
 * @returns
 */
export function getCategoryInfo(id: number) {
    return request.get(`zzhc/category/${id}`);
}

/**
 * 添加项目分类
 * @param params
 * @returns
 */
export function addCategory(params: Record<string, any>) {
    return request.post('zzhc/category', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑项目分类
 * @param id
 * @param params
 * @returns
 */
export function editCategory(params: Record<string, any>) {
    return request.put(`zzhc/category/${params.category_id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除项目分类
 * @param id
 * @returns
 */
export function deleteCategory(id: number) {
    return request.delete(`zzhc/category/${id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_END -- zzhc_goods_category


// USER_CODE_BEGIN -- zzhc_goods
/**
 * 获取项目列表
 * @param params
 * @returns
 */
export function getGoodsList(params: Record<string, any>) {
    return request.get(`zzhc/goods`, {params})
}

/**
 * 获取项目详情
 * @param goods_id 项目goods_id
 * @returns
 */
export function getGoodsInfo(goods_id: number) {
    return request.get(`zzhc/goods/${goods_id}`);
}

/**
 * 添加项目
 * @param params
 * @returns
 */
export function addGoods(params: Record<string, any>) {
    return request.post('zzhc/goods', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑项目
 * @param goods_id
 * @param params
 * @returns
 */
export function editGoods(params: Record<string, any>) {
    return request.put(`zzhc/goods/${params.goods_id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除项目
 * @param goods_id
 * @returns
 */
export function deleteGoods(goods_id: number) {
    return request.delete(`zzhc/goods/${goods_id}`, { showErrorMessage: true, showSuccessMessage: true })
}

// USER_CODE_END -- zzhc_goods
