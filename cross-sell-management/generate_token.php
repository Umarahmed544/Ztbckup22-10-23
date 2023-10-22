<?php
ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
// Get our helper functions
require_once("inc/functions.php");

// Set variables for our request
$api_key = "d36c2624eb3545100887bde6bb184448";
$shared_secret = "28211c992bb7931f71433207e08656c3";
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
	
	
	// print_r($params['shop']);
    // die('test');

	include 'inc/DB.class.php';
	$db = new DB();
	$table_name = 'ZT_shop_details';

	$data = array(
		'shop_url ' => $params['shop'],
		'access_token ' => $access_token,
		'status' => 1
	);

	$conditions['where'] = array('shop_url' => $params['shop']);
	$conditions['return_type'] = 'count';
	$row = $db->getRows($table_name,$conditions);

	if ($row < 1){
		$result = $db->insert($table_name, $data );
	}else{
		$condition = array('shop_url' => $params['shop']);
        $result = $db->update($table_name, $data ,$condition );
	}

	// header("Location: Dashboard.php");
	// exit();
	if ($result){
		include "ZT_file.php";
		include "create_uninstall_webhook.php";
		header("Location: https://" . $params['shop'] . "/admin/apps/zt-crosssell");
		exit();
	}else{
		echo "error";
	}

} else {
	// Someone is trying to be shady!
	die('This request is NOT from Shopify!');
}
