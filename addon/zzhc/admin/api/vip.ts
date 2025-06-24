import request from '@/utils/request'


/**
 * 获取VIP套餐列表
 * @param params
 * @returns
 */
export function getVipList(params: Record<string, any>) {
    return request.get(`zzhc/vip`, {params})
}

/**
 * 获取VIP配置
 * @returns
 */
export function getVipConfig() {
    return request.get('zzhc/vip/config')
}

/**
 * 编辑VIP配置
 * @returns
 */
export function setVipConfig(param: any) {
    return request.post('zzhc/vip/config', param, {showSuccessMessage: true})
}


/**
 * 获取VIP套餐详情
 * @param vip_id VIP套餐vip_id
 * @returns
 */
export function getVipInfo(vip_id: number) {
    return request.get(`zzhc/vip/${vip_id}`);
}

/**
 * 添加VIP套餐
 * @param params
 * @returns
 */
export function addVip(params: Record<string, any>) {
    return request.post('zzhc/vip', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑VIP套餐
 * @param vip_id
 * @param params
 * @returns
 */
export function editVip(params: Record<string, any>) {
    return request.put(`zzhc/vip/${params.vip_id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除VIP套餐
 * @param vip_id
 * @returns
 */
export function deleteVip(vip_id: number) {
    return request.delete(`zzhc/vip/${vip_id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_BEGIN -- zzhc_vip_member
/**
 * 获取办卡会员列表
 * @param params
 * @returns
 */
export function getMemberList(params: Record<string, any>) {
    return request.get(`zzhc/vip_member`, {params})
}

/**
 * 获取办卡会员详情
 * @param id 办卡会员id
 * @returns
 */
export function getMemberInfo(id: number) {
    return request.get(`zzhc/vip_member/${id}`);
}

/**
 * 添加办卡会员
 * @param params
 * @returns
 */
export function addMember(params: Record<string, any>) {
    return request.post('zzhc/vip_member', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑办卡会员
 * @param id
 * @param params
 * @returns
 */
export function editMember(params: Record<string, any>) {
    return request.put(`zzhc/vip_member/${params.id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除办卡会员
 * @param id
 * @returns
 */
export function deleteMember(id: number) {
    return request.delete(`zzhc/vip_member/${id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_END -- zzhc_vip_member


// USER_CODE_BEGIN -- zzhc_vip_order
/**
 * 获取办卡订单列表
 * @param params
 * @returns
 */
export function getOrderList(params: Record<string, any>) {
    return request.get(`zzhc/vip/order`, {params})
}

/**
 * 获取办卡订单详情
 * @param order_id 办卡订单order_id
 * @returns
 */
export function getOrderInfo(order_id: number) {
    return request.get(`zzhc/vip/order/${order_id}`);
}

/**
 * 添加办卡订单
 * @param params
 * @returns
 */
export function addOrder(params: Record<string, any>) {
    return request.post('zzhc/vip/order', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑办卡订单
 * @param order_id
 * @param params
 * @returns
 */
export function editOrder(params: Record<string, any>) {
    return request.put(`zzhc/vip/order/${params.order_id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除办卡订单
 * @param order_id
 * @returns
 */
export function deleteOrder(order_id: number) {
    return request.delete(`zzhc/vip/order/${order_id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_END -- zzhc_vip_order


// USER_CODE_BEGIN -- zzhc_vip_log
/**
 * 获取VIP会员操作日志列表
 * @param params
 * @returns
 */
export function getLogList(params: Record<string, any>) {
    return request.get(`zzhc/vip_log`, {params})
}

/**
 * 获取VIP会员操作日志详情
 * @param id VIP会员操作日志id
 * @returns
 */
export function getLogInfo(id: number) {
    return request.get(`zzhc/vip_log/${id}`);
}

/**
 * 添加VIP会员操作日志
 * @param params
 * @returns
 */
export function addLog(params: Record<string, any>) {
    return request.post('zzhc/vip_log', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑VIP会员操作日志
 * @param id
 * @param params
 * @returns
 */
export function editLog(params: Record<string, any>) {
    return request.put(`zzhc/vip_log/${params.id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除VIP会员操作日志
 * @param id
 * @returns
 */
export function deleteLog(id: number) {
    return request.delete(`zzhc/vip_log/${id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_END -- zzhc_vip_log
