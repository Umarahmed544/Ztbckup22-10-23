<?php
//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
error_reporting(0);
  include ('DB.class.php');
  $db_config = new DB();
  $table_shop_details = 'ZT_shop_details';

  $conditions['where'] = array(
    'shop_url' => $_GET['shop'],
    'status' => 1
  );
  $conditions['return_type'] = 'count';
  $row = $db_config->getRows($table_shop_details,$conditions);

  if ($row < 1){
    header("Location: install.php?shop=" . $_GET['shop']);
  exit();
  }else{
    $condition_access_token['where'] = array('shop_url' => $_GET['shop']);    
    $row_access_token = $db_config->getRows($table_shop_details,$condition_access_token); 
    $access_token = $row_access_token[0]['access_token'];
    $shop_url = $_GET['shop'];
    $config = array(
      'ShopUrl' => $shop_url,
      'AccessToken' => $access_token,
      'ApiVersion' => '2022-10', 
    );
    PHPShopify\ShopifySDK::config($config);
    $shopify = new PHPShopify\ShopifySDK;
  }
?>