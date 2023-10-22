<?php 
ini_set('display_errors', 1);

include_once("../inc/DB.class.php");
$db = new DB();
$table_name="consigncloud_supplier";



$rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
$req = file_get_contents('php://input');


// Product id
$item_id = json_decode($req)->payload->item_id;

$item_response = ConsignCurl('items',$item_id);

$account_id = json_decode($item_response)->account;

$account_response = ConsignCurl('accounts',$account_id);

$item_response = json_decode($item_response);
$account_response = json_decode($account_response);

$account_response->line_items = $item_response;


$email = $account_response->email;

$first_name = $account_response->first_name;

$last_name = $account_response->last_name;

$number = $account_response->number;


$account = $account_response->line_items->account;

$tag_price = $account_response->line_items->tag_price;

$product_title = $account_response->line_items->title;


$conditions['return_type'] = 'single';
$conditions['where'] = array(
    'email' => $account_response->email,
);
$check_supplier_is_exists = $db->getRows($table_name, $conditions); 

echo "<pre>";
print_r($check_supplier_is_exists['email']);
echo "</pre>";

if($email!=''){

  if(!empty($check_supplier_is_exists['email'])){
    // Supplier already exists
  
        // Create supplier list item
        $supplier_list_item_data = array(  
          'supplier_id' => $check_supplier_is_exists['id'],
          'item_lists' => serialize($account_response),
        );
  
    $insert_supplier_list_item = $db->insert('consigncloud_supplier_items', $supplier_list_item_data); 
  }
  else{
          
          // Create New supplier
            $supplier_data = array(  
              'item_id' => $account_response->id,
              'email' => $account_response->email,
              'first_name' => $account_response->first_name,
              'last_name' => $account_response->last_name
              //'param' => serialize($account_response),
            );
          $last_insert_id = $db->insert($table_name, $supplier_data);         
  
            // Create supplier list item
            $supplier_list_item_data = array(  
              'supplier_id' => $last_insert_id,
              'item_lists' => serialize($account_response),
            );
  
        $insert_supplier_list_item = $db->insert('consigncloud_supplier_items', $supplier_list_item_data); 
  
    }
}


//file_put_contents($rootDir."/consigne-cloud-testting/$filename",json_encode($account_response));


/*####################################################
 Save product and account data into the klaviyo by API 
 ######################################################*/

// require_once('vendor/autoload.php');
// $client = new \GuzzleHttp\Client();
// $data_array = array("email"=>$email,"first_name"=>$first_name, "last_name"=>$last_name, "number"=>$number, "account"=>$account, "tag_price"=>$tag_price, "product_title"=>$product_title );
// $response = $client->request('POST', 'https://a.klaviyo.com/api/v2/list/WE9Bpp/subscribe?api_key=pk_7b16755cb90e647cf1a1aaed020e3d0d46', [
//     'body' => '{"profiles":['.json_encode($data_array).']}',
//     'headers' => [
//     'accept' => 'application/json',
//     'content-type' => 'application/json',
//   ],
// ]);

//file_put_contents($rootDir."/consigne-cloud-testting/$filename",json_encode($account_response));

//echo $response->getBody();


//file_put_contents($rootDir."/consigne-cloud-testting/$filename",json_decode($req)->payload->item_id);

function ConsignCurl($endpoints,$id){
   $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.consigncloud.com/api/v1/'.$endpoints.'/'.$id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer MjlhMzY4NzUtNjkxOC00YTc5LWE5NzAtYzVjYTg1NWRlYjczOnBYWm9QYTFxaVcwVGtYZ0s0NVd0VkE='
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;

    //$account_id = json_decode($response)->account;

//    $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
//    $filename = date('d-m-Y').'-'.time().'_account_id.json'; 
//    file_put_contents($rootDir."/consigne-cloud-testting/$filename",$account_id);
}