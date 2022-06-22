<?php
include('includes/configuracion.php');
include('includes/db.php');
$db = new db();



$listado = $db->select('*','fcm_token');


if ($listado['suceed'] && count($listado['data'])>0) {
    
    foreach ($listado['data'] as $registro) {

        if (!$registro['eliminar']) {

            $resultado = sendNotification($registro['token']);

            if ($resultado['fallo']==1) {
                
                if ($resultado['error']==='NotRegistered') {
                 
                    $db->update('fcm_token',Array("eliminar"=>1),Array("id"=>$registro['id']));
                
                }

            }
            
        }
        
    }    
 
} else {
    echo 'No existen suscripciones registradas';
}

function sendNotification($token) {
    $url =  'https://fcm.googleapis.com/fcm/send';

    $fields = Array(
            'to'            => $token,
            'notification'  => Array(
            'body'          => $_POST['message'],
            'title'         => $_POST['title'],
            'click_action'  => $_POST['url']
        )
    );

    $headers = Array(
        'Authorization: key=AAAAwxwszJs:APA91bEq-XiPTL8mC-kOerbcrX_IkPrKlzMLTzzIufDpxPGQtB6XZrhqLppFV3tci2uPXKhxD2bnWREbagMPnBcAqpzsXXz9TrAeNUeJyIelJgOtAAddl4yNtJXGNKiZN_VJZCFzqZ6E	',
        'Content-Type:application/json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    $result = json_decode($result);
    print_r($result);
    curl_close($ch);
    $descripcion = '';
    if ($result->failure) {
        $descripcion = $result->results[0]->error;
        
    }
    return Array('fallo'=>$result->failure, 'error'=>$descripcion);
}
// require __DIR__ . '/vendor/autoload.php';
// use Minishlink\WebPush\WebPush;
// use Minishlink\WebPush\Subscription;


// //$subscription = Subscription::create(json_decode(file_get_contents('php://input'), true));


// $auth = array(
//     'VAPID' => array(
//         'subject' => 'ynfantes@gmail.com',
//         'publicKey' => file_get_contents(__DIR__ . '/keys/public_key.txt'), // don't forget that your public key also lives in app.js
//         'privateKey' => file_get_contents(__DIR__ . '/keys/private_key.txt'), // in the real world, this would be in a secret file
//     ),
// );
// $listado = $db->select('*','suscripciones');


// if ($listado['suceed'] && count($listado['data'])>0) {
    
//     $webPush = new WebPush($auth);

    
//     foreach ($listado['data'] as $registro) {

//         if (!$registro['eliminar']) {

//             $subscription = Subscription::create(json_decode($registro['suscripcion'], true));
            
            
//             $report = $webPush->sendOneNotification(
//                 $subscription,
//                 json_encode($_POST)
//             );
            
//             // handle eventual errors here, and remove the subscription from your server if it is expired
//             $endpoint = $report->getRequest()->getUri()->__toString();
            
//             if ($report->isSuccess()) {
//                 echo "[v] Message sent successfully for subscription {$endpoint}.\n";
//             } else {
//                 echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
//                 if ($report->isSubscriptionExpired()) {
//                     $db->update('suscripciones',Array("eliminar"=>1),Array("id"=>$registro['id']));
//                 }
//             }
            
//         }
        
//     }    
 
// } else {
//     echo 'No existen suscripciones registradas';
// }
