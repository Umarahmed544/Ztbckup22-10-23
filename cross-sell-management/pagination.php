<?php
require_once("inc/functions.php");
include ('inc/DB.class.php');
$db_config = new DB();
$table_shop_details = 'ZT_shop_details';
$shop_url = $_GET['url'];
$limit = $_GET['limit'];
$rel = $_GET['rel'];
$page_info = $_GET['page_info'];



$condition_access_token['where'] = array('shop_url' => $shop_url);    
$row_access_token = $db_config->getRows($table_shop_details,$condition_access_token); 
$access_token = $row_access_token[0]['access_token'];
// $row_pid = json_decode($_GET['row_pid']);
// $assigned_products = $_REQUEST['row_pid'];

//Create an array for the API
$array = array(
  'limit' => $limit,
  'page_info' => $page_info,
  'rel' => $rel
);

$products = rest_api($access_token, $shop_url, "/admin/api/2021-07/products.json", $array, 'GET');


//Get the headers
$headers = $products['headers'];

//Create an array for link header
$link_array = array();

//Check if there's more than one links / page infos. Otherwise, get the one and only link provided
if( strpos( $headers['link'], ',' )  !== false ) {
	$link_array = explode(',', $headers['link'] );
} else {
	$link = $headers['link'];
}

//Create variables for the new page infos
$prev_link = '';
$next_link = '';

//Check if the $link_array variable's size is more than one
if( sizeof( $link_array ) > 1 ) {
  $prev_link = $link_array[0];
  $prev_link = str_btwn($prev_link, '<', '>');

  $param = parse_url($prev_link);
  parse_str($param['query'], $prev_link);
  $prev_link = $prev_link['page_info'];

  $next_link = $link_array[1];
  $next_link = str_btwn($next_link, '<', '>');

  $param = parse_url($next_link);
  parse_str($param['query'], $next_link);

  $next_link = $next_link['page_info'];
} else {
  $rel = explode(";", $headers['link']);
  $rel = str_btwn($rel[1], '"', '"');

  if($rel == "previous") {
    $prev_link = $link;
    $prev_link = str_btwn($prev_link, '<', '>');

    $param = parse_url($prev_link);
    parse_str($param['query'], $prev_link);

    $prev_link = $prev_link['page_info'];

    $next_link = "";
  }else{
    $next_link = $link;
    $next_link = str_btwn($next_link, '<', '>');

    $param = parse_url($next_link);
    parse_str($param['query'], $next_link);

    $next_link = $next_link['page_info'];

    $prev_link = "";
  }
}

//Create and loop through the next or previous products
$html = '';
$products = json_decode($products['data'], true);
$html2 = '';
$i= 1;

foreach($products as $product) {
  foreach($product as $key => $value) {
    if ($value['id'] == $_GET['pid']){ unset($key);
    unset($value);} 
    $spid =$value['id'];
    $title= $value['title'];
    if(empty($value['images'])){
      $image="image/no-img.png";
      $alt = "no-image";	
    }
    else{
      $image = $value['image']['src'];
      $alt = $value['image']['alt'];	
    }
      // foreach ($assigned_products as $assigned_product ) { 
      // foreach ($assigned_products as $key => $value) { 
      //   if($spid==$value){ $checked = "checked";}else{$checked = '';}
      //   // $pta[] =$value;$sfd =$key;

      // }
      // }
      // die('val');
      // '. $checked .'
    if ($spid != null){$html .= '<tr >
      <th scope="row" ><input type="checkbox" class="justone" name="cross-sell_product[]" 
      id="'.$spid.'" value="'.$spid.'"></th>
      <td ><img src="'. $image.'" alt="'. $alt .'" style=" height:60px; width:60px"></td>
      <td >'.$spid.'</td>
      <td >'.$title.'</td>
    </tr>';}
  }
}

foreach($products as $product) {
  foreach($product as $key => $value) {
    $title= $value['title'];
    $id= $value['id'];
    if(empty($value['images'])){
      $image="image/no-img.png";
      $alt = "no-image";	
    }
    else{
      $image = $value['image']['src'];
      $alt = 	$value['image']['alt'];	
    }$html2 .=
    '<tr>
      <th scope="row">'. $i++ .'</th>
      <td><a href="api_call_single_product.php?pid='. $id .'&shop='.$shop_url.'"><img src="'.$image.'" alt="'.$alt.'" 
        style=" height:30px; width:30px"></a></td>
      <td>'.$id.'</td>
      <td><a href="api_call_single_product.php?pid='.$id.'&shop='.$shop_url.'" style="text-decoration: none; color:black">
        '.$title.'</a></td>
      <td><button class="btn btn-sm btn-success" disabled style="cursor:no-drop">Up-sell+</button>
      <a href="api_call_single_product.php?pid='.$id.'&shop='.$shop_url.'" class="btn btn-sm btn-success">Cross-sell+</a></td>
    </tr>';
  }
}

//Then we return the values back to ajax
echo json_encode( array( 'prev' => $prev_link, 'next' => $next_link, 'html' => $html, 'html2' => $html2 ) );
?>
