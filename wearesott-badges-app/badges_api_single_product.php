<?php 
header("Access-Control-Allow-Origin: *");
require_once("inc/DB.class.php");
include_once("inc/functions.php");
$db = new DB();
$table_name="ZT_badges";

$ids = substr($_REQUEST['id'],0,-1);


$conditions['order_by'] = "priority";
$conditions['limit'] = "1";

/* Do not remove this  */
// for the IN
$conditions['where'] = array(
    'collection_id' => $ids,
);

$conditions['return_type'] = 'single';

$result = $db->badges_single($table_name,$conditions, $ids);
echo json_encode($result);

