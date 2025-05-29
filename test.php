<?php
$postArray = [
    'method' => 'game_list',
    'agent_code' => 'cassie.bet',
    'agent_token' => '470db548fbd4542572c1e60504fc5e31',
    'provider_code' => 'evolution' // Additional parameter
];

$jsonData = json_encode($postArray);

$headerArray = ['Content-Type: application/json'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.fiverscool.com');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

$res = curl_exec($ch);
curl_close($ch);

// Handle response
if ($res === false) {
    // Request failed
    echo 'Error: ' . curl_error($ch);
} else {
    // Request successful
    echo 'Response: ' . $res;
}

?>
