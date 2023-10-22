<?php

function shopify_call($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array()) {
    
	// Build URL
	// . ".myshopify.com"
	$url = "https://" . $shop . $api_endpoint;
	if (!is_null($query) && in_array($method, array('GET', 	'DELETE'))) $url = $url . "?" . http_build_query($query);

	// Configure cURL
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, TRUE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
	// curl_setopt($curl, CURLOPT_SSLVERSION, 3);
	curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

	// Setup headers
	$request_headers[] = "";
	if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
	curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

	if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
		if (is_array($query)) $query = http_build_query($query);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
	}
    
	// Send request to Shopify and capture any errors
	$response = curl_exec($curl);
	$error_number = curl_errno($curl);
	$error_message = curl_error($curl);

	// Close cURL to be nice
	curl_close($curl);

	// Return an error is cURL has a problem
	if ($error_number) {
		return $error_message;
	} else {

		// No error, return Shopify's response by parsing out the body and the headers
		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

		// Convert headers into an array
		$headers = array();
		$header_data = explode("\n",$response[0]);
		$headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
		array_shift($header_data); // Remove status, we've already set it above
		foreach($header_data as $part) {
			$h = explode(":", $part);
			$headers[trim($h[0])] = trim($h[1]);
		}

		// Return headers and Shopify's response
		return array('headers' => $headers, 'response' => $response[1]);

	}
    
}

// .wp-block-code{border:0;padding:0}.wp-block-code>div{overflow:auto}.shcb-language{border:0;clip:rect(1px,1px,1px,1px);-webkit-clip-path:inset(50%);clip-path:inset(50%);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px;word-wrap:normal;word-break:normal}.hljs{box-sizing:border-box}.hljs.shcb-code-table{display:table;width:100%}.hljs.shcb-code-table>.shcb-loc{color:inherit;display:table-row;width:100%}.hljs.shcb-code-table .shcb-loc>span{display:table-cell}.wp-block-code code.hljs:not(.shcb-wrap-lines){white-space:pre}.wp-block-code code.hljs.shcb-wrap-lines{white-space:pre-wrap}.hljs.shcb-line-numbers{border-spacing:0;counter-reset:line}.hljs.shcb-line-numbers>.shcb-loc{counter-increment:line}.hljs.shcb-line-numbers .shcb-loc>span{padding-left:.75em}.hljs.shcb-line-numbers .shcb-loc:before{border-right:1px solid #ddd;content:counter(line);display:table-cell;padding:0 .75em;text-align:right;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;white-space:nowrap;width:1%}
// <?php
function rest_api($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array()) {
	$url = "https://" . $shop . $api_endpoint;
	if (!is_null($query) && in_array($method, array('GET', 	'DELETE'))) $url = $url . "?" . http_build_query($query);
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, TRUE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	$request_headers[] = "";
	$headers[] = "Content-Type: application/json";
	if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
	curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
	if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
		if (is_array($query)) $query = json_encode($query);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
	}
	$response = curl_exec($curl);
	$error_number = curl_errno($curl);
	$error_message = curl_error($curl);
	curl_close($curl);
	if ($error_number) {
		return $error_message;
	} else {
		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
		$headers = array();
		$header_data = explode("\n",$response[0]);
		$headers['status'] = $header_data[0];
		array_shift($header_data);
		foreach($header_data as $part) {
			$h = explode(":", $part, 2);
			$headers[trim($h[0])] = trim($h[1]);
		}
		return array('headers' => $headers, 'data' => $response[1]);
	}
}

//shopify_call() { ... } <--- make sure you add the code outside this function

function str_btwn($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


function mysql_connection(){

}

