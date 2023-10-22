<?php 
// include("inc/Shopify_verification.php");
include_once("inc/DB.class.php");
$db = new DB();
$table_name="ZT_badges";

// print_r($_REQUEST);

if(isset($_POST['edit_badge'])){

  if($_REQUEST['edit_badge_collection'] == null){
    $data_edit = array(
      'title' => $_REQUEST['edit_badge_name'],
      'link' => $_REQUEST['edit_badge_link']
    );
  }
  elseif($_REQUEST['edit_badge_name'] == null){
    $data_edit = array(
      'collection_id' => $_REQUEST['edit_badge_collection'],
      'link' => $_REQUEST['edit_badge_link']
    );
  }
  elseif($_REQUEST['edit_badge_link'] == null){
    $data_edit = array(
      'collection_id' => $_REQUEST['edit_badge_collection'],
      'title' => $_REQUEST['edit_badge_name']  
    );
  }
  else{ 
    $data_edit = array(
      'collection_id' => $_REQUEST['edit_badge_collection'],
      'title' => $_REQUEST['edit_badge_name'],
      'link' => $_REQUEST['edit_badge_link'] 
    );
  }
  $msg = "updt";
  $condition_edit['id'] = $_REQUEST['id'];
  $result = $db->update($table_name,$data_edit, $condition_edit );
}

// if(isset($_POST['disable_badge'])){
//   $condition_check['where'] = array('id' => $_REQUEST['id']);
//   $row = $db->getRows($table_name, $condition_check);
//   $check = $row[0]['is_delete'];

//   if($check == 1){ 
//     $data_update = array('is_delete' => 0);
//   }else{ 
//     $data_update = array('is_delete' => 1);
//   }

//   $condition_del['id'] = $_REQUEST['id'];
//   $result = $db->update($table_name, $data_update, $condition_del );
// }

if(isset($_POST['delete_badge'])){
  $condition['order_by'] = 'priority';
  $row = $db->getRows($table_name, $condition);

  $tr_id = [];
  foreach ($row as $key => $value) {
    $tr_id[] =  $value['id'];
  }
  $RP = $_REQUEST['priority'];

  for ($order_no= $RP; $order_no <= count($row) ; $order_no++) {
    $data_update = array(
      'priority' => $order_no
    );

    $condition_up['id'] = $tr_id[$order_no];
    $result = $db->update($table_name, $data_update, $condition_up );
  }
  $condition_del['id'] = $_REQUEST['id'];
  
  $msg = 'del';
  $result = $db->delete($table_name, $condition_del );

}

if(isset($_POST['save_badges'])){
  $condition['order_by'] = 'priority';
  $row = $db->getRows($table_name, $condition);

  $tr_id = [];
  foreach ($row as $key => $value) {
    $tr_id[] =  $value['id'];
  }

  for ($order_no= 0; $order_no <= count($row); $order_no++) {

    $data_update = array(
      'priority' => $order_no+2
    );

    $condition_up['id'] = $tr_id[$order_no];    
    $result = $db->update($table_name, $data_update, $condition_up );
  }

  $data = array(
    'title' => $_REQUEST['badges_title'],
    'collection_id' => $_REQUEST['productCollection'],
    'link' => $_REQUEST['badges_link'],
    'priority' => 1,
  );
  $msg = 'ins';
  $result = $db->insert($table_name, $data );  
}

header("Location: dashboard.php?shop=".$_GET['shop']."&$msg=1");

?>

