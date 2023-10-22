<?php
  // $curl_handle=curl_init();
  // curl_setopt($curl_handle,CURLOPT_URL,'https://www.wearesott.com/pages/vcc');
  // curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
  // curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
  // $buffer = curl_exec($curl_handle);
  // curl_close($curl_handle);
  // if (empty($buffer)){
  //     print "Nothing returned from url.<p>";
  // }
  // else{
  //     print $buffer;
  // }

$homepage = file_get_contents('https://www.wearesott.com/pages/vcc');
echo $homepage;


?>
