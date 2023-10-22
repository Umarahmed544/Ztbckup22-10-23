<?php 
include 'inc/DB.class.php';
$db = new DB();
$table = 'stores';
$req = file_get_contents('php://input');

$result = json_decode($req);
$data = array(
    'status' => 0
  );
  $condition = array('shop_url' => $result->domain);
  $result1 = $db->update($table, $data ,$condition );
  
  die();
