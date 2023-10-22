<?php
  include ('inc/DB.class.php');
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
  }
  else{
    include 'dashboard.php';
  }
?>









