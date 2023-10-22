<style>
.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('./assets/images/loader.gif') 50% 50% no-repeat #020202;
    opacity: .5;
}
</style>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" >
    <!-- <link rel="stylesheet" href="./assets/css/select2.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css">
</head>
<body>
<div class="loader" style="display:none"></div>

<div class="container">
    <?php 
    // include("inc/Shopify_verification.php");
    include_once("inc/functions.php");
    include_once("config.php");

    $page_info = [
        // "eyJsYXN0X2lkIjoxNjM1MDY5NDYxNjEsImxhc3RfdmFsdWUiOiJiYW1mb3JkIn0",
        // "eyJsYXN0X2lkIjo1MzAyMTg5MSwibGFzdF92YWx1ZSI6ImRlc2lnbmVyIGJhZ3MifQ",
        // "eyJsYXN0X2lkIjoxNjM1MDkyNzI2ODksImxhc3RfdmFsdWUiOiJmZW5kaSJ9",
        // "eyJsYXN0X2lkIjoxNjM1MTA5MTEwODksImxhc3RfdmFsdWUiOiJqdW1wZXIgMTIzNCJ9",
        // "eyJsYXN0X2lkIjoxNjM1MTIzNTI4ODEsImxhc3RfdmFsdWUiOiJtYXJzZWxsIn0",
        // "eyJsYXN0X2lkIjoxNjM1MTM3NjE5MDUsImxhc3RfdmFsdWUiOiJyYWcgJiBib25lIn0",
        // "eyJsYXN0X2lkIjoyNjY5MjUzMTAwNjUsImxhc3RfdmFsdWUiOiJzaXplIHVrIDEyIn0",
        // "eyJsYXN0X2lkIjoxNjM1MTYyODUwNDEsImxhc3RfdmFsdWUiOiJ2aWN0b3JpYSBiZWNraGFtIn0",
        'eyJsYXN0X2lkIjoyNzMyMzQ2NTczOTMsImxhc3RfdmFsdWUiOiJBZXllZGUifQ',
        'eyJsYXN0X2lkIjoyNzMxOTYxODc3NjEsImxhc3RfdmFsdWUiOiJBbHR1enVycmEifQ',
        'eyJsYXN0X2lkIjoyNzMxNDI1NDY1NDUsImxhc3RfdmFsdWUiOiJBcm1hIn0',
        'eyJsYXN0X2lkIjoyNzMwMjA1ODQwNDksImxhc3RfdmFsdWUiOiJCYXJydXMifQ',
        'eyJsYXN0X2lkIjoyNzMwMjE1MDE1NTMsImxhc3RfdmFsdWUiOiJCcmlhbiBBdHdvb2QifQ',
        'eyJsYXN0X2lkIjoyNzMxNjk0NDkwNzMsImxhc3RfdmFsdWUiOiJDYXJyaWUgRm9yYmVzIn0',
        'eyJsYXN0X2lkIjoyNzMwMjI2NDg0MzMsImxhc3RfdmFsdWUiOiJDaHJpc3RpYW4gV2lqbmFudHMifQ',
        'eyJsYXN0X2lkIjoyNzMyMTQ5OTY1OTMsImxhc3RfdmFsdWUiOiJDdXRsZXIgJiBHcm9zcyJ9',
        'eyJsYXN0X2lkIjoxNjM0NDY4NDk2NDksImxhc3RfdmFsdWUiOiJEZXNpZ25lciBTdW5nbGFzc2VzIn0',
        'eyJsYXN0X2lkIjoyNzMxMDM1MTk4NTcsImxhc3RfdmFsdWUiOiJFbW1hIFdpbGxpcyJ9',
        'eyJsYXN0X2lkIjoyNzMyMzA4ODkwNzMsImxhc3RfdmFsdWUiOiJGaWxseWJvbyJ9',
        'eyJsYXN0X2lkIjoxNTU4NTcxNTgyNTcsImxhc3RfdmFsdWUiOiJHaWZ0cyJ9',
        'eyJsYXN0X2lkIjoyNzMwMjY0ODIyODksImxhc3RfdmFsdWUiOiJIYXQgQXR0YWNrIn0',
        'eyJsYXN0X2lkIjoxNjM1MTAyNTU3MjksImxhc3RfdmFsdWUiOiJJUk8ifQ',
        'eyJsYXN0X2lkIjoyNzMwMjc5NTY4NDksImxhc3RfdmFsdWUiOiJKb2llIn0',
        'eyJsYXN0X2lkIjoxNjM1MTEyMzg3NjksImxhc3RfdmFsdWUiOiJMYSBQZXJsYSJ9',
        'eyJsYXN0X2lkIjoyNzMxMDM5MTMwNzMsImxhc3RfdmFsdWUiOiJMTkEifQ',
        'eyJsYXN0X2lkIjoxNjM1MTE4Mjg1OTMsImxhc3RfdmFsdWUiOiJNYWRlIFdlbGwifQ',
        'eyJsYXN0X2lkIjoyNzMwMzA3NDIxMjksImxhc3RfdmFsdWUiOiJNYXJpYSBEZSBMYSBPcmRlbiJ9',
        'eyJsYXN0X2lkIjoyNzMwMzE1NjEzMjksImxhc3RfdmFsdWUiOiJNaW1pIExpYmVydGUifQ',
        'eyJsYXN0X2lkIjoyNzMxNjYzMDMzNDUsImxhc3RfdmFsdWUiOiJOYWtlZCBDYXNobWVyZSJ9',
        'eyJsYXN0X2lkIjoyNzMwMzMyOTgwMzMsImxhc3RfdmFsdWUiOiJPZmYtV2hpdGUifQ',
        'eyJsYXN0X2lkIjoyNzMwMzM5NTMzOTMsImxhc3RfdmFsdWUiOiJQZW5lbG9wZSBDaGlsdmVycyJ9',
        'eyJsYXN0X2lkIjoyNzMwMzQ4MDUzNjEsImxhc3RfdmFsdWUiOiJSMTMifQ',
        'eyJsYXN0X2lkIjoyNzMxNTg4NjUwMDksImxhc3RfdmFsdWUiOiJSZXRyb2ZldGUifQ',
        'eyJsYXN0X2lkIjoyNzMwMzYwNTA1NDUsImxhc3RfdmFsdWUiOiJSb3ViaSBMJ1JvdWJpIn0',
        'eyJsYXN0X2lkIjoyNzMwMzY2MDc2MDEsImxhc3RfdmFsdWUiOiJTZWxmIFBvcnRyYWl0In0',
        'eyJsYXN0X2lkIjoyNzMwMzcxNjQ2NTcsImxhc3RfdmFsdWUiOiJTb2xlciJ9',
        'eyJsYXN0X2lkIjoyNzMwMzc5MTgzMjEsImxhc3RfdmFsdWUiOiJTdXBlciBCbG9uZCJ9',
        'eyJsYXN0X2lkIjoyNzMwMzg2MzkyMTcsImxhc3RfdmFsdWUiOiJUaGllcnJ5IENvbHNvbiJ9',
        'eyJsYXN0X2lkIjoyNzMwMzkzOTI4ODEsImxhc3RfdmFsdWUiOiJWZWphIHggTWFuc3VyIEdyYXZyaWVsIn0',
        'eyJsYXN0X2lkIjoyNzMxMzg0MTc3NzcsImxhc3RfdmFsdWUiOiJXYW5kbGVyIn0',
        'eyJsYXN0X2lkIjoyNzMyODk4MDU5MzcsImxhc3RfdmFsdWUiOiJZXC9QUk9KRUNUIn0'  
    ];
    
    $count = count($page_info);
    $data = array();
    $results = [];
    
    for ($i = 0; $i < $count; $i++) {
        $arr = array(
            'page_info' => $page_info[$i]
        );
       
        $smart_collections = shopify_call($access_token, $shop_url,"/admin/api/2022-10/smart_collections.json", $arr , 'GET');
        $data[$page_info[$i]] = json_decode($smart_collections['response'], JSON_PRETTY_PRINT);
        
        $results = array_merge($results, $data[$page_info[$i]]['smart_collections']);
    
    }

    $smart_collections_count = shopify_call($access_token, $shop_url,"/admin/api/2022-10/smart_collections/count.json", array() , 'GET');
    $smart_collections_count = json_decode($smart_collections_count['response'], JSON_PRETTY_PRINT);
    ?>
    <div class="row p-3">
      <div class="col-8 p-0">
        <h3>Add New Badge</h3>
      </div>
      <div class="col-4">
        <a href="dashboard.php?shop=<?php echo $_GET['shop'];?>" class="float-right btn btn-sm btn-primary" id="add_loader_on_click">Badges List</a>
      </div>
    </div>
    <form  action="save.php"  id="save_badges" method="post">
        <div class="form-group">
            <label for="badges" class="form-label">Badges Title</label>
            <input type="text" class="form-control" placeholder="Badges Title" name="badges_title" required>
        </div>
        <div class="form-group">
            <label for="badges_link" class="form-label">Badges Link</label>
            <input type="text" class="form-control" placeholder="Badges Link" name="badges_link" required>
        </div>
        <div class="form-group">
            <label for="Collections" class="form-label">Select Collection</label>
            <select class="form-control" id="productCollection" name="productCollection" required>
                <?php
                $custom_collections = shopify_call($access_token, $shop_url,"/admin/api/2022-10/custom_collections.json", array("limit" => 250), 'GET');
                $custom_collections = json_decode($custom_collections['response'], JSON_PRETTY_PRINT);
                foreach($custom_collections as $custom_collection){
                    foreach($custom_collection as $key => $value){
                        ?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                        <?php
                    }
                }
                $smart_collections = shopify_call($access_token, $shop_url,"/admin/api/2022-10/smart_collections.json", array("limit" => 50), 'GET');
                $smart_collections = json_decode($smart_collections['response'], JSON_PRETTY_PRINT);
                foreach($smart_collections as $smart_collection){
                    foreach($smart_collection as $key => $value){
                        ?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                        <?php
                    }
                }
                for ($j = 0; $j < 1618; $j++) {
                    ?>
                    <option value="<?php echo $results[$j]['id'];?>"><?php echo $results[$j]['title'];?></option>
                    <?php
                }
            ?><select>
        </div>
        <button type="submit" class="btn btn-success" id="save_loader" name="save_badges">Save</button>
    </form>

    <!-- <div id="loader"></div> -->
</div>

<script src="./assets/js/jquery-3.4.1.min.js" ></script>
<script src="./assets/js/popper.min.js"></script>
<script src="./assets/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- <script src=".assets/js/select2.min.js"></script> -->
<script>
    
    $(document).ready(function(){
        $("#productCollection").select2({placeholder: "Select a Collection"} );
        $("#productCollection").select2("val", "4");
    });
</script>
</body>
</html>
<script>
$(document).ready(function() {
$("#add_loader_on_click").click(function() {
  // disable button  
  $(this).prop("disabled", true);
    // add spinner to button 
    $(this).html( `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`); 
    // window.location.reload(true);
  });

});

$("#save_badges").submit(function(){
    $('.loader').show();
//alert("Submitted");
});


</script>


