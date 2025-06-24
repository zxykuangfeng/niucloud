import request from '@/utils/request'


/**
 * 数据概况
 */
export function getStatInfo() {
    return request.get(`zzhc/stat`)
}

