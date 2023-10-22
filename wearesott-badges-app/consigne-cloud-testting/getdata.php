<?php 
ini_set('display_errors', 1);

include_once("../inc/DB.class.php");
$db = new DB();
$table_name="consigncloud_supplier_items";


// $conditions['where'] = array(
//     'id' => 1,
// );
// $sql = 'SELECT `email`,GROUP_CONCAT(`param`) FROM consign_cloud_item_data GROUP BY email';
// $result = mysqli_query($conn,$sql);

//$result = $db->getRows($table_name, $conditions); 

$result = $db->get_suppliers_email_list($table_name); 
$item_data = [];
foreach($result as $key=>$res){   
    $supplier_id =  $res['id'];    
    $item_data[] = $db->get_suppliers_item('consigncloud_supplier_items',$supplier_id);
      
}


// $item_result1 = [];
// foreach($item_data as $key=>$item_result){
//    echo $key;
//    echo "<br>";
//    echo $item_result[$key];  
// }

// echo "<pre>";
// print_r($item_data);
// echo "</pre>";