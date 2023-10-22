<?php 
header("Access-Control-Allow-Origin: *");
include_once("inc/functions.php");
require_once("inc/DB.class.php");
$db = new DB();
$table_name="ZT_badges";

$ids = substr($_REQUEST['id'],0,-1);
$collection_id = $_REQUEST['collection_id'];


$conditions['order_by'] = "priority";
$conditions['limit'] = "1";

/* Do not remove this  */
// for the IN
$conditions['where'] = array(
    'collection_id' => $ids,
);

/* Do not remove this  */
$conditions['return_type'] = 'single';

$result = $db->badges($table_name,$conditions, $ids, $collection_id);

echo json_encode($result);

