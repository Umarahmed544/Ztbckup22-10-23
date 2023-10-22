<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/vendor/autoload.php';
require_once("inc/functions.php");
$shop_url="https://timpanys-dress-agency.myshopify.com/";
$access_token="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";


$req = file_get_contents('php://input');
$rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);

$vendor_name = json_decode($req)->vendor;

$config = array(
  'ShopUrl' => $shop_url,
  'AccessToken' => $access_token,
  'ApiVersion' => '2022-10', 
);
PHPShopify\ShopifySDK::config($config);
$shopify = new PHPShopify\ShopifySDK;

$param = array('title' => $vendor_name);
$count = $shopify->SmartCollection->count($param);

// && $count !=''
if($count == 0 ){
    $params = array(
        'title' => $vendor_name,
        "rules" => array(array(
            "column" => "vendor",
            "relation" => "equals",
            "condition" => $vendor_name,
        ))
    );
    $product = $shopify->SmartCollection->post($params);
}
   