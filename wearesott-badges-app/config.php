<?php
include_once("inc/DB.class.php");
$db = new DB();
$table_shop_details = 'ZT_shop_details';

$shop_url = 'timpanys-dress-agency.myshopify.com';
$condition_access_token['where'] = array('shop_url' => $shop_url);    
$row_access_token = $db->getRows($table_shop_details,$condition_access_token); 
$access_token = $row_access_token[0]['access_token'];