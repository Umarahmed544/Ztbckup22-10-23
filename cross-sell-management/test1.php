<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./assets/css/bootstrap.min.css" >
        <link rel="stylesheet" href="./assets/css/jquery.dataTables.min.css"></style>
    </head>
    <body>
        <div class="container-fluid">
            <h1 class="text-center">test 1</h1>
            <table id="myTable" class="table text-center mt-2" > <!-- table-striped -->
                <thead>
                    <tr>
                    <th scope="col">S.No.</th>
                    <th scope="col">Product Image</th>
                    <th scope="col">Product ID</th>
                    <th scope="col">Product Title</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="td1"><?php
                    $i= 1;
                    $shopifyDomain = 'quick-start-8ab6beba.myshopify.com';
                    $accessToken = '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';
                    // $shopifyDomain = 'abhishek-zehntech.myshopify.com';
                    // $accessToken = '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';
                    // $shopifyDomain = 'timpanys-dress-agency.myshopify.com';
                    // $accessToken = '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';

                    $graphqlEndpoint = "https://{$shopifyDomain}/admin/api/2021-07/graphql.json";
                    $apiHeaders = [
                        'Content-Type: application/json',
                        'X-Shopify-Access-Token: ' . $accessToken,
                    ];

                    // Prepare the GraphQL query
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
                            var_dump($response);
                            var_dump($data);

                            if (isset($data['data']['products']['edges'])) {
                                $products = $data['data']['products']['edges'];

                                // Process the products
                                foreach ($products as $product) {
                                    $productId = $product['node']['id'];
                                    $productId = preg_replace('/^gid:\/\/shopify\/Product\//', '', $productId);
                                    $title = $product['node']['title'];
                                    // $image = $product['node']['images']['edges'][0]['node']['originalSrc'];
                                    $status = $product['node']['status'];
                                    $imageEdges = $product['node']['images']['edges'];
                                    $imageNode = $imageEdges[0]['node'];
                                    $imageSrc = $imageNode['originalSrc'];
                                    $imageAlt = $imageNode['altText'];


                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $i++; ?></th>
                                        <td><a href="api_call_single_product.php?pid=<?php echo $productId;?>&shop=<?php echo $_GET['shop'];?>">
                                            <img src="<?php echo $imageSrc; ?>" alt="<?php echo $imageAlt; ?>" 
                                                style=" height:30px; width:30px">
                                        </a></td>
                                        <td><?php echo $productId; ?></td>
                                        <td><a href="api_call_single_product.php?pid=<?php echo $productId;?>&shop=<?php echo $_GET['shop'];?>" style="text-decoration: none; color:black">
                                        <?php echo $title; ?></a></td>
                                        <td><button class="btn btn-sm btn-success" disabled style="cursor:no-drop">Up-sell+</button>
                                        <a href="api_call_single_product.php?pid=<?php echo $productId;?>&shop=<?php echo $_GET['shop'];?>" class="btn btn-sm btn-success">Cross-sell+</a></td>
                                    </tr>
                                    <?php
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
                </tbody>
            </table>
        </div>
        <script src="./assets/js/jquery-3.4.1.min.js" ></script>
        <script src="./assets/js/popper.min.js"></script>
        <script src="./assets/js/bootstrap.min.js" ></script>
        <script src="./assets/js/jquery.dataTables.min.js"></script> 
        <script>
            $(document).ready(function(){
                $('#myTable').dataTable();
            });
        </script>
    </body>
</html> 