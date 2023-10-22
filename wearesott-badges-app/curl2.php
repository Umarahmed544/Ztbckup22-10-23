<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *"); 

$shop = 'timpanys-dress-agency.myshopify.com';
$token = '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';


// print_r(json_encode($current_product_data));
// die();

function graphpQL_shopify_call($token, $shop){

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.wearesott.com/pages/vcc',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
          'X-Shopify-Access-Token: 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',
          'Content-Type: application/json'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
      return $response;

  }


echo graphpQL_shopify_call($token, $shop);
