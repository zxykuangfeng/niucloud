import request from '@/utils/request'

// USER_CODE_BEGIN -- zzhc_coupon
/**
 * 获取优惠券列表
 * @param params
 * @returns
 */
export function getCouponList(params: Record<string, any>) {
    return request.get(`zzhc/coupon`, {params})
}

/**
 * 获取优惠券详情
 * @param coupon_id 优惠券coupon_id
 * @returns
 */
export function getCouponInfo(coupon_id: number) {
    return request.get(`zzhc/coupon/${coupon_id}`);
}

/**
 * 添加优惠券
 * @param params
 * @returns
 */
export function addCoupon(params: Record<string, any>) {
    return request.post('zzhc/coupon', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑优惠券
 * @param coupon_id
 * @param params
 * @returns
 */
export function editCoupon(params: Record<string, any>) {
    return request.put(`zzhc/coupon/${params.coupon_id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除优惠券
 * @param coupon_id
 * @returns
 */
export function deleteCoupon(coupon_id: number) {
    return request.delete(`zzhc/coupon/${coupon_id}`, { showErrorMessage: true, showSuccessMessage: true })
}
// USER_CODE_END -- zzhc_coupon

// USER_CODE_BEGIN -- zzhc_coupon_member
/**
 * 获取领券记录列表
 * @param params
 * @returns
 */
export function getMemberList(params: Record<string, any>) {
    return request.get(`zzhc/coupon_member`, {params})
}

/**
 * 获取领券记录详情
 * @param id 领券记录id
 * @returns
 */
export function getMemberInfo(id: number) {
    return request.get(`zzhc/coupon_member/${id}`);
}

/**
 * 添加领券记录
 * @param params
 * @returns
 */
export function addMember(params: Record<string, any>) {
    return request.post('zzhc/coupon_member', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑领券记录
 * @param id
 * @param params
 * @returns
 */
export function editMember(params: Record<string, any>) {
    return request.put(`zzhc/coupon_member/${params.id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除领券记录
 * @param id
 * @returns
 */
export function deleteMember(id: number) {
    return request.delete(`zzhc/coupon_member/${id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_END -- zzhc_coupon_member
