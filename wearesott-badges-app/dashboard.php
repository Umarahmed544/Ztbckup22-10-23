<style>
    td:hover{
		cursor:move;
	}

  span.select2.select2-container.select2-container--default.select2-container--below.select2-container--open {width: 100% !important;}
  span#select2-edit_badge_collection-container { width: 460px !important;}

  .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('./assets/images/loader.gif') 50% 50% no-repeat #020202;
    opacity: .5;
  }

  /* .disabled_row{
    background: dimgrey;
    color: linen;
  } */
</style>

<?php
// include("inc/Shopify_verification.php");
include_once("inc/functions.php");
include_once("config.php");
$table_name="ZT_badges";
$condition['order_by'] = 'priority';
$row = $db->getRows($table_name, $condition);

$page_info = [
  "eyJsYXN0X2lkIjoxNjM1MDY5NDYxNjEsImxhc3RfdmFsdWUiOiJiYW1mb3JkIn0",
  "eyJsYXN0X2lkIjo1MzAyMTg5MSwibGFzdF92YWx1ZSI6ImRlc2lnbmVyIGJhZ3MifQ",
  "eyJsYXN0X2lkIjoxNjM1MDkyNzI2ODksImxhc3RfdmFsdWUiOiJmZW5kaSJ9",
  "eyJsYXN0X2lkIjoxNjM1MTA5MTEwODksImxhc3RfdmFsdWUiOiJqdW1wZXIgMTIzNCJ9",
  "eyJsYXN0X2lkIjoxNjM1MTIzNTI4ODEsImxhc3RfdmFsdWUiOiJtYXJzZWxsIn0",
  "eyJsYXN0X2lkIjoxNjM1MTM3NjE5MDUsImxhc3RfdmFsdWUiOiJyYWcgJiBib25lIn0",
  "eyJsYXN0X2lkIjoyNjY5MjUzMTAwNjUsImxhc3RfdmFsdWUiOiJzaXplIHVrIDEyIn0",
  "eyJsYXN0X2lkIjoxNjM1MTYyODUwNDEsImxhc3RfdmFsdWUiOiJ2aWN0b3JpYSBiZWNraGFtIn0"
];

$count = count($page_info);
$data = array();
$results = [];

for ($i = 0; $i < $count; $i++) {
  $arr = array(
    'page_info' => $page_info[$i]
  );
 
  $smart_collections = shopify_call($access_token, $shop_url,"/admin/api/2022-10/smart_collections.json", $arr , 'GET');
  $data[$page_info[$i]] = json_decode($smart_collections['response'], JSON_PRETTY_PRINT);
  $results = array_merge($results, $data[$page_info[$i]]['smart_collections']);
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css" >
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css">
  <!-- <script>
    $(".alert-danger").hide();
    $(".alert-success").hide();
  </script> -->
</head>
<body>
<div class="loader" style="display:none"></div>

<?php if($_REQUEST['ins'] == 1){?>  
<div class="alert icon-alert with-arrow alert-info form-alter" role="alert"> 
    <strong> Success ! </strong> <span class="success-message">New Badge has been created successfully.</span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="cross_alert()" >
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php } ?>


<?php if($_REQUEST['updt'] == 1){?>  
<div class="alert icon-alert with-arrow alert-info form-alter" role="alert"> 
    <strong> Success ! </strong> <span class="success-message">Badge has been updated successfully. </span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="cross_alert()" >
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php } ?>



<?php if($_REQUEST['del'] == 1){?>  
<div class="alert icon-alert with-arrow  alert-info form-alter" role="alert">
    <strong> Success ! </strong> <span class="success-message">Badge has been deleted successfully. </span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="cross_alert()" >
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php } ?>

  <div class="container">
    <div class="row p-3">
      <div class="col-8 p-0">
        <h3>Badges</h3>
      </div>
      <div class="col-4">
        <a href="add_new_badges.php?shop=<?php echo $_GET['shop'];?>" class="float-right btn btn-sm btn-primary" id="btnFetch">Add New Badge</a>
      </div>
    </div>
    <div class="row">

      <div class="col-lg-12">
        <table class="table table-hover" id="myTable">
          <thead>
          <tr>
            <th scope="col" >S.No.</th>
            <th scope="col">Badges Name</th>
            <th scope="col">Collection </th>
            <th scope="col">Priority</th>
            <th scope="col">Actions</th>
          </tr>
          </thead>
          <tbody id="tbody">
            <?php
              $i=0;
              foreach ($row as $key => $value) {
                $id = $value['id'];
                $title = $value['title'];
                $collection_id = $value['collection_id'];
                $priority = $value['priority'];

                // <!-- enable/disable functionality -->
                // if($value['is_delete'] == 0){
                //   $btn_label = 'Disable';
                //   $labeld_class = 'btn-danger';
                //   $row_disabled_class = "";
                //   $display = '';

                // }else{
                //   $btn_label = 'Enable';
                //   $labeld_class = 'btn-success';
                //   $row_disabled_class =  "disabled_row";
                //   $display = '"display:none"';
                // }
                ?>
                <tr data-post-id="<?php echo $id; ?>" class="<?php echo $row_disabled_class;?>"> 
                  <td class="index"><?php echo ++$i; ?></td>
                  <td product-id="<?php echo $title; ?>"><?php echo $title; ?></td>
                  <td><?php
                  $collection =  shopify_call($access_token, $shop_url,"/admin/api/2022-10/collections//$collection_id.json", array() , 'GET');
                  $collection = json_decode($collection['response'], JSON_PRETTY_PRINT);
                  foreach($collection as $key => $value){
                    echo $value['title'];
                  }
                  ?></td>
                  <td class="index1"><?php echo $priority;?></td>
                  <td>
                    <form  action="save.php" method="post" id="delete_badge_form">
                      <input type="hidden" value="<?php echo $id;?>" name="id">
                      <input type="hidden" value="<?php echo $priority;?>" name="priority">
                      <button class="btn btn-sm btn-info" type="button" db-id="<?php echo $id;?>" data-toggle="modal" data-target="#edit_badge" >Edit</button>
                      <!-- <button class="btn btn-sm btn-info" type="button" db-id="<?php echo $id;?>" data-toggle="modal" data-target="#edit_badge" style=<?php echo $display;?>>Edit</button> -->
                      <!-- <button class="btn btn-sm <?php echo $labeld_class;?>" type="submit" name="disable_badge"><?php echo $btn_label;?></button> -->
                      <button class="btn btn-sm btn-danger " type="submit" name="delete_badge" id="delete_badge" onclick="return confirm('Are you sure want to delete item ?')">Delete</button>
                    </form >
                  </td>
                </tr>
                <?php
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="edit_badge" tabindex="-1" role="dialog" aria-labelledby="edit_badge_Title" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
      <div class="modal-content">
        <form  action="save.php"  id="edit_badge_form" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="edit_badge_Title">Edit Badges</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="edit_badge_name" class="form-label">Badge Name</label>
              <input type="text" class="form-control" name="edit_badge_name" id="edit_badge_name">
            </div>
            <div class="form-group">
              <label for="edit_badge_link" class="form-label">Badge Link</label>
              <input type="text" class="form-control" name="edit_badge_link" id="edit_badge_link">
            </div>
            <div class="form-group d-flex m-0"><p>Current Collection - </p><p class="font-weight-bold text-success px-1" id="badge_collection"></p></div>
            <div class="form-group">
              <label for="edit_badge_collection" class="form-label edit_badge_collection">Select New Collection</label>
              <select class="form-control" id="edit_badge_collection" name="edit_badge_collection" >
                <?php
                $custom_collections = shopify_call($access_token, $shop_url,"/admin/api/2022-10/custom_collections.json", array(), 'GET');
                $custom_collections = json_decode($custom_collections['response'], JSON_PRETTY_PRINT);
                foreach($custom_collections as $custom_collection){
                  foreach($custom_collection as $key => $value){
                    ?>
                    <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                    <?php
                  }
                }
                $smart_collections = shopify_call($access_token, $shop_url,"/admin/api/2022-10/smart_collections.json", array(), 'GET');
                $smart_collections = json_decode($smart_collections['response'], JSON_PRETTY_PRINT);
                foreach($smart_collections as $smart_collection){
                  foreach($smart_collection as $key => $value){
                    ?>
                    <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                    <?php
                  }
                }
                for ($j = 0; $j < 368; $j++) {
                  ?>
                  <option value="<?php echo $results[$j]['id'];?>"><?php echo $results[$j]['title'];?></option>
                  <?php              
                }
              ?><select>
            </div>
            <input type="hidden" id="hidden"  value="" name="id"> 
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="edit_badge">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="./assets/js/jquery-3.4.1.min.js" ></script>
  <script src="./assets/js/popper.min.js"></script>
  <script src="./assets/js/bootstrap.min.js"></script>
  <script src="./assets/js/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-1.8.2.js"></script> -->

  <script>
    function cross_alert(){
      $(".alert-info").hide();
    };
    // $(".alert-danger").hide();
    // $(".alert-success").hide();
    $(document).ready(function() {

      var fixHelperModified = function(e, tr) {
      var $originals = tr.children();
      var $helper = tr.clone();
      $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
      });
      return $helper;
      },
      updateIndex = function(e, ui) {
        $('td.index', ui.item.parent()).each(function (i) {
          $(this).html(i+1);
        });
        $('td.index1', ui.item.parent()).each(function (i) {
          $(this).html(i+1);
        });
        // $('input[type=text]', ui.item.parent()).each(function (i) {
        //   $(this).val(i + 1);
        // });
      };

      $("#myTable tbody").sortable({
        helper: fixHelperModified,
        stop: updateIndex
      }).disableSelection();
      
      $("tbody").sortable({
        distance: 5,
        delay: 100,
        opacity: 0.6,
        cursor: 'move',
        update: function(event, ui) {
          var post_order_ids = new Array();
          $('#tbody tr').each(function() {
            post_order_ids.push($(this).data("post-id"));
          });
          $.ajax({
            url: "update_fn.php",
            method: "POST",
            data: {
              post_order_ids: post_order_ids
            
            },
            success: function(data) {
              console.log(data);
              if (data) {
                $(".alert-danger").hide();
                $(".alert-success").show();
              } else {
                $(".alert-success").hide();
                $(".alert-danger").show();
              }
            }
          });
        }
      });

      $('button[db-id]').on('click', function(){
        var ID = $(this).attr('db-id');
        // console.log(ID);
        $('#hidden').attr('value', ID);

        $.ajax({
          method: 'POST',
          data: {
            id: ID
          },
          url: 'ajax.php',
          success:function(json){
            // console.log(json);
            // console.log(JSON.parse(json)[0]['title']);
            const title = JSON.parse(json)[0]['title'];
            const link = JSON.parse(json)[0]['link'];
            const collection = JSON.parse(json)[0]['collection_id'];
            const collection_t = JSON.parse(json)[0]['colection_title'];
            // console.log(collection_t);
            // console.log( JSON.parse(json)[0]);
            $('#edit_badge_name').val(title);
            $('#edit_badge_link').val(link);
            $('#badge_collection').html(collection_t);
          }
        });
      });
     
      $("#edit_badge_collection").select2();
      $("#edit_badge_collection").select2("val", "4");
      $('#edit_badge_collection').select2({
        placeholder: "Change Collection",
        dropdownParent: $('#edit_badge')
      });
      
      $("#edit_badge_form").submit(function(){
        $('.loader').show();
      });

      $("#delete_badge_form").submit(function(){
        $('.loader').show();
      });
          
      $("#btnFetch").click(function() {
        // disable button  
        $(this).prop("disabled", true);
          // add spinner to button 
        $(this).html( `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`     
        ); 
        // window.location.reload(true);
      }); 

      
    });
    //  $(window).load(function() {
    //   $(".loader").fadeOut(1000);
    //   });
  </script>
  
</body>
</html>

