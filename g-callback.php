<?php
require_once './g-config.php';
require './includes/constants.php';

if (isset($_SESSION['access_token'])) {
    $gClient->setAccessToken($_SESSION['access_token']);
} else if (isset($_GET['code'])) {
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
} else {
    header('Location: https://soporte.administracion-condominio.com.ve/administracion') ; //change this
    exit(0);
}
//if (!isset($token['access_token'])) {
//    die($token['error_description']);
//    exit(0);
//}

$oAuth = new Google_Service_Oauth2($gClient);
$userData = $oAuth->userinfo_v2_me->get();

$email = $userData['email'];
$id    = $userData['id'];
$usuarios = new usuarios();
$r = $usuarios->obtenerUsuarioPorEmail($email);

$password = isset($r['password'])? base64_decode($r['password']):'';
$r = $usuarios->login($email, $password);
$mensaje = $r['mensaje'];
if($r['suceed']) {

    if ($r['data'][0]['id_google']==NULL || $r['data'][0]['id_google']=='') {
        $usuarios->actualizar($r['data'][0]['id'], array('id_google'=>$id));
        $_SESSION['usuario']['id_google'] = $id;
    }
    $_SESSION['picture'] = $userData['picture'];
    header("location:".ROOT."administracion/articulos/");
} else {
    echo $twig->render('/administracion/login.html.twig',Array('mensaje'=>$mensaje));
}
    
