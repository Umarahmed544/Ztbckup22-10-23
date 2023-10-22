<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
require_once("inc/DB.class.php");
$db = new DB();
$table_name="ZT_badges";

$ids = substr($_REQUEST['id'],0,-1);
//$result =$db->badges($table_name, $ids);

$conditions['order_by'] = "priority";


$result = $db->getRows($table_name,$conditions);

echo "<pre>";
print_r($result);
echo "</pre>";
//$sql = "SELECT title FROM $table_name WHERE collection_id IN($ids) order by status LIMIT 1";//
//echo json_encode($result);
//echo $sql = "SELECT title FROM $table_name WHERE collection_id IN($ids) order by status LIMIT 1";
//echo $_REQUEST['id'];

?>
  