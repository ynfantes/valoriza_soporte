<?php
include('includes/configuracion.php');
include('includes/db.php');
$subscription = json_decode(file_get_contents('php://input'), true);

if (!isset($subscription['endpoint'])) {
    echo 'Error: not a subscription';
    return;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // create a new subscription entry in your database (endpoint is unique)
        $db = new db();
        $data = Array();
        $data['endpoint'] = $subscription['endpoint'];
        $data['suscripcion'] = json_encode($subscription);
        $r = $db->insert('suscripciones',$data);
        
        if ($r['suceed']) {
            echo 'Suscripción guardada con éxito';
        } else if ($r['stats']['errno']==1062) {
            echo 'Suscripcion ya existe en el BD';
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
