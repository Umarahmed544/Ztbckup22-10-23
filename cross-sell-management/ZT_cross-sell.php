<?php 
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/vendor/autoload.php';
include 'inc/DB.class.php';

$db = new DB();
$table_name_shop_details = 'ZT_shop_details';
$condition_access_token['where'] = array('shop_url' => $_REQUEST['shop']);    
$row_access_token = $db->getRows($table_name_shop_details,$condition_access_token); 
$access_token = $row_access_token[0]['access_token'];

$config = array(
  'ShopUrl' => $_REQUEST['shop'],
  'AccessToken' => $access_token,
  'ApiVersion' => '2022-10', 
);
PHPShopify\ShopifySDK::config($config);
$shopify = new PHPShopify\ShopifySDK($config);

$table_name_cross_sell = 'ZT_cross_sell';
$condition_pid['where'] = array('product_id' => $_REQUEST['id']);
$result_pid = $db->getRows($table_name_cross_sell, $condition_pid);

$assigned_products= json_decode($result_pid[0]['assignee_product']);
$cross_sell_products = [];
foreach ($assigned_products as $key => $value) {
  $cross_sell_products[] = $shopify->Product($value)->get();
}

#conditons for active Crossell and Upsell

$table_name_settings = 'ZT_settings';
$condition_cross_sell['where'] = array(
  'shop' => $_REQUEST['shop'],
  'type' => 'cross_sell'
);
$result_cross_sell = $db->getRows($table_name_settings, $condition_cross_sell);
$status_cross_sell = $result_cross_sell[0]['status'];

$condition_up_sell['where'] = array(
  'shop' => $_REQUEST['shop'],
  'type' => 'up_sell'
);
$result_up_sell = $db->getRows($table_name_settings, $condition_up_sell);
$status_up_sell = $result_up_sell[0]['status'];

$ZT_data = array(
  'cross_sell_products' => $cross_sell_products,
  'status_cross_sell' => $status_cross_sell,
  'status_up_sell'  => $status_up_sell
);
echo json_encode($ZT_data);
?>


