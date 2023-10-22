<?php
require_once("inc/functions.php");
require __DIR__ . '/vendor/autoload.php';
include 'inc/shop_config.php';

$limit="50"; 
$array = array(
  'limit' => $limit
);
$products = rest_api($access_token, $shop_url, "/admin/api/2021-07/products.json", $array, 'GET');

$headers = $products['headers'];

foreach ($headers as $key => $value) {
 '<div>[' . $key . '] =>' . $value . '</div>';
}

$nextPageURL = str_btwn($headers['link'], '<', '>');
$nextPageURLparam = parse_url($nextPageURL);
parse_str($nextPageURLparam['query'], $value);
$page_info = $value['page_info'];

$table_cross_sell = 'ZT_cross_sell';
    
$condition_pid['where'] = array('product_id' => $_GET['pid']);
$row_pid = $db_config->getRows($table_cross_sell,$condition_pid);
$assigned_products= json_decode($row_pid[0]['assignee_product'], TRUE);

$params = array('limit' => $limit);
$products = $shopify->Product->get($params);
$get_single_product = $shopify->Product($_GET['pid'])->get();
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css" >
</head>
<body>
  <div class="container-fluid">
    <h1 class="text-center"><?php echo $get_single_product['title']; ?></h1>
    
    <div class="card mb-2" value="<?php echo $get_single_product['id']; ?>">
      <div class="row g-0">
          <div class="col-md-4">
          <img src="<?php echo $get_single_product['image']['src']; ?>" class="img-fluid rounded-start" 
          alt="<?php echo $get_single_product['image']['alt']; ?>" style=" height:300px; width:100%">
        </div>
        <div class="col-md-8">
          <div class="card-body"  style="height:300px; overflow-y:auto;">
            <h5 class="card-title"><?php echo $get_single_product['title']; ?></h5>
            <p class="card-text"><?php echo $get_single_product['body_html']; ?></p>
          </div>
        </div>
      </div>
    </div>

    <form action="save.php?pid=<?php echo $get_single_product['id'];?>&shop=<?php echo $_GET['shop'];?>" id="delete_cross_sell_product"
     method="post" <?php if ($assigned_products == null){ echo 'style="display: none;"';} ?>>
     <!-- <div style="height: 50px;">
        <div style="float: right;"> 
          <button type="button" class="btn btn-danger removeall" onclick="rmv()">Remove All</button>
          <button type="submit" class="btn btn-success" id="save" name="update_cross_sell_product">Save</button>
        </div>
      </div> -->
      <div class="d-flex justify-content-end my-2">         
        <button type="button" class="btn btn-danger mx-1 removeall" onclick="rmv()">Remove All</button>
        <button type="submit" class="btn btn-success mx-1" id="save" name="update_cross_sell_product">Save</button>
      </div>
      <div class="card-columns" style="column-count: 1;">
        <?php
        foreach ($assigned_products as $key => $value) {
          $cross_sell_products = $shopify->Product($value)->get(); 
          ?>
          <div class="card" style="width:100px;" product-id="<?php echo $value; ?>">
            <div class="card-body p-0"><small class="card-text"><?php  print_r($cross_sell_products['title']);?></small>
              <span class="float-right"><input type="checkbox" class='assigned' checked name="delete_cross-sell_product[]" 
              id="<?php echo $value;?>" value="<?php echo $value;?>"></span>
            </div>
            <img class="card-img-bottom" src="<?php print_r($cross_sell_products['image']['src']); ?>" alt="<?php print_r($cross_sell_products['image']['alt']); ?>" style="height:100px;">
          </div>
          <?php
        }
        ?>
      </div>
    </form>

    <form action="save.php?pid=<?php echo $get_single_product['id'];?>"  id="save_cross_sell_product" method="post">
      <input type="hidden" value="<?php echo $_GET['shop'];?>" name="shop">
      <input type="text" id="myInput" onkeyup="mySearch()" placeholder="Search for products" >
      <table id="myTable" class="table text-center mt-2">
        <thead>
          <tr>
            <th scope="col"><input type="checkbox" name="sample" class="selectall"></th>
            <th scope="col">Product Image</th>
            <th scope="col">Product ID</th>
            <th scope="col">Product Title</th>
          </tr>
        </thead>
        <tbody  id="td2">
          <?php
          foreach ($products as $key => $value) {
          if ($value['id'] == $_GET['pid']){ unset($key);
          unset($value);} 
          $spid =$value['id'];
          $title= $value['title'];
          if(empty($value['images'])){
            $image="image/no-img.png";
            $alt = 	"no-image";	
          }
          else{
            $image = $value['image']['src'];
            $alt = 	$value['image']['alt'];	
          }
          if ($spid != null){
          ?>
          <tr>
            <th scope="row" ><input type="checkbox" class="justone" name="cross-sell_product[]" id="<?php echo $value['id'];?>" value="<?php echo $value['id'];?>"></th> 
              <?php
              //  foreach ($assigned_products as $key => $value) { if($spid==$value){ echo "checked";}} 
              ?>
            <td ><img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" style=" height:60px; width:60px"></td>
            <td ><?php echo $spid; ?></td>
            <td><?php echo $title; ?></td>
          </tr>
          <?php
          }
          }
          ?>
        </tbody>
      </table>
      <input type="hidden" class="csp" name="csp" id="csp" value=""/>
      <div class="row"> 
        <div class="col text-center">
          <button type="button" data-info="" data-rel="previous" data-store="abhishek-zehntech.myshopify.com" class="page btn btn-secondary py-1">&lt;&lt; Prev</button>
          <button type="button" data-info="eyJsYXN0X2lkIjo4MTMwNjk2ODA2NzA3LCJsYXN0X3ZhbHVlIjoiQS1MaW5lIEphY2tldCIsImRpcmVjdGlvbiI6Im5leHQifQ" data-rel="next" data-store="abhishek-zehntech.myshopify.com" class="page btn btn-secondary py-1">Next &gt;&gt;</button>
          <button type="submit" class="btn btn-success savebtn" disabled="disabled" name="save_cross_sell_product" style="float: right;">Save</button>
        </div>
      </div>
    </form>
  </div>
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
  <script src="./assets/js/jquery-3.4.1.min.js" ></script>
  <script src="./assets/js/popper.min.js"></script>
  <script src="./assets/js/bootstrap.min.js"></script>
  <script>

    $('.justone').on('change', function(e) {
      var array = [];
      $('.justone:checked').each(function() {
        array.push($(this).val());   
      });
      $("#csp").val(array);
    });
  
    $('.page').on('click', function(e) {
      var data_info = $(this).attr('data-info');
      var data_rel = $(this).attr('data-rel');
      var data_store = $(this).attr('data-store');
      var data_limit = <?php echo $limit ?>;
      var data_pid = <?php echo $_GET['pid'] ?>;
      var data_row_pid = <?php echo json_encode($assigned_products); ?>;
      var array1 = $("#csp").val();

      if(data_info != '') {
        $.ajax({
          type: "GET",
          url: "pagination.php", 
          data: {
            row_pid: data_row_pid,
            pid: data_pid,
            limit: data_limit,
            page_info: data_info,
            rel: data_rel,
            url: data_store,

          },           
          dataType: "json",    
                  
          success: function(response) {
            // console.log(data_row_pid[0]);  
            console.log(response);

            if( response['prev'] != '' ) {
              $('button[data-rel="previous"]').attr('data-info', response['prev']);
            } else {
              $('button[data-rel="previous"]').attr('data-info', "");
            }

            if( response['next'] != '' ) {
              $('button[data-rel="next"]').attr('data-info', response['next']);
            } else {
              $('button[data-rel="next"]').attr('data-info', "");
            }

            if( response['html'] != '' ) {
              $('#td2').html(response['html']);
            }

            $('.selectall').click(function() {
              if ($(this).is(':checked')) {
                $("input[type='checkbox'].justone").prop('checked', true);
              $('.savebtn').prop('disabled', false);
              } else {
                $("input[type='checkbox'].justone").prop('checked', false);
              $('.savebtn').prop('disabled', true);
              }
            });

            $("input[type='checkbox'].justone").change(function(){
              var a = $("input[type='checkbox'].justone");
              if(a.length == a.filter(":checked").length ){
                $('.selectall').prop('checked', true);
              }
              else {
                $('.selectall').prop('checked', false);
              }
            });

            $(".justone").click(function() {
              if ($(this).is(':checked')) {
              $('.savebtn').prop('disabled', false);
              }
              else if($(".justone").filter(":checked").length == 0){
              $('.savebtn').prop('disabled', true);
              }
            });

                      
            $('.justone').on('change', function(e) {
              console.log(array1);
              var arr = [array1];
              if(array1 == ''){var arr = [];}
              $('.justone:checked').each(function() {
                arr.push($(this).val());

              });
              $("#csp").val(arr);
            });
          }
        });
      }
    });
  </script>
  <script>
    $(document).ready(function(){
      //   $('#myTable').dataTable();
      // });
      
      $('.selectall').click(function() {
        if ($(this).is(':checked')) {
          $("input[type='checkbox'].justone").prop('checked', true);
        $('.savebtn').prop('disabled', false);
        } else {
          $("input[type='checkbox'].justone").prop('checked', false);
        $('.savebtn').prop('disabled', true);
        }
        var array = [];
          $('.justone:checked').each(function() {
            array.push($(this).val());   
          });
          $("#csp").val(array);
      });

      $("input[type='checkbox'].justone").change(function(){
        var a = $("input[type='checkbox'].justone");
        if(a.length == a.filter(":checked").length ){
          $('.selectall').prop('checked', true);
        }
        else {
          $('.selectall').prop('checked', false);
        }
      });

      $(".justone").click(function() {
        if ($(this).is(':checked')) {
        $('.savebtn').prop('disabled', false);
        }
        else if($(".justone").filter(":checked").length == 0){
        $('.savebtn').prop('disabled', true);
        }
      });
    });

    function rmv(){
      var txt = '<?php echo $get_single_product['title']; ?>';
      if (confirm('Do you really want to remove all products assigned to "'+txt+'" from Cross-sell?')) {
        $("input[type='checkbox'].assigned").prop('checked', false);  
        $("#save").click();
      } else {
        $("input[type='checkbox'].assigned").prop('checked', true);  
      }
    };

    function mySearch(){
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }       
      }
    }
  </script>   
</body>
</html>
