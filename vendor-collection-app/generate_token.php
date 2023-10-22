<?php

// Get our helper functions
require_once("inc/functions.php");

// Set variables for our request
$api_key = "719c69b68237ed10164a8758f9fda0a1";
$shared_secret = "3fd1b5b40f81b8816509b76084c0a4ac";
$params = $_GET; // Retrieve all request parameters
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter

$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically

$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

// Use hmac data to check that the response is from Shopify or not
if (hash_equals($hmac, $computed_hmac)) {

	// Set variables for our request
	$query = array(
		"client_id" => $api_key, // Your API key
		"client_secret" => $shared_secret, // Your app credentials (secret key)
		"code" => $params['code'] // Grab the access key from the URL
	);

	// Generate access token URL
	$access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";

	// Configure curl client and execute request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $access_token_url);
	curl_setopt($ch, CURLOPT_POST, count($query));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
	$result = curl_exec($ch);
	curl_close($ch);

	// Store the access token
	$result = json_decode($result, true);
	$access_token = $result['access_token'];

	// Show the access token (don't do this in production!)
	// echo $access_token;
	// ?access_token=" . $access_token
	// header("Location: vendor-collection.php");
	?>

	<center>
		<h2>To create collection for vendors that don't have one - 
		<!-- <a href="https://vendor-colection-store.myshopify.com/pages/designers" target="_blank"> -->
		<!-- <button>press!!!</button></a> -->
		<button onclick="openWin()">press!!!</button></h2>
		<p id="add_msg" ></p>
	</center>

	<script>
		let myWindow;
		function openWin() {
			myWindow = window.open("https://timpanys-dress-agency.myshopify.com/pages/vcc", "_blank", "width=500, height=500");
			// setTimeout(openWin, 60*1000);
		}

		// openWin();
	</script>
	<?php
} else {
	// Someone is trying to be shady!
	die('This request is NOT from Shopify!');
}