import request from '@/utils/request'


/**
 * 店长端首页
 */
export function getManageInfo(store_id: number) {
    return request.get(`zzhc/merchant/manage/info/${store_id}`,{},{showSuccessMessage:false,showErrorMessage: false})
}

/**
 * 门店订单列表
 */
export function getManageOrderList(params: Record<string, any>) {
    return request.get(`zzhc/merchant/manage/order`, params)
}

/**
 * 店长取消订单
 */
export function manageOrderCancel(params: Record<string, any>) {
    return request.put(`zzhc/merchant/manage/order_cancel`,params)
}

/**
 * 店长获取订单详情
 */
export function getManageOrderDetail(params: Record<string, any>) {
    return request.get(`zzhc/merchant/manage/order_detail`,params)
}
/**
 * 店长获取考勤列表
 */
export function getManageWorkList(params: Record<string, any>) {
    return request.get(`zzhc/merchant/manage/work`, params)
}

/**
 * 发型师端首页
 */
export function getBarberInfo(store_id: number) {
    return request.get(`zzhc/merchant/barber/info/${store_id}`,{},{showSuccessMessage:false,showErrorMessage: false})
}

/**
 * 发型师订单列表
 */
export function getBarberOrderList(params: Record<string, any>) {
    return request.get(`zzhc/merchant/barber/order`, params)
}

/**
 * 发型师获取订单详情
 */
export function getBarberOrderDetail(params: Record<string, any>) {
    return request.get(`zzhc/merchant/barber/order_detail`,params)
}

/**
 * 发型师取消订单
 */
export function barberOrderCancel(params: Record<string, any>) {
    return request.put(`zzhc/merchant/barber/order_cancel`,params)
}

/**
 * 发型师订单开始服务
 */
export function barberOrderService(params: Record<string, any>) {
    return request.put(`zzhc/merchant/barber/order_service`,params)
}

/**
 * 发型师订单退回排队
 */
export function barberOrderRevert(params: Record<string, any>) {
    return request.put(`zzhc/merchant/barber/order_revert`,params)
}

/**
 * 发型师订单完成服务
 */
export function barberOrderFinish(params: Record<string, any>) {
    return request.put(`zzhc/merchant/barber/order_finish`,params)
}

/**
 * 发型师添加考勤打卡
 */
export function barberWorkAdd(params: Record<string, any>) {
    return request.post(`zzhc/merchant/barber/work`,params)
}

/**
 * 获取发型师月打卡数据
 */
export function getBarberWorkInfo(params: Record<string, any>) {
	return request.get(`zzhc/merchant/barber/work`, params)
}

/**
 * 获取发型师日打卡数据
 */
export function getBarberWorkDateInfo(params: Record<string, any>) {
	return request.get(`zzhc/merchant/barber/work_date`, params)
}