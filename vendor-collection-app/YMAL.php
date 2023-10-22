<?php
 header("Access-Control-Allow-Origin: *"); 

$shop = 'https://timpanys-dress-agency.myshopify.com';
$token = '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';
$new_vendor_name = $_REQUEST['vendor_name'] ;
$new_product_type_name = $_REQUEST['type_name'] ;
$new_colour_name = $_REQUEST['colour_name'] ;
$current_product_data = $_REQUEST['current_product'] ;

// print_r(json_encode($current_product_data));
// die();

function graphpQL_shopify_call($token, $shop, $query  = array()){

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://timpanys-dress-agency.myshopify.com/admin/api/2023-01/graphql.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($query),
        CURLOPT_HTTPHEADER => array(
          'X-Shopify-Access-Token: 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',
          'Content-Type: application/json'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
      return $response;

  }


//First Query for show same type + same vendor + same tag(as color) product data

$query_type_tag_vendor = array("query" => '
	 {
        products(first: 10, query: "status:active product_type:'.$new_product_type_name.'  tag:'.$new_colour_name.' vendor:'.$new_vendor_name.'  -tag:hide -tag:Hide" ) {
          
            edges {
              node {              
                id
                featuredImage {
                  id
                  url
                }
                handle
                vendor
                title
                priceRange {
                  minVariantPrice {
                    amount
                  }
                }
               
              }
              
            }
           
   }
	}
'); 

//secound Query for show same type + same tag(as color) product data

$query_type_tag = array("query" => '
	 {
        products(first: 10, query: "status:active product_type:'.$new_product_type_name.'  tag:'.$new_colour_name.' -tag:hide -tag:Hide" ) {
          
            edges {
              node {              
                id
                featuredImage {
                  id
                  url
                }
                handle
                vendor
                title
                priceRange {
                  minVariantPrice {
                    amount
                  }
                }
               
              }
              
            }
           
   }
	}
');


// Third Query for show same type + same vendor product data
$query_vendor_type = array("query" => '
	 {
        products(first: 10, query: "status:active product_type:'.$new_product_type_name.' vendor:'.$new_vendor_name.' -tag:'.$new_colour_name.' -tag:hide -tag:Hide" ) {
            edges {
              node {
                id
                featuredImage {
                  id
                  url
                }
                handle
                vendor
                title
                priceRange {
                  minVariantPrice {
                    amount
                  }
                }
              }
            }
          }
	}
');

// fourth Query for show same type  product data
$query_type = array("query" => '
	 {
        products(first: 10, query: "status:active product_type:'.$new_product_type_name.' -tag:'.$new_colour_name.' -vendor:'.$new_vendor_name.' -tag:hide -tag:Hide" ) {
            edges {
              node {
                id
                featuredImage {
                  id
                  url
                }
                handle
                vendor
                title
                priceRange {
                  minVariantPrice {
                    amount
                  }
                }
              }
              
            }
          }
	}
');

// for the 1st query
$result_arr = [];
$mutation =  graphpQL_shopify_call($token, $shop, $query_type_tag_vendor);
$result = json_decode($mutation)->data->products->edges;
foreach($result as $key=>$res){
 $result_arr[] =  $res->node;
$product_varition_query = array("query" => '
 {
  product(id: "'.$res->node->id.'") {
    title
    variants(first: 10) {
      edges {
        node {
          title
          selectedOptions {
            name
            value
          }
        }
      }
    }
  }
}
');

$vration_result_array =  graphpQL_shopify_call($token, $shop,$product_varition_query);
$varition_array = json_decode($vration_result_array)->data->product->variants->edges[0]->node->selectedOptions;
$result_arr[$key]->variants = (object) $varition_array;
}


// for the 2st query
$result_arr1 = [];
$mutation1 =  graphpQL_shopify_call($token, $shop, $query_type_tag);
$result1 = json_decode($mutation1)->data->products->edges;
foreach($result1 as $key=>$res1){
 $result_arr1[] =  $res1->node;
$product_varition_query1 = array("query" => '
 {
  product(id: "'.$res1->node->id.'") {
    title
    variants(first: 10) {
      edges {
        node {
          title
          selectedOptions {
            name
            value
          }
        }
      }
    }
  }
}
');

$vration_result_array1 =  graphpQL_shopify_call($token, $shop,$product_varition_query1);
$varition_array1 = json_decode($vration_result_array1)->data->product->variants->edges[0]->node->selectedOptions;
$result_arr1[$key]->variants = (object) $varition_array1;
}




// for the 3rd query

$result_arr2 = [];
$mutation2 =  graphpQL_shopify_call($token, $shop, $query_vendor_type);
$result2 = json_decode($mutation2)->data->products->edges;
foreach($result2 as $key=>$res2){
 $result_arr2[] =  $res2->node;
$product_varition_query2 = array("query" => '
 {
  product(id: "'.$res2->node->id.'") {
    title
    variants(first: 10) {
      edges {
        node {
          title
          selectedOptions {
            name
            value
          }
        }
      }
    }
  }
}
');

$vration_result_array2 =  graphpQL_shopify_call($token, $shop,$product_varition_query2);
$varition_array2 = json_decode($vration_result_array2)->data->product->variants->edges[0]->node->selectedOptions;
$result_arr2[$key]->variants = (object) $varition_array2;
}



// for the 4th query

$result_arr3 = [];
$mutation3 =  graphpQL_shopify_call($token, $shop, $query_type);
$result3 = json_decode($mutation3)->data->products->edges;
foreach($result3 as $key=>$res3){
 $result_arr3[] =  $res3->node;
$product_varition_query3 = array("query" => '
 {
  product(id: "'.$res3->node->id.'") {
    title
    variants(first: 10) {
      edges {
        node {
          title
          selectedOptions {
            name
            value
          }
        }
      }
    }
  }
}
');

$vration_result_array3 =  graphpQL_shopify_call($token, $shop,$product_varition_query3);
$varition_array3 = json_decode($vration_result_array3)->data->product->variants->edges[0]->node->selectedOptions;
$result_arr3[$key]->variants = (object) $varition_array3;
}


// echo "<pre>";
// print_r($result_arr1);

// print_r($result_arr2);

// print_r($result_arr3);
// echo "</pre>";

$mergarray = array_merge($result_arr,$result_arr1, $result_arr2, $result_arr3);
echo json_encode($mergarray);

