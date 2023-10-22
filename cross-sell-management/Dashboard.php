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
// echo

$nextPageURL = str_btwn($headers['link'], '<', '>');
$nextPageURLparam = parse_url($nextPageURL);
parse_str($nextPageURLparam['query'], $value);
$page_info = $value['page_info'];

?>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" >
    <link rel="stylesheet" href="./assets/css/jquery.dataTables.min.css"></style>
  </head>
  <body>
    <div class="container-fluid">
      <?php
      $params = array(
        'limit' => $limit  
      );
      $products = $shopify->Product->get($params);
      ?>
      <h1 class="text-center">Dashboard</h1>
      <input type="text" id="myInput" onkeyup="mySearch()" placeholder="Search for products" >
      <table id="myTable" class="table text-center mt-2" > <!-- table-striped -->
        <thead>
          <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Product Image</th>
            <th scope="col">Product ID</th>
            <th scope="col">Product Title</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody id="td1">
          <?php 
          $i= 1;
          foreach ($products as $key => $value) {

          $title= $value['title'];
          $id= $value['id'];
          if(empty($value['images'])){
            $image="image/no-img.png";
            $alt = 	"no-image";	
          }
          else{
            $image = $value['image']['src'];
            $alt = 	$value['image']['alt'];	
          }?>
          <tr>
            <th scope="row"><?php echo $i++; ?></th>
            <td><a href="api_call_single_product.php?pid=<?php echo $id;?>&shop=<?php echo $_GET['shop'];?>"><img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" 
              style=" height:30px; width:30px"></a></td>
            <td><?php echo $id; ?></td>
            <td><a href="api_call_single_product.php?pid=<?php echo $id;?>&shop=<?php echo $_GET['shop'];?>" style="text-decoration: none; color:black">
              <?php echo $title; ?></a></td>
            <td><button class="btn btn-sm btn-success" disabled style="cursor:no-drop">Up-sell+</button>
            <a href="api_call_single_product.php?pid=<?php echo $id;?>&shop=<?php echo $_GET['shop'];?>" class="btn btn-sm btn-success">Cross-sell+</a></td>
          </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
      <div class="row"> 
        <div class="col text-center">
          <button type="button" data-info="" data-rel="previous" data-store="<?php echo $shop_url; ?>" class="page btn btn-secondary py-1"><< Prev</button>
          <button type="button" data-info="<?php echo $page_info; ?>" data-rel="next" data-store="<?php echo $shop_url; ?>" class="page btn btn-secondary py-1">Next >></button>
        </div>
      </div>
    </div>
    <script src="./assets/js/jquery-3.4.1.min.js" ></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js" ></script>
    <script src="./assets/js/jquery.dataTables.min.js"></script> 
    <script>
    // $(document).ready(function(){
    //   $('#myTable').dataTable();
    // });

    $('.page').on('click', function(e) {
      var data_info = $(this).attr('data-info');
      var data_rel = $(this).attr('data-rel');
      var data_store = $(this).attr('data-store');
      var data_limit = <?php echo $limit ?>;

      if(data_info != '') {
        $.ajax({
          type: "GET",
          url: "pagination.php", 
          data: {
            limit: data_limit,
            page_info: data_info,
            rel: data_rel,
            url: data_store
          },           
          dataType: "json",               
          success: function(response) {
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

            if( response['html2'] != '' ) {
              $('#td1').html(response['html2']);
            }
          }
        });
      }
    });

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
  