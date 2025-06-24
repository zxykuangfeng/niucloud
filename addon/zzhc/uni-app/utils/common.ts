import { redirect } from '@/utils/common';

//获取当前门店ID
export function currStoreId(isLink: boolean = true) {
	
    let data = uni.getStorageSync('zzhc:store');
    if (data) {
        return data.store_id;
    }
	if(isLink){
		redirect({ url: '/addon/zzhc/pages/store/list', mode: 'navigateTo' });
	}else{
		return 0;
	}
}

//距离格式化
export function distanceFormat(distance: number | string) {
	distance = parseFloat(distance);
	if (distance < 1000) {
		const m = distance.toFixed(1);
		return `${m}m`;
	} else {
		const km = (distance / 1000).toFixed(1)
		return `${km}km`;
	}
}

//获取经纬度
export function getLoc(){
	return new Promise((resolve, reject) => {
		//定位
		if(!uni.getStorageSync('zzhc:location')) {
			
			uni.getLocation({
			    type: 'gcj02',
			    success: res => {
					uni.setStorageSync("zzhc:location",res);
			        resolve(res);
			    },
			    fail: res => {
			        reject(res)
			    }
			});
			
		}else{
			resolve(uni.getStorageSync('zzhc:location'));
		}
	})
}



//拨号
export function callPhone(phoneNumber: string){
	uni.makePhoneCall({
		phoneNumber: phoneNumber
	});
}
