<?php 
include_once("inc/functions.php");
include_once("config.php");

$table_name="ZT_badges";

$condition_row['where'] = array('id' => $_REQUEST['id']);
$row = $db->getRows($table_name, $condition_row);

 $coll_id = $row[0]['collection_id'];

$collection =  shopify_call($access_token, $shop_url,"/admin/api/2022-10/collections//$coll_id.json", array() , 'GET');
$collection = json_decode($collection['response'], JSON_PRETTY_PRINT);


foreach($collection as $key => $value){

    $a = $value['title'];
}

$row[0]['colection_title'] = $a;
echo json_encode($row);
?>