<?php
require_once '../includes/constants.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $usuarios = new usuarios();
    $r = $usuarios->login($_POST['email'], $_POST['password']);
    $mensaje = $r['mensaje'];
    if($r['suceed']) {
        header("location:".ROOT."administracion/articulos/");
//        $temas = new temas();
//        $lista_temas = array();
//        $r = $temas->listarActivos(array('inactivo'=>0));
//        if($r['suceed'] && count($r['data'])>0) {
//            $lista_temas = $r['data'];
//        }
//        echo $twig->render('/administracion/index.html.twig',array(
//            'temas'     => $lista_temas,
//            'session'   => $_SESSION));
    } else {
        echo $twig->render('/administracion/login.html.twig',Array('mensaje'=>$mensaje));
    }
} else {
    require_once '../g-config.php';
    $loginUrl = $gClient->createAuthUrl();
    
    if(session_status()  == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['state'] = md5(uniqid(rand(), TRUE));
    //constant('ROOT')~'faceauth.php'|url_encode
    $url = urlencode(ROOT.'faceauth.php');
//    if (isset($_GET['login'])) {
//        
//        Auth::getUserAuth();
//    }
    echo $twig->render('/administracion/login.html.twig',array(
        'url'   => $url,
        'state' => $_SESSION['state'],
        'loginUrl'   => $loginUrl)
            );
}