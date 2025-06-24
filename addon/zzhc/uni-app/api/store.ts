import request from '@/utils/request'


/**
 * 获取门店列表
 */
export function getStoreList(params: Record<string, any>) {
    return request.get('zzhc/store', params)
}

/**
 * 门店详情
 */
export function getStoreDetail(store_id: number) {
    return request.get(`zzhc/store/${store_id}`)
}

/**
 * 获取门店发型师组件数据
 */
export function getStoreStaffComponents(params: Record<string, any>) {
    return request.get(`zzhc/store/components`, params)
}