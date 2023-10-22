<?php
require __DIR__ . '/vendor/autoload.php';

PHPShopify\ShopifySDK::config($config);
$scopes = 'read_products,write_products,read_script_tags,write_script_tags';
echo $accessToken = \PHPShopify\AuthHelper::createAuthRequest($scopes);

