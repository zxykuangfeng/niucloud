import request from '@/utils/request'

/**
 * 优惠券列表
 */
export function getCouponList(params : Record<string, any>) {
	return request.get(`zzhc/coupon`, params)
}

/**
 * 领取优惠券
 */
export function receiveCoupon(params : Record<string, any>) {
	return request.post(`zzhc/coupon`, params, {showSuccessMessage: true})
}

/**
 * 获取我的优惠券
 */
export function getMyCouponList(params : Record<string, any>) {
	return request.get(`zzhc/member/coupon`, params)
}
