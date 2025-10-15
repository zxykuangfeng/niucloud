<?php
namespace app\service\api\toutiao;

use app\service\core\toutiao\CoreToutiaoOpenService;
use core\base\BaseApiService;

class ToutiaoOpenService extends BaseApiService
{
    protected CoreToutiaoOpenService $core_service;

    public function __construct()
    {
        parent::__construct();
        $this->core_service = new CoreToutiaoOpenService();
    }

    /**
     * 获取授权链接
     * @param array $params
     * @return array
     */
    public function getAuthLink(array $params = []): array
    {
        $token = $this->core_service->getComponentAccessToken();
        $accessToken = $token['component_access_token'] ?? '';
        if ($accessToken === '') {
            return [];
        }
        return $this->core_service->genAuthLink($params, $accessToken);
    }



      /**
     * 获取小程序二维码
     * @param string $authorizerAccessToken 授权小程序接口调用凭据
     * @param array $params 请求参数
     * @return string 二维码图片路径
     */
    public function getQrcode(string $authorizerAccessToken, array $params = []): string
    {
        
        $token = $this->core_service->getComponentAccessSanFangToken();
        
         $accessToken = $token['component_access_token'] ?? '';
        if ($accessToken === '') {
            return [];
        }
        return $this->core_service->getQrcode($params, $accessToken);
    }
    
    
    
    /**
     * 获取已授权抖音小程序二维码
     * @param string $version
     * @param string $path
     * @return string
     */
    public function getToutiaoQrcode(string $version = 'latest', string $path = ''): string
    {
        return $this->core_service->getToutiaoQrcode($version, $path);
    }
    
    
    
    /**
     * 获取已授权抖音小程序的 access_token
     * @return string
     */
    public function getToutiaoAccessToken(): string
    {
        return $this->core_service->getToutiaoAccessToken();
    }
    
    
     /**
     * 查询抖音标签组信息
     * @return array
     */
    public function getToutiaoTagGroup(): array
    {
        $token = $this->getToutiaoAccessToken();
        if ($token === '') {
            return [];
        }
        return $this->core_service->getToutiaoTagGroup($token);
    }
      /**
     * 提审已授权抖音小程序
     * @param array $hostNames
     * @param string $auditNote
     * @param int $auditWay
     * @return array
     */
    public function auditToutiaoPackage(array $hostNames, string $auditNote = '', int $auditWay = 0): array
    {
        return $this->core_service->auditToutiaoPackage($hostNames, $auditNote, $auditWay);
    }
  /**
     * 创建一个抖音测试商品
     * @param string $accessToken 指定的 access_token
     * @param array $payload 自定义商品结构
     * @return array
     */
    public function createTestProduct(string $accessToken = '', array $payload = []): array
    {
        $tokenResponse = [];
        if ($accessToken === '') {
            $tokenResponse = $this->core_service->getOauthAccessToken();
            $accessToken = $tokenResponse['data']['access_token'] ?? '';
            if ($accessToken === '') {
                return $tokenResponse;
            }
        }

        $body = $this->buildTestProductPayload($payload);
        
      
        $productResponse = $this->core_service->saveToutiaoProduct($accessToken, $body);
  dd($productResponse);
        return [
            'access_token' => $accessToken,
            'request_payload' => $body,
            'token_response' => $tokenResponse,
            'product_response' => $productResponse,
        ];
    }

    /**
     * 构建默认的测试商品结构
     * @param array $payload
     * @return array
     */
    protected function buildTestProductPayload(array $payload = []): array
    {
        $timestamp = time();
    $default = [
    "ability" => ["ignore_inapplicable_poi" => false],
    "account_id" => "7210702973314271272",
    "base" => [
        "log_id" => uniqid('', true),
        "caller" => "niucloud",
    ],

    "product" => [
        "product_name" => "test",
        "out_id" => "test-out-" . uniqid(),
        "account_name" => "test",
        "owner_account_id" => "7852080425400674587",
        "biz_line" => "5",
        "open_biz_type" => "1",
        "product_type" => "22",
        "product_sub_type" => "1",
        "desc" => "NiuCloud",
        "telephone" => ["010-12345678"],
        "category_id" => "6108686888958459895",
        "category_full_name" => "category",
        "product_ext" => ["auto_online" => false,"display_price"=>["low_price"=>1000]],
         "low_price"=> 100, 
    ],

    // ✅ 单SKU结构体
    "sku" => [
        "sku_id" => "sku-" . uniqid(),
        "sku_name" => "SKU",
        "status" => 1,
        "origin_amount" => 10000,
        "actual_amount" => 10000,
         "low_price"=> 10000, 
        "out_sku_id" => "out-sku-" . uniqid(),
        "extra" => "extra",
        "bind_skus" => [],
        "attr_key_value_map" => [
            "color" => "red",
            "size" => "L",
            "is_custom" => "false",
            // 注意图片字段是结构体需转JSON字符串
            "main_image" => json_encode([
                ["url" => "https://example.com/test.jpg"]
            ]),
        ],
        "stock" => [
            "stock_qty" => 100,
            "avail_qty" => 100,
            "sold_qty" => 0,
            "sold_count" => 0,
            "frozen_qty" => 0,
            "limit_type" => 1,
        ],
        "sku_ext" => [
            "use_sub_rel_stock" => false,
            "account_settle" => false,
            "discount_promo" => [
                "PlanId" => 0,
                "BrandActivityId" => 0,
                "PromoId" => 0
            ],
            "takeaway_presale_info" => [
                "takeaway_presale_product_id" => 0,
                "takeaway_presale_sku_id" => 0
            ],
            
        ]
    ],

];


        // if (empty($payload)) {
        //     return $default;
        // }
        // dd($payload);
        return array_replace_recursive($default, $payload);
    }

}