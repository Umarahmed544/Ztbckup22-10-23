<?php
$params = $_GET; // Retrieve all request parameters
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter
$shared_secret = "1e01045c0c10c8ceb5725f6548b6395c";
$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically
$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);
// Use hmac data to check that the response is from Shopify or not
if (!hash_equals($hmac, $computed_hmac)) {
 die('Something went wrong!!!');
}
?>