<?php
header("Access-Control-Allow-Origin: *");
$access_token="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";
$shop_url="https://timpanys-dress-agency.myshopify.com";
$vendor_names = $_REQUEST['vendors'];

$vendor_arr = explode(',', $vendor_names);
$i= 0;
$a=[];
$c=[];
foreach($vendor_arr as $key => $value){
    $a[]= $i++;
    $c[]= $value;
}

echo json_encode($c);
// print_r(json_encode($arr));
// $jayParsedAry = [
//     "smart_collection" => [
//           "title" => "Macbooks", 
//           "handle" => "macbooks", 
//           "rules" => [
//             [
//               "column" => "variant_price", 
//               "relation" => "less_than", 
//               "condition" => "100" 
//             ]  
//           ] 
//        ] 
//   ];

// $smart_collections = shopify_call($access_token, $shop_url,"/admin/api/2022-10/smart_collections.json",$jayParsedAry, 'POST');
// // $smart_collections = json_decode($smart_collections['response'], JSON_PRETTY_PRINT);

// echo json_encode($_REQUEST);