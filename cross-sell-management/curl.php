<?php 
require_once("inc/functions.php");

// Set variables for our request
$shop = "abhishek-zehntech";
$token = "shpua_2f84a237cb177a1a4d8b4e19dcb931b7";
$query = array(
	"Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
);

// Run API call to get products
$products = shopify_call($token, $shop, "/admin/shop.json", array(), 'GET');

// Convert product JSON information into an array
$products = json_decode($products['response'], TRUE);
// echo "<pre>";
// print_r($products);
// echo "</pre>";

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
$.get('https://kavitapatidar.zehntech.net/csm/cross-sell-management/abhi.php',  // url
      function (data, textStatus, jqXHR) {  // success callback        
          const obj = JSON.parse(data);  
          var jsonarray = obj[1].assignee_product;		
		 var assign_product = JSON.parse(jsonarray);		
		 
			var i;
			for (i = 0; i < assign_product.length; ++i) {				
				
				$('#myTable').append(
					'<tr><td>'
					+ assign_product[i]
					+ '</td></tr>'
				);

			}		

    });
</script>
<table width="500px" id="myTable" border="1"></table>