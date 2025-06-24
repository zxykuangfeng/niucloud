import request from '@/utils/request'

/**
 * 获取订单状态列表
 */
export function getOrderStatus() {
    return request.get(`zzhc/order/status`)
}

/**
 * 获取订单列表
 */
export function getOrderList(params: Record<string, any>) {
    return request.get(`zzhc/order`, params)
}

/**
 * 订单创建
 */
export function orderCreate(params: Record<string, any>) {
    return request.post('zzhc/order/create', params)
}

/**
 * 获取订单详情
 */
export function getOrderDetail(order_id: number) {
    return request.get(`zzhc/order/${order_id}`)
}

/**
 * 取消订单
 */
export function orderCancel(order_id: number) {
    return request.put(`zzhc/order/cancel/${order_id}`)
}

/**
 * 添加或更新订单支付表
 */
export function orderPay(order_id: number) {
    return request.put(`zzhc/order/pay/${order_id}`)
}


/**
 * 订单使用优惠券
 */
export function orderUseCoupon(params: Record<string, any>) {
    return request.put(`zzhc/order/coupon`,params)
}

/**
 * 查询订单可用优惠券
 */
export function orderCoupon(params: Record<string, any>) {
    return request.get('zzhc/order/coupon', params)
}