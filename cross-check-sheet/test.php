<h1 class="text-center">test 6</h1>
<?php
ini_set('max_execution_time', 300); // Set to 5 minutes (adjust as needed)

$i = 1;
$shopifyDomain = 'timpanys-dress-agency.myshopify.com';
$accessToken = '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';
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
                    images(first: 1) {
                        edges {
                            node {
                                originalSrc
                                altText
                            }
                        }
                    }

                }
            }
        }
    }
GRAPHQL;

$cursor = null;
$retryCount = 0;
$maxRetries = 3;

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

    if ($response) {
        $data = json_decode($response, true);
        // var_dump($response);
        // var_dump($data);

        if (isset($data['data']['products']['edges'])) {
            $products = $data['data']['products']['edges'];

            foreach ($products as $product) {
                $productId = $product['node']['id'];
                $productId = preg_replace('/^gid:\/\/shopify\/Product\//', '', $productId);
                $title = $product['node']['title'];
                $handle = $product['node']['handle'];
                $description = $product['node']['description'];
                // $status = $product['node']['status'];
                // $status = $product['node']['status'];

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

                echo 'productId-' . $productId.'<br>';
                echo 'handle-' . $handle.'<br>';
                echo 'description-' . $description.'<br>';
                echo 'priceRangeMin-' . $priceRangeMin_currencyCode.'-'.$priceRangeMin_amount.'<br>';
                echo 'priceRangeMax-' . $priceRangeMax_currencyCode.'-'.$priceRangeMax_amount.'<br>';
                echo 'Product name -' . $title.'<br>';
                echo 'imageSrc-' . $imageSrc.'<br>';
                echo 'imageAlt-' . $imageAlt.'<hr>';
            }

            $cursor = $data['data']['products']['pageInfo']['endCursor'];
        }
    } else {
        echo "Error occurred while making the API request.";
        break;
    }
} while ($cursor !== null);
?>
