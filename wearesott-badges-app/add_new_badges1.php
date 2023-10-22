<?php
$access_token='';
$shop='';

function graphpQL_shopify_call($access_token, $shop, $query  = array()){

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://'.$shop.'/admin/api/2023-01/graphql.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($query),
        CURLOPT_HTTPHEADER => array(
            'X-Shopify-Access-Token: '.$access_token.'',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

$query_type_tag_vendor = array("query" => '
   {
        collections {
            edges {
                node {
                    id
                    title
                }
            }
        }
    }

');

    $mutation =  graphpQL_shopify_call($access_token, $shop, $query_type_tag_vendor);

    print_r($mutation);

// echo '<select name="collections">';
// foreach ($collections as $collection) {
//     $id = $collection['node']['id'];
//     $title = $collection['node']['title'];
//     echo '<option value="' . $id . '">' . $title . '</option>';
// }
// echo '</select>';