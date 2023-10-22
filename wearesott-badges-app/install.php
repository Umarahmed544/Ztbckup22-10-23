<?php

$shop = $_GET['shop'];
$api_key = "dbe476b7eb7b711ce3a39fbce98f7d14";

$scopes = "read_orders,write_products,read_products,read_product_listings";

$redirect_uri = "https://badges.wearesott.com/token.php";

$oauth_url ='https://' .  $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri) ;
header("Location: " . $oauth_url);
exit();
