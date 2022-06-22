<?php
include_once 'configuracion.php';

include_once dirname(dirname(dirname(__FILE__))).'/framework/twig/lib/Twig/Autoloader.php';
include_once SERVER_ROOT . 'includes/extensiones.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(SERVER_ROOT.'template');
$twig = new Twig_Environment($loader, array(
            'debug' => true,
            //'cache' => SERVER_ROOT . 'cache',
            'cache' => false,
            "auto_reload" => true)
);
session_start();
if (isset($_SESSION)){
    $twig->addGlobal("session", $_SESSION);
}
$twig->addExtension(new extensiones());
$twig->addExtension(new Twig_Extension_Debug());


spl_autoload_register( function($class) {
    include_once SERVER_ROOT.'/includes/'.$class.'.php';
});


if (isset($_GET['logout']) && $_GET['logout'] == true) {
    $user_logout = new usuarios();
    $user_logout->logout();
}

//https://www.google.com/settings/u/1/security/lesssecureapps
//https://accounts.google.com/DisplayUnlockCaptcha
//https://security.google.com/settings/security/activity?hl=en&pli=1