<h1 class="text-center">test 4</h1>
        <?php
        require_once("inc/functions.php");
        $i= 1;
        // $shopifyDomain = 'timpanys-dress-agency.myshopify.com';
        // $accessToken = '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';
        // $shopifyDomain = 'abhishek-zehntech.myshopify.com';
        // $accessToken = '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';
        $shopifyDomain = 'quick-start-8ab6beba.myshopify.com';
        $accessToken = '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';        

        // $products = shopify_call($accessToken, $shopifyDomain, "/admin/api/2021-07/products/4381815865457.json", array(), 'GET');
        // $products = json_decode($products['response'], TRUE);
        //     foreach($products as $key => $value){
        //         // echo 'key -'.$key.'<br>';
        //         // echo 'value -'.$value.'<br>';
        //         $image_url = $value['image']['src'];
        //         // $title = $value['title'];
        //         echo($image_url);
        //     }



        // die('imgurl');
        $graphqlEndpoint = "https://{$shopifyDomain}/admin/api/2021-07/graphql.json";
        $apiHeaders = [
            'Content-Type: application/json',
            'X-Shopify-Access-Token: ' . $accessToken,
        ];

        // Prepare the GraphQL query
        $query = <<<'GRAPHQL'
            query ($cursor: String) {
                products(first: 250, after: $cursor, query: "status:ACTIVE") {
                    pageInfo {
                        hasNextPage
                        endCursor
                    }
                    edges {
                        node {
                            id
                            title
                            status
                        }
                    }
                }
            }
        GRAPHQL;

        // Set the initial cursor to null
        $cursor = null;

        // Make the API request until all products are fetched
        do {
            // Set the variables for the GraphQL query
            $variables = [
                'cursor' => $cursor,
            ];

            // Make the API request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $graphqlEndpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $apiHeaders);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['query' => $query, 'variables' => $variables]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            // Handle the response
            if ($response) {
                $data = json_decode($response, true);
                // var_dump($response);
                // var_dump($data);

                if (isset($data['data']['products']['edges'])) {
                    $products = $data['data']['products']['edges'];

                    // Process the products
                    foreach ($products as $product) {
                        $productId = $product['node']['id'];
                        $productId = preg_replace('/^gid:\/\/shopify\/Product\//', '', $productId);
                        $title = $product['node']['title'];
                        $status = $product['node']['status'];

                        echo $i++.'<br>'; echo 'status-'.$status.'<br>'; echo 'productId-'.$productId.'<br>'; echo 'title-'.$title.'<br>';

                        $products = shopify_call($accessToken, $shopifyDomain, "/admin/api/2021-07/products/".$productId.".json", array(), 'GET');
                        $products = json_decode($products['response'], TRUE);
                            foreach($products as $key => $value){
                                $image_url = $value['image']['src'];
                                echo 'image_url-'.$image_url.'<hr>';
                            }
                    }

                    // Get the end cursor for pagination
                    $cursor = $data['data']['products']['pageInfo']['endCursor'];
                }
            } else {
                // Handle API request error
                echo "Error occurred while making the API request.";
                break;
            }
        } while ($cursor !== null);?>
