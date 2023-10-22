<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/vendor/autoload.php';
require_once("inc/functions.php");
$shop_url="https://timpanys-dress-agency.myshopify.com/";
// $shop_url=$_REQUEST['shop'];
$access_token="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";
// $access_token=$_GET['access_token'];

$config = array(
  'ShopUrl' => $shop_url,
  'AccessToken' => $access_token,
  'ApiVersion' => '2022-10', 
);
PHPShopify\ShopifySDK::config($config);
$shopify = new PHPShopify\ShopifySDK;

$vendor_names = $_REQUEST['vendors'];
$vendor_arr = explode(',', $vendor_names);


if ($vendor_names!=''){
    foreach($vendor_arr as $key => $value){
        $params = array(
            'title' => $value,
            "rules" => array(array(
                "column" => "vendor",
                "relation" => "equals",
                "condition" => $value,
            ))
        );
        $products = $shopify->SmartCollection->post($params);       
    }
    echo json_encode(count($vendor_arr)." new ,vendor collection created - ".$vendor_names);
}else{
    echo json_encode("All vendors have collection ");
}