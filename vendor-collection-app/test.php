<?php

require __DIR__ . '/vendor/autoload.php';
require_once("inc/functions.php");
$shop_url="https://vendor-colection-store.myshopify.com";
// $shop_url=$_REQUEST['shop'];
$access_token="shpua_3b626e93a9591f79d13ebe61fcc87218";
// $access_token=$_GET['access_token'];

$config = array(
    'ShopUrl' => $shop_url,
    'AccessToken' => $access_token,
    'ApiVersion' => '2022-10', 
);

PHPShopify\ShopifySDK::config($config);
$shopify = new PHPShopify\ShopifySDK;
$params = array(
    'title' => "Hansel from Basel"
);
// $products = $shopify->SmartCollection->get($params);
$products = $shopify->SmartCollection->count($params);


echo '<pre>';
print_r($products);
echo '</pre>';

// die();