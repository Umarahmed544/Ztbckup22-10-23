<?php

// Get our helper functions
require_once("inc/functions.php");

// Set variables for our request
$shop = "abhishek-zehntech";
$token = "shpua_b71c985a231dac201e218a7e089266d1";
$query = array(
	"Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
);

// Run API call to get products
$products = shopify_call($token, $shop, "/admin/products.json", array(), 'GET');

// Convert product JSON information into an array
$products = json_decode($products['response'], TRUE);

// Get the ID of the first product
$product_id = $products['products'][0]['id'];

// echo "<pre>";
// print_r($products['products']);
// echo "</pre>";

?>
<table border="1" width="100%">
<tr>
	<th>S.No.</th>
	<th>Product ID</th>
	<th>Product Tile</th>

</tr>
<style>
	td{
		text-align: center;
	}
</style>
<?php
$i= 1;
foreach($products['products'] as $key => $product){
	echo "<tr>
	<td>".$i++."</td>
	<td>".$product['id']."</td>
	<td>".$product['title']."</td>
	</tr>";	
}

?>
</table>

<?php 
/*
// Modify product data
$modify_data = array(
	"product" => array(
		"id" => $product_id,
		"title" => "My New Title"
	)
);

// Run API call to modify the product
$modified_product = shopify_call($token, $shop, "/admin/products/" . $product_id . ".json", $modify_data, 'PUT');


// Storage response
 $modified_product_response = $modified_product['response'];*/
