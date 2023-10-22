<?php 
include_once("inc/functions.php");
header("Access-Control-Allow-Origin: *");
require_once("inc/DB.class.php");
$db = new DB();

$table_shop_details = 'ZT_shop_details';
$condition_access_token['where'] = array('shop_url' => 'timpanys-dress-agency.myshopify.com');    
$row_access_token = $db->getRows($table_shop_details,$condition_access_token); 
$access_token = $row_access_token[0]['access_token'];
$shop_url = $row_access_token[0]['shop_url'];
$collection_id = '264839954545';

//$collection_id = $result['collection_id'];

$custom_collections = shopify_call($access_token, $shop_url,"/admin/api/2022-10/collections/$collection_id.json", array(), 'GET');
$custom_collections = json_decode($custom_collections['response'], JSON_PRETTY_PRINT);
$result['collection_handle'] =  $custom_collections['collection']['handle'];

echo "<pre>";
print_r($custom_collections );
echo "</pre>";

die();
//echo json_encode($result);

