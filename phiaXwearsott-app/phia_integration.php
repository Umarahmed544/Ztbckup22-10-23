<h1 class="text-center">Phia Data Integration</h1>
<?php
$i = 1;

$shopifyDomain = $_GET['shop'];
$db = new DB();
$table = 'wearesott_custom_apps';
$conditions['return_type'] = 'single';
$conditions['where'] = array('shop_url' => $shopifyDomain, 'status' => 1);
$get_single_result = $db->getRows($table, $conditions);
$accessToken = $get_single_result['access_token'];

// $shopifyDomain = 'timpanys-dress-agency.myshopify.com';
// $accessToken = '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';
$graphqlEndpoint = "https://{$shopifyDomain}/admin/api/2021-07/graphql.json";
$apiHeaders = [
    'Content-Type: application/json',
    'X-Shopify-Access-Token: ' . $accessToken,
];

$query = <<<'GRAPHQL'
    query ($cursor: String) {
  products(first: 50, after: $cursor, query: "status:active") {
    pageInfo {
      hasNextPage
      endCursor
    }
    edges {
      node {
        id
        title
        status
        totalInventory
        vendor
        productType
        variants(first: 1) {
          edges {
            node {
              sku
            }
          }
        }
        images(first: 1) {
          edges {
            node {
              originalSrc
              altText
            }
          }
        }
        priceRange {
          minVariantPrice {
            amount
            currencyCode
          }
          maxVariantPrice {
            amount
            currencyCode
          }
        }
        handle
        description
        options {
          name
          values
        }
      }
    }
  }
}
GRAPHQL;

$cursor = null;
$retryCount = 0;
$maxRetries = 3;
$allProductData = [];
do {
    $variables = [
        'cursor' => $cursor,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $graphqlEndpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $apiHeaders);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['query' => $query, 'variables' => $variables]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 429) {
        $retryAfter = 0;
        $responseData = json_decode($response, true);
        if (isset($responseData['extensions']['cost']['throttleStatus']['restoreRate'])) {
            $restoreRate = $responseData['extensions']['cost']['throttleStatus']['restoreRate'];
            $retryAfter = ceil(1 / $restoreRate);
        }
        if ($retryCount >= $maxRetries) {
            echo "Exceeded maximum number of retries.";
            break;
        }
        sleep($retryAfter);
        $retryCount++;
        continue;
    }

    //  echo $response;
    if ($response) {
        $data = json_decode($response, true);
        
        if (isset($data['data']['products']['edges'])) {

            $products = $data['data']['products']['edges'];
            $productData = [];

            foreach ($products as $product) {
              
                $productId = $product['node']['id'];

                $sku_edges = $product['node']['variants']['edges'];
                $sku_node = $sku_edges[0]['node'];
                $sku = $sku_node['sku'];

                $productId = preg_replace('/^gid:\/\/shopify\/Product\//', '', $productId);
                $title = $product['node']['title'];
                $handle = $product['node']['handle'];
                $description = $product['node']['description'];
                $vendor = $product['node']['vendor'];
                $type = $product['node']['productType'];


                $options = $product['node']['options'];
                // Initialize arrays to store option names and values
                  $optionNames = [];
                  $optionValues = [];

                  if (!empty($options)) {
                    foreach ($options as $option) {
                        $optionNames[] = trim($option['name']); // Apply trim() to option name
                        $optionValues[] = array_map('trim', $option['values']); // Apply trim() to all option values
                    }
                } 

                $sizeIndex = array_search('Size', $optionNames);

                  if ($sizeIndex !== false) {
                      $size = implode(', ', $optionValues[$sizeIndex]); // Apply implode() to the trimmed values
                  } else {
                      $size = 'No data';
                  }

                  $conditionIndex = array_search('Condition', $optionNames);

                  if ($conditionIndex !== false) {
                      $option_condition = implode(', ', $optionValues[$conditionIndex]); // Apply implode() to the trimmed values
                 
                  } else {
                      $option_condition = "Condition option not available.";
                  }
                
                  if ($option_condition == 'A*' || $option_condition == 'A' || $option_condition == 'AB' || $option_condition == 'B' || $option_condition == 'BC' || $option_condition == 'C' ){
                      if ($option_condition == 'A*'){
                        $option_condition = 'Brand New';
                      }
                      if ($option_condition == 'A'){
                        $option_condition = 'Excellent';
                      }
                      if ($option_condition == 'AB'){
                      $option_condition = 'Very good';
                      }
                      if ($option_condition == 'B'){
                      $option_condition = 'Good';
                      }
                      if ($option_condition == 'BC'){
                        $option_condition = 'Fair';
                      }
                      if ($option_condition == 'C'){
                        $option_condition = 'Used';
                      }
                  }
                 
               
                $priceRangeMin = $product['node']['priceRange']['minVariantPrice'];
                $priceRangeMin_amount = $priceRangeMin['amount'];
                $priceRangeMin_currencyCode = $priceRangeMin['currencyCode'];
                
                $priceRangeMax = $product['node']['priceRange']['maxVariantPrice'];
                $priceRangeMax_amount = $priceRangeMax['amount'];
                $priceRangeMax_currencyCode = $priceRangeMax['currencyCode'];

                $imageEdges = $product['node']['images']['edges'];
                $imageNode = $imageEdges[0]['node'];
                $imageSrc = $imageNode['originalSrc'];
                $imageAlt = $imageNode['altText'];
                
                echo 'Product name -' . $title.'<br>';
                echo 'productId-' . $productId.'<br>';
                echo 'SKU-' .$sku.'<br>';
                echo 'https://' . $shopifyDomain . '/products/' . $handle . '/?utm_source=affiliate&utm_medium=phia&utm_id=partnership <br>';

                echo 'description-' . $description.'<br>';
                echo 'priceRangeMin-' . $priceRangeMin_currencyCode.'-'.$priceRangeMin_amount.'<br>';
                echo 'priceRangeMax-' . $priceRangeMax_currencyCode.'-'.$priceRangeMax_amount.'<br>';
                echo 'imageSrc-' . $imageSrc.'<br>';
                echo 'imageAlt-' . $imageAlt.'<br>';
                echo 'Brand-' . $vendor.'<br>';
                echo 'Category-' . $type.'<br>';
                echo 'Size-' .$size.'<br>';
                
                echo 'Product Condition-' .$option_condition.'<hr>';


                  $allProductData[] = [
                    'productId' => $productId,
                    'SKU' => $sku,
                    'Brand' => $vendor,
                    'URL with affiliate tracking'=> 'https://' . $shopifyDomain . '/products/' . $handle . '/?utm_source=affiliate&utm_medium=phia&utm_id=partnership' , 
                    'Category '=> $type, 
                    'Condition'=> $option_condition, 
                    'Size' => $size,
                    'description' => $description,
                    'price' => $priceRangeMin_currencyCode . '-' . $priceRangeMin_amount,
                    // 'priceRangeMax' => $priceRangeMax_currencyCode . '-' . $priceRangeMax_amount,
                    'title' => $title,
                    'imageSrc' => $imageSrc,
                    // 'imageAlt' => $imageAlt,
                ];

            }
 
            $cursor = $data['data']['products']['pageInfo']['endCursor'];
        }
    } else {
        echo "Error occurred while making the API request.";
        break;
    }
} while ($cursor !== null);
file_put_contents(__DIR__."/products_integration_data.json", json_encode($allProductData));
?>

