<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

//$data_array = {"email":"george.washington3@klaviyo.com","email_consent":"true"};

$data_array = array("email"=>"george.washington6@klaviyo.com","email_consent"=>"true");


$response = $client->request('POST', 'https://a.klaviyo.com/api/v2/list/WE9Bpp/members?api_key=pk_7b16755cb90e647cf1a1aaed020e3d0d46', [
    'body' => '{"profiles":['.json_encode($data_array).']}',
    'headers' => [
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
]);

echo $response->getBody();