<?php
ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

// Set variables for our request
$shop = $_GET['shop'];

// print_r($_GET);
// die();

$api_key = "d36c2624eb3545100887bde6bb184448";
$scopes = "read_orders,write_products,read_themes,write_themes";
$redirect_uri = "https://kavitapatidar.zehntech.net/csm/cross-sell-management/generate_token.php";

// Build install/approval URL to redirect to
echo $install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . $redirect_uri;

//die();

// Redirect
header("Location: " . $install_url);
die();

?>