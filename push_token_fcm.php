<?php
include('includes/configuracion.php');
include('includes/db.php');
$subscription = json_decode(file_get_contents('php://input'), true);


if (!isset($subscription['token'])) {
    echo 'Error: no token';
    return;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // create a new subscription entry in your database (endpoint is unique)
        $db = new db();
        $data = Array();
        $data['token'] = $subscription['token'];
        $data['fecha'] = date("Y-m-d h:i:00 ", time());

        $r = $db->insert('fcm_token',$data);
        
        if ($r['suceed']) {
            echo 'Token guardada con éxito';
        } else if ($r['stats']['errno']==1062) {
            echo 'Token ya existe en el BD';
        } else {
            echo $r['stats']['error'];
        }
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
