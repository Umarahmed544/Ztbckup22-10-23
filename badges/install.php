<?php
$api_key = "080d215b3d05eb81393b0d6b11217494";


$_NGROK_URL = 'https://c28e-111-118-246-106.in.ngrok.io';
$shop = $_GET['shop'];
$scopes = "read_orders,write_products,read_products";
$redirect_uri = $_NGROK_URL . "/badges/token.php";

$oauth_url ='https://' .  $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri) ;
header("Location: " . $oauth_url);
exit();
