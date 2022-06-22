<?php

$data = file_get_contents('vapid.json');

$vapid = json_decode($data);

//die($vapid->publicKey);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // solicita la llave publica
        $key = base64_encode($vapid->publicKey);
        echo $key;
        break;
    case 'PUT':
        // update the key and token of subscription corresponding to the endpoint
        break;
    case 'DELETE':
        // delete the subscription corresponding to the endpoint
        break;
    default:
        echo "Error: method not handled";
        return;
}