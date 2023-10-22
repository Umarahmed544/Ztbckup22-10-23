<?php
$shop = $_GET['shop'];
$api_key = '75e4957e3c8aa4d541f36b2bf21d95fa';

$scopes = "read_orders,write_orders,write_products,read_products";

$redirect_uri = "https://apps.wearesott.com/phia/token.php";

$oauth_url ='https://' .  $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri) ;

header("Location: " . $oauth_url);

exit();