<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./files/bootstrap.min.css" >
</head>
<body>
<?php 
include 'inc/DB.class.php';
$db = new DB();
$table_name_setting="ZT_settings"; 

$condition_crossell_row['where'] = array(
  'shop ' => $_GET['shop'],
  'type ' => 'cross_sell',
);
$condition_upsell_row['where'] = array(
  'shop ' => $_GET['shop'],
  'type ' => 'up_sell',
);
  
$crossell_row = $db->getRows($table_name_setting,$condition_crossell_row);
$upsell_row = $db->getRows($table_name_setting,$condition_upsell_row);
?>
<div class="text-center">    
  <h1><?php echo "settings";?></h1>
</div>
<div class="container-fluid">

  <div class="row">
  <div class="col">
    <form action="save.php" id="save_setting_data" method="post" >    
      <br><b>Activate Cross-sell</b>
      <br><p><input type="checkbox" id="checkbox" value="cross_sell" name="cross_sell"  
      <?php { if($crossell_row[0]['status']=="1"){ echo "checked";}} ?>>  Show Cross-sell on products page</p>
      <input type="hidden" value="<?php echo $_GET['shop'];?>" name="shop"> 
      <button  id="submit" class='btn btn-primary' name="cross_save_setting">Save</button>
    </form>
  </div>
  <div class="col">
    <form action="save.php"  id="save_setting_data" method="post" >
      <br><b>Activate Up-sell</b>
      <br><p><input type="checkbox" id="checkbox1" value="up_sell" name="up_sell"
      <?php { if($upsell_row[0]['status']=="1"){ echo "checked";}} ?>>  Show Up-sell on products page</p>
      <input type="hidden" value="<?php echo $_GET['shop'];?>" name="shop">
      <button id="submit1" class='btn btn-primary' name="upsell_save_setting">Save</button>
    </form>
  </div>
</div>
</div>
<script src="./files/jquery-3.4.1.min.js" ></script>
<script src="./files/popper.min.js" ></script>
<script src="./files/bootstrap.min.js" ></script>
</body>
</html> 