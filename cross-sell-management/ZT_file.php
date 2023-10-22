<?php
require_once("inc/functions.php");

$ZT_asset=
'<style>
    a {
        text-decoration:none;
        color:black;
    }
    .slick-next {
        right: 0 !important;
    }
    .slick-next:before {
        color: rebeccapurple !important;
    }
    .slick-prev {
        left: 0 !important;
    }
    .slick-prev:before {
        color: rebeccapurple !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css"/>
<div class="crossell-div"><center>
    <h2>ZT_Cross-Sell Products</h2>
    <div class="items"></div>
</center></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>  
<script> 
    $.ajax({
        url: "https://kavitapatidar.zehntech.net/csm/cross-sell-management/ZT_cross-sell.php",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        type: "GET", 
        data: {
            id:{{ product.id | escape }},
            shop:"{{ shop.domain | escape}}"
        },
        success: function (result) {
            // console.log(result);
            console.log(JSON.parse(result)["cross_sell_products"]);
            console.log(JSON.parse(result)["status_cross_sell"]);
            console.log(JSON.parse(result)["status_up_sell"]);
            const assign_products = JSON.parse(result)["cross_sell_products"];
            const status_cross_sell = JSON.parse(result)["status_cross_sell"];
            const status_up_sell = JSON.parse(result)["status_up_sell"];

            if (assign_products.length == 0){
                $(".items").append('."'".'<p>"No products are Assigned in Cross-sell"</p>'."'".');
            }
            for (i = 0; i < assign_products.length; ++i) {
        
                const assign_product_title = assign_products[i]["title"];
                const assign_product_handle = assign_products[i]["handle"];
                const assign_product_price = assign_products[i]["variants"][0]["price"];
                const assign_product_src = assign_products[i]["image"]["src"];
        
                if(status_cross_sell == 1){
                    $(".items").append(
                        '."'".'<a href="{{ shop.url | escape}}/products/'."'".'
                        +assign_product_handle
                        +'."'".'"><div style="margin:5px;"><img style="height:200px; width:200px;" src="'."'".'
                        + assign_product_src
                        + '."'".'"><p><small>'."'".'
                        + assign_product_title
                        +'."'".'</small><br>Rs.'."'".'
                        +assign_product_price
                        +'."'".'</p></div></a>'."'".'
                    );
                }else{
                    $(".crossell-div").css("display","none");
                }
            }	
        },
        error: function (error) {
            console.log("error", error);
        },
        complete: function () {
            $(".items").slick({
                dots: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 5000,
                responsive: [
                  {
                    breakpoint: 1024,
                    settings: {
                      slidesToShow: 3
                    }
                  },
                  {
                    breakpoint: 600,
                    settings: {
                      slidesToShow: 2
                    }
                  },
                  {
                    breakpoint: 480,
                    settings: {
                      slidesToShow: 1
                    }
                  }
                ]
            });
        }
    }); 
</script>';

$themes = shopify_call($access_token,  $params['shop'] , "/admin/api/2023-01/themes.json", array(), 'GET');
$themes = json_decode($themes['response'], TRUE);

foreach($themes as $theme){
    foreach($theme as $key => $value){
        if ($value['role'] === 'main'){
            $theme_id = $value['id'];
            $theme_role = $value['role'];

            $asset_file = array(
                "asset" => array(
                    "key" => "snippets/ZT_umar.liquid",
                    "value" => $ZT_asset
                        
                )
            );

            $assets = shopify_call($access_token,  $params['shop'] , "/admin/api/2023-01/themes/". $theme_id."/assets.json", $asset_file, 'PUT');
            $assets = json_decode($assets['response'], TRUE);
            echo print_r($assets);
        }
    }
}

?>