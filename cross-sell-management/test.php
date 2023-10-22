<?php
require_once("inc/functions.php");
require __DIR__ . '/vendor/autoload.php';
include 'inc/shop_config.php';  
$col_name = 'product_id';
$table = 'ZT_cross_sell';
$condition_pid['where'] = array('product_id' => $_GET['pid']);
$row_pid = $db_config->getcolumn($col_name, $table);

print_r($row_pid);