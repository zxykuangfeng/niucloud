import request from '@/utils/request'

/**
 * 获取VIP配置
 * @returns
 */
export function getVipConfig() {
    return request.get('zzhc/vip/config')
}

/**
 * 获取VIP套餐列表
 */
export function getVipList() {
    return request.get(`zzhc/vip`)
}

/**
 * 创建VIP订单
 */
export function orderCreate(params: Record<string, any>) {
    return request.post('zzhc/vip/order/create', params)
}

/**
 * 获取我的会员卡
 */
export function getMyVip(params : Record<string, any>) {
	return request.get(`zzhc/member/vip`, params)
}