import request from '@/utils/request'

/**
 * 发型师列表
 */
export function getBarberList(params : Record<string, any>) {
	return request.get(`zzhc/barber`, params)
}

/**
 * 发型师详情
 */
export function getBarberDetail(staff_id: number) {
    return request.get(`zzhc/barber/${staff_id}`)
}