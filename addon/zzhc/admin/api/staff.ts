import request from '@/utils/request'



// USER_CODE_BEGIN -- zzhc_staff
/**
 * 获取员工列表
 * @param params
 * @returns
 */
export function getStaffList(params: Record<string, any>) {
    return request.get(`zzhc/staff`, {params})
}

/**
 * 所有员工
 * @param {Record<string,any>} params 
 * @return 
 */ 
export function getStaffAll(params: Record<string,any>){
    return request.get('zzhc/staff_all', {params})
}

/**
 * 获取员工角色
 * @returns
 */
export function getStaffRole() {
    return request.get(`zzhc/staff/role`);
}

/**
 * 获取员工详情
 * @param staff_id 员工staff_id
 * @returns
 */
export function getStaffInfo(staff_id: number) {
    return request.get(`zzhc/staff/${staff_id}`);
}

/**
 * 添加员工
 * @param params
 * @returns
 */
export function addStaff(params: Record<string, any>) {
    return request.post('zzhc/staff', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑员工
 * @param staff_id
 * @param params
 * @returns
 */
export function editStaff(params: Record<string, any>) {
    return request.put(`zzhc/staff/${params.staff_id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除员工
 * @param staff_id
 * @returns
 */
export function deleteStaff(staff_id: number) {
    return request.delete(`zzhc/staff/${staff_id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_END -- zzhc_staff


// USER_CODE_BEGIN -- zzhc_staff_work
/**
 * 获取考勤管理列表
 * @param params
 * @returns
 */
export function getWorkList(params: Record<string, any>) {
    return request.get(`zzhc/work`, {params})
}

/**
 * 获取考勤管理详情
 * @param id 考勤管理id
 * @returns
 */
export function getWorkInfo(id: number) {
    return request.get(`zzhc/work/${id}`);
}

/**
 * 添加考勤管理
 * @param params
 * @returns
 */
export function addWork(params: Record<string, any>) {
    return request.post('zzhc/work', params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 编辑考勤管理
 * @param id
 * @param params
 * @returns
 */
export function editWork(params: Record<string, any>) {
    return request.put(`zzhc/work/${params.id}`, params, { showErrorMessage: true, showSuccessMessage: true })
}

/**
 * 删除考勤管理
 * @param id
 * @returns
 */
export function deleteWork(id: number) {
    return request.delete(`zzhc/work/${id}`, { showErrorMessage: true, showSuccessMessage: true })
}



// USER_CODE_END -- zzhc_staff_work
