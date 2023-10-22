<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" >
  </head>
  <body>
<?php 
include 'inc/DB.class.php';

$db = new DB();
$table_name = 'ZT_cross_sell';
$table_name_setting="ZT_settings"; 

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST['update_cross_sell_product'])) {
    $delete_data = array(
      'store_id ' => $_REQUEST['shop'],
      'product_id ' => $_GET['pid'],
      'assignee_product ' => json_encode($_REQUEST['delete_cross-sell_product']),
    );
    $condition = array('product_id' => $_GET['pid']);
    $result = $db->update($table_name, $delete_data ,$condition );   
    die();  
  }
  if(isset($_POST['save_cross_sell_product'])){

    $arr = explode(',', $_REQUEST['csp']);
    $data = array(
      'store_id ' => $_REQUEST['shop'],
      'product_id ' => $_GET['pid'],
      // 'assignee_product ' => json_encode($_REQUEST['cross-sell_product']),
      'assignee_product ' => json_encode($arr),
    );
    
    $conditions['where'] = array('product_id' => $_GET['pid']);
    $conditions['return_type'] = 'count';
    $row = $db->getRows($table_name,$conditions);
  
    if ($row < 1){
        $result = $db->insert($table_name, $data );
        // header("Location: dashboard.php");
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Products added in Cross-sell.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
    }else{
        $condition = array('product_id' => $_GET['pid']);
        $result = $db->update($table_name, $data ,$condition );
        // header("Location: Dashboard.php");
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Products updated in Cross-sell.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
    }        
    die();
  }
  if(isset($_POST['upsell_save_setting'])) {
    
    $condition_upsell_row['where'] = array(
      'shop ' => $_REQUEST['shop'],
      'type ' => 'up_sell',
    );
    $condition_upsell_row['return_type'] = 'count';
    $upsell_row = $db->getRows($table_name_setting,$condition_upsell_row);

    if ($upsell_row < 1){
      $upsell_setting_data = array(
        'shop ' => $_REQUEST['shop'],
        'type ' => $_REQUEST['up_sell'],
        'status' =>'1',
      );
      $result = $db->insert($table_name_setting, $upsell_setting_data );
      echo"Data inserted up sell";
      }else{
        if($_REQUEST['up_sell'] == null){
          $upsell_setting_update_data = array('status' => '0');
          echo"Data updated up sell with 0 value";
        }else{
          $upsell_setting_update_data = array('status' => '1'); 
          echo"Data updated up sell with 1 value";
        }
  
        $upsell_setting_update_condition = array(
          'shop' =>  $_REQUEST['shop'],
          'type ' => 'up_sell'
        );
      $result = $db->update($table_name_setting, $upsell_setting_update_data ,$upsell_setting_update_condition ); 
    }
    // header("Location: settings.php");
    die();
  }
  if(isset($_POST['cross_save_setting'])) {
    $condition_crossell_row['where'] = array(
      'shop ' => $_REQUEST['shop'],
      'type ' => 'cross_sell',
    );
    $condition_crossell_row['return_type'] = 'count';
    $crossell_row = $db->getRows($table_name_setting,$condition_crossell_row);
    
    if ($crossell_row < 1){
      $crossell_setting_data = array(
          'shop ' => $_REQUEST['shop'],
          'type ' => $_REQUEST['cross_sell'],
          'status' =>'1',
        );
      $result = $db->insert($table_name_setting, $crossell_setting_data );
  
      echo"Data inserted cross sell";
    }else{
      if($_REQUEST['cross_sell'] == null){
        $crossell_setting_update_data = array('status' => '0'); 
        echo"Data updated cross sell with 0 value";
      }else{
        $crossell_setting_update_data = array('status' => '1'); 
        echo"Data updated cross sell with 1 value";
      }
  
      $crossell_setting_update_condition = array(
        'shop' =>  $_REQUEST['shop'],
        'type ' => 'cross_sell'
      );
      $result = $db->update($table_name_setting, $crossell_setting_update_data ,$crossell_setting_update_condition );
    
    }
    // header("Location: test.php");
    die();   
  }
}

?>
<script src="./assets/js/jquery-3.4.1.min.js" ></script>
<script src="./assets/js/popper.min.js"></script>
<script src="./assets/js/bootstrap.min.js" ></script>
</body>
</html> 
  