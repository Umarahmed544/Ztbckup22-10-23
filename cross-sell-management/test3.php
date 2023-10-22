<?php
require_once("inc/functions.php");
require __DIR__ . '/vendor/autoload.php';
include 'inc/shop_config.php';
    $shopifyDomain = 'timpanys-dress-agency.myshopify.com';
    $accessToken = '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';
    $access_token = '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';
    $shop_url = 'timpanys-dress-agency.myshopify.com';

$limit = "50";
$array = array(
  'limit' => $limit
);
$products = rest_api($access_token, $shop_url, "/admin/api/2021-07/products.json", $array, 'GET');

$headers = $products['headers'];

foreach ($headers as $key => $value) {
  '<div>[' . $key . '] =>' . $value . '</div>';
}
// echo

$nextPageURL = str_btwn($headers['link'], '<', '>');
$nextPageURLparam = parse_url($nextPageURL);
parse_str($nextPageURLparam['query'], $value);
$page_info = $value['page_info'];

?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/jquery.dataTables.min.css"></style>
</head>
<body>
  <div class="container-fluid">
    <?php
    $params = array(
      'limit' => $limit
    );
    $products = $shopify->Product->get($params);
    ?>
    <h1 class="text-center">Dashboard</h1>
    <input type="text" id="myInput" onkeyup="mySearch()" placeholder="Search for products">
    <table id="myTable" class="table text-center mt-2">
      <!-- table-striped -->
      <thead>
        <tr>
          <th scope="col">S.No.</th>
          <th scope="col">Product Image</th>
          <th scope="col">Product ID</th>
          <th scope="col">Product Title</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody id="td1">
        <?php
        $i = 1;
        foreach ($products as $key => $value) {

          $title = $value['title'];
          $id = $value['id'];
          if (empty($value['images'])) {
            $image = "image/no-img.png";
            $alt = "no-image";
          } else {
            $image = $value['image']['src'];
            $alt = $value['image']['alt'];
          }
          ?>
          <tr>
            <th scope="row"><?php echo $i++; ?></th>
            <td><a href="api_call_single_product.php?pid=<?php echo $id; ?>&shop=<?php echo $_GET['shop']; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $alt; ?>" style=" height:30px; width:30px"></a></td>
            <td><?php echo $id; ?></td>
            <td><a href="api_call_single_product.php?pid=<?php echo $id; ?>&shop=<?php echo $_GET['shop']; ?>" style="text-decoration: none; color:black">
                <?php echo $title; ?></a></td>
            <td><button class="btn btn-sm btn-success" disabled style="cursor:no-drop">Up-sell+</button>
              <a href="api_call_single_product.php?pid=<?php echo $id; ?>&shop=<?php echo $_GET['shop']; ?>" class="btn btn-sm btn-success">Cross-sell+</a></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <div class="row">
      <div class="col text-center">
        <button type="button" data-info="" data-rel="previous" data-store="<?php echo $shop_url; ?>" class="page btn btn-secondary py-1"><< Prev</button>
        <button type="button" data-info="<?php echo $page_info; ?>" data-rel="next" data-store="<?php echo $shop_url; ?>" class="page btn btn-secondary py-1">Next >></button>
      </div>
    </div>
  </div>
  <script src="./assets/js/jquery-3.4.1.min.js"></script>
  <script src="./assets/js/popper.min.js"></script>
  <script src="./assets/js/bootstrap.min.js"></script>
  <script src="./assets/js/jquery.dataTables.min.js"></script>
  <script>
    $('.page').on('click', function(e) {
      var data_info = $(this).attr('data-info');
      var data_rel = $(this).attr('data-rel');
      var data_store = $(this).attr('data-store');
      var data_limit = <?php echo $limit ?>;

      if (data_info != '') {
        $.ajax({
          type: "GET",
          url: "pagination.php",
          data: {
            limit: data_limit,
            page_info: data_info,
            rel: data_rel,
            url: data_store
          },
          dataType: "json",
          success: function(response) {
            console.log(response);

            if (response['prev'] != '') {
              $('button[data-rel="previous"]').attr('data-info', response['prev']);
            } else {
              $('button[data-rel="previous"]').attr('data-info', "");
            }

            if (response['next'] != '') {
              $('button[data-rel="next"]').attr('data-info', response['next']);
            } else {
              $('button[data-rel="next"]').attr('data-info', "");
            }

            if (response['html2'] != '') {
              $('#td1').html(response['html2']);
            }
          }
        });
      }
    });

    function mySearch() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");

      // GraphQL query
      var query = `
        query ($filter: String!) {
          products(query: $filter, first: 50) {
            edges {
              node {
                id
                title
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
      `;

      // Variables for the GraphQL query
      var variables = {
        filter: filter
      };

      // Make the GraphQL API call
      fetch('https://timpanys-dress-agency.myshopify.com/api/2023-04/graphql.json', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Shopify-Storefront-Access-Token': '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'
        },
        body: JSON.stringify({
          query: query,
          variables: variables
        })
      })
        .then(response => response.json())
        .then(data => {
          var products = data.data.products.edges;

          for (i = 0; i < tr.length; i++) {
            tr[i].style.display = "none";
          }

          for (i = 0; i < products.length; i++) {
            var product = products[i].node;
            var id = product.id;
            var title = product.title;
            var image = product.images.edges[0].node.originalSrc;
            var alt = product.images.edges[0].node.altText;

            var productRow = document.getElementById("td1").querySelector('tr[data-id="' + id + '"]');
            if (productRow) {
              productRow.style.display = "";
            } else {
              var newRow = table.insertRow(-1);
              newRow.setAttribute("data-id", id);

              var snCell = newRow.insertCell(0);
              snCell.appendChild(document.createTextNode(i + 1));

              var imageCell = newRow.insertCell(1);
              var imageLink = document.createElement("a");
              imageLink.href = "api_call_single_product.php?pid=" + id + "&shop=<?php echo $_GET['shop']; ?>";
              var productImage = document.createElement("img");
              productImage.src = image;
              productImage.alt = alt;
              productImage.style.height = "30px";
              productImage.style.width = "30px";
              imageLink.appendChild(productImage);
              imageCell.appendChild(imageLink);

              var idCell = newRow.insertCell(2);
              idCell.appendChild(document.createTextNode(id));

              var titleCell = newRow.insertCell(3);
              var titleLink = document.createElement("a");
              titleLink.href = "api_call_single_product.php?pid=" + id + "&shop=<?php echo $_GET['shop']; ?>";
              titleLink.style.textDecoration = "none";
              titleLink.style.color = "black";
              titleLink.appendChild(document.createTextNode(title));
              titleCell.appendChild(titleLink);

              var actionsCell = newRow.insertCell(4);
              var upSellButton = document.createElement("button");
              upSellButton.className = "btn btn-sm btn-success";
              upSellButton.disabled = true;
              upSellButton.style.cursor = "no-drop";
              upSellButton.appendChild(document.createTextNode("Up-sell+"));
              actionsCell.appendChild(upSellButton);

              var crossSellLink = document.createElement("a");
              crossSellLink.href = "api_call_single_product.php?pid=" + id + "&shop=<?php echo $_GET['shop']; ?>";
              crossSellLink.className = "btn btn-sm btn-success";
              crossSellLink.appendChild(document.createTextNode("Cross-sell+"));
              actionsCell.appendChild(crossSellLink);
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  </script>
</body>
</html>
