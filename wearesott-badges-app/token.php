<?php
// Get our helper functions

// Set variables for our request
$api_key = "dbe476b7eb7b711ce3a39fbce98f7d14";
$shared_secret = "1e01045c0c10c8ceb5725f6548b6395c";
$params = $_GET; // Retrieve all request parameters
echo $hmac = $_GET['hmac']; // Retrieve HMAC request parameter

die();
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
	echo $access_token;


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

	if ($result){
		header("Location: https://" . $params['shop'] . "/admin/apps/badges-9");
		exit();
	}else{
		echo "error";
	}

} else {
	// Someone is trying to be shady!
	die('This request is NOT from Shopify!');
}