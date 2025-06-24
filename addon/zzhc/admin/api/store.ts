import request from '@/utils/request'

// USER_CODE_BEGIN -- zzhc_store
/**
 * 获取门店列表
 * @param params
 * @returns
 */
export function getStoreList(params: Record<string, any>) {
    return request.get(`zzhc/store`, {params})
}

/**
 * 获取门店详情
 * @param store_id 门店store_id
 * @returns
 */
export function getStoreInfo(store_id: number) {
    return request.get(`zzhc/store/${store_id}`);
}

/**
 * 添加门店
 * @param params
 * @returns
 */
export function addStore(params: Record<string, any>) {
    return request.post('zzhc/store', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑门店
 * @param store_id
 * @param params
 * @returns
 */
export function editStore(params: Record<string, any>) {
    return request.put(`zzhc/store/${params.store_id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除门店
 * @param store_id
 * @returns
 */
export function deleteStore(store_id: number) {
    return request.delete(`zzhc/store/${store_id}`, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 所有门店
 * @param {Record<string,any>} params 
 * @return 
 */ 
export function getStoreAll(params: Record<string,any>){
    return request.get('zzhc/store_all', {params})
}


// USER_CODE_END -- zzhc_store
