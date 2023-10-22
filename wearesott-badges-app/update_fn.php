<?php
include_once("inc/DB.class.php");
$db = new DB();
$table_name="ZT_badges";

$post_order_ids = [2,3,1,4];
$post_order = isset($_POST["post_order_ids"]) ? $_POST["post_order_ids"] : [];

if(count($post_order)>0){
	for($order_no= 0; $order_no < count($post_order); $order_no++)
	{
    $data_update = array(
        'priority' => $order_no+1
      );
      
    $condition_up['id'] = $post_order[$order_no];
      
      $result = $db->update($table_name, $data_update, $condition_up );

	}
	echo true;
}else{
	echo false;
}

?>