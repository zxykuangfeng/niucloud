<?php

namespace addon\zzhc\app\listener\order;

use addon\zzhc\app\dict\order\OrderDict;
use addon\zzhc\app\service\core\order\CoreOrderLogService;

/**
 * 订单取消后操作
 */
class AfterOrderCancel
{

    public function handle($data){
        $orderData = $data['order_data'];
        
        //订单操作日志
        $main_type = $data['main_type'];
        $main_id = $data['main_id'] ?? 0;
        $content = $data['content'] ?? '';
        (new CoreOrderLogService())->add([
            'order_id' => $orderData['order_id'],
            'site_id' => $data['site_id'],
            'status' => OrderDict::CANCEL,
            'main_type' => $main_type,
            'main_id' => $main_id,
            'type' => OrderDict::ORDER_CANCEL_ACTION,
            'content' => $content
        ]);
    }
}
