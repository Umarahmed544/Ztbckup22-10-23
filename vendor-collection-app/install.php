<?php
// Set variables for our request
$shop = $_GET['shop'];
$api_key = "719c69b68237ed10164a8758f9fda0a1";
$scopes = "read_orders,write_products,read_themes,write_themes,read_product_listings";

$redirect_uri = "https://vendercollectioncreation.wearesott.com/generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);
// .myshopify.com
// Redirect
header("Location: " . $install_url);
die();