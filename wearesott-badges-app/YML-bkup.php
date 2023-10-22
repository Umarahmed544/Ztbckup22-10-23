<?php
 header("Access-Control-Allow-Origin: *"); 

$shop = 'timpanys-dress-agency.myshopify.com';
$token = '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';
$new_vendor_name = $_REQUEST['vendor_name'] ;
$new_product_type_name = $_REQUEST['type_name'] ;
$new_colour_name = $_REQUEST['colour_name'] ;
// echo $new_product_type_name;
// echo $new_vendor_name;
// echo $new_colour_name;
// die();
//First Query for show same type + same tag(as color) product data
$query = array("query" => '
	 {
        products(first: 250, query: "status:active product_type:'.$new_product_type_name.'  tag:'.$new_colour_name.' " ) {
          
          edges {
            node {
              id
              title
              vendor
              selectedOptions(first: 10) {
                edges {
                  node {
                    name
                    value
                  }
                }
              }
            }
          }
           
   }
	}
');
// echo "<pre>";
// print_r($query);
// echo "</pre>";
// die();
// Secound Query for show same type + same vendor product data
$query1 = array("query" => '
	 {
        products(first: 250, query: "status:active product_type:Dresses vendor:'.$new_vendor_name.' -tag:'.$new_colour_name.' " ) {
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

// Third Query for show same type  product data
$query2 = array("query" => '
	 {
        products(first: 250, query: "status:active product_type:'.$new_product_type_name.' -tag:'.$new_colour_name.' -vendor:'.$new_vendor_name.'" ) {
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

$mutation =  graphpQL_shopify_call($token, $shop, $query);

echo json_encode(json_decode($mutation)->data->products->edges);

die();
$mutation1 =  graphpQL_shopify_call($token, $shop, $query1);
$mutation2 =  graphpQL_shopify_call($token, $shop, $query2);

// echo "<pre>";
// print_r(json_decode($mutation)->data->products->edges);
// echo "</pre>";

// echo "<pre>";
// print_r(json_decode($mutation1)->data->products->edges);
// echo "</pre>";

// echo "<pre>";
// print_r(json_decode($mutation2)->data->products->edges);
// echo "</pre>";

//$mergarray = array_merge(json_decode($mutation)->data->products->edges, json_decode($mutation1)->data->products->edges, json_decode($mutation2)->data->products->edges);
//$mergarray = json_decode($mutation2)->data->products->edges;
$query4 = array("query" => '
 {
  product(id: "gid://shopify/Product/6851973742705") {
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
$mutation4 =  graphpQL_shopify_call($token, $shop, $query);
//print_r($mutation4);
echo json_encode($mergarray);






