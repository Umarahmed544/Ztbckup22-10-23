<?php
$shopifyDomain = $params['shop'];
// Webhook details
$webhookUrl = 'https://kavitapatidar.zehntech.net/csm/cross-sell-management/uninstall_hook_response.php'; // Replace with your webhook URL
$webhookTopic = 'app/uninstalled';

// API endpoint URL
$apiUrl = "https://$shopifyDomain/admin/api/2023-01/webhooks.json";

// Request payload
$requestPayload = [
    'webhook' => [
        'topic' => $webhookTopic,
        'address' => $webhookUrl,
        'format' => 'json'
    ]
];

// cURL request
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Shopify-Access-Token: ' . $access_token
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestPayload));
$response = curl_exec($ch);
curl_close($ch);

// Handle the response
if ($response) {
    $responseData = json_decode($response, true);
    if (isset($responseData['webhook'])) {
        $webhookId = $responseData['webhook']['id'];
        echo "Webhook created successfully with ID: $webhookId";
    } else {
        echo "Error creating webhook: " . print_r($responseData['errors']);
    }
} else {
    echo "Failed to create webhook. Please check your request.";
}
