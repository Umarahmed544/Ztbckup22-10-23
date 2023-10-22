<?php 
include_once("inc/DB.class.php");
print_r($_REQUEST);
$db = new DB();
$table_name="ZT_badges";

  if(isset($_POST['save_badges'])){
    $data = array(
      'title' => $_REQUEST['badges_title'],
      'collection' => $_REQUEST['productCollection'],
      'link' => $_REQUEST['badges_link'],
      'status' => 1,
    );
    
    // $conditions['where'] = array('product_id' => $_GET['pid']);
    // $conditions['return_type'] = 'count';
    // $row = $db->getRows($table_name,$conditions);
    
    // if ($row < 1){
        $result = $db->insert($table_name, $data );
        // header("Location: test.php");
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Badge assigned to Collection.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>';
    // }else{
    //     $condition = array('product_id' => $_GET['pid']);
    //     $result = $db->update($table_name, $data ,$condition );
    //     // header("Location: test.php");
    //     echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    //     <strong>Success!</strong> Products updated in Cross-sell.
    //     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    //       <span aria-hidden="true">&times;</span>
    //     </button>
    //   </div>';
    // }        
    die();
  }