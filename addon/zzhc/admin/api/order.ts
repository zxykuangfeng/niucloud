import request from '@/utils/request'

// USER_CODE_BEGIN -- zzhc_order
/**
 * 获取预约订单列表
 * @param params
 * @returns
 */
export function getOrderList(params: Record<string, any>) {
    return request.get(`zzhc/order`, {params})
}

/**
 * 获取预约订单详情
 * @param order_id 预约订单order_id
 * @returns
 */
export function getOrderInfo(order_id: number) {
    return request.get(`zzhc/order/${order_id}`);
}

/**
 * 添加预约订单
 * @param params
 * @returns
 */
export function addOrder(params: Record<string, any>) {
    return request.post('zzhc/order', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑预约订单
 * @param order_id
 * @param params
 * @returns
 */
export function editOrder(params: Record<string, any>) {
    return request.put(`zzhc/order/${params.order_id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除预约订单
 * @param order_id
 * @returns
 */
export function deleteOrder(order_id: number) {
    return request.delete(`zzhc/order/${order_id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_END -- zzhc_order


// USER_CODE_BEGIN -- zzhc_order_log
/**
 * 获取订单操作日志列表
 * @param params
 * @returns
 */
export function getOrderLogList(params: Record<string, any>) {
    return request.get(`zzhc/order_log`, {params})
}

/**
 * 获取订单操作日志详情
 * @param id 订单操作日志id
 * @returns
 */
export function getOrderLogInfo(id: number) {
    return request.get(`zzhc/order_log/${id}`);
}

/**
 * 添加订单操作日志
 * @param params
 * @returns
 */
export function addOrderLog(params: Record<string, any>) {
    return request.post('zzhc/order_log', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑订单操作日志
 * @param id
 * @param params
 * @returns
 */
export function editOrderLog(params: Record<string, any>) {
    return request.put(`zzhc/order_log/${params.id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除订单操作日志
 * @param id
 * @returns
 */
export function deleteOrderLog(id: number) {
    return request.delete(`zzhc/order_log/${id}`, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 获取订单状态
 * @returns
 */
export function getOrderStatus() {
    return request.get(`zzhc/order/status`);
}


/**
 * 取消订单
 */
export function orderCancel(order_id: number) {
    return request.put(`zzhc/order/cancel/${order_id}`,{ showErrorMessage: true, showSuccessMessage: true })
}

// USER_CODE_END -- zzhc_order_log
