<?php
// echo print_r($_GET);
$shopify = $_GET;

$sql = "SELECT * FROM `shopifyapp2` WHERE `shop_url`='". $shopify['shop']."' LIMIT 1 ";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) < 1){
header("Location: install.php?shop=" . $shopify['shop']);
exit();
}else{
    $shop_row = mysqli_fetch_assoc($result);

    $shop_url= $shopify['shop'];
    $token=$shop_row['access_token'];
}
?>