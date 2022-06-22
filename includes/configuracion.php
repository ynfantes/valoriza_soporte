<?php
date_default_timezone_set("America/La_Paz");
define('mailPHP',   0);
define('sendMail',  1);
define('SMTP',      2);

$debug              = false;
$sistema            = '/';
$email_error        = false;
$mostrar_error      = true;
$programa_correo    = mailPHP;

if ($_SERVER['SERVER_NAME'] == "soporte.administracion-condominio.com.ve") {

    $user               = "supportv2";
    $password           = "valoriza25231";
    $db                 = "valoriza2_support";
    $host               = "localhost";
    $email_error        = true;
    $mostrar_error      = false;
    $debug              = false;
    $sistema            = "/";
    $programa_correo    = SMTP;
    $protocolo          = 'https';

} else {

    $user       = "root";
    $password   = "";
    $db         = "valoriza2_support";
    $host       = "localhost";
    $protocolo  = 'http';

}

define('HOST',              $host);
define('USER',              $user);
define('PASSWORD',          $password);
define('DB',                $db);
define('SISTEMA',           $sistema);
define('EMAIL_ERROR',       $email_error);
define('EMAIL_CONTACTO',    'ynfantes@gmail.com');
define('EMAIL_TITULO',      'error');
define('MOSTRAR_ERROR',     $mostrar_error);
define('DEBUG',             $debug);
define('TITULO',            'Centro de Ayuda Valoriza2');
/**
 * para las urls
 */
define('ROOT',              $protocolo.'://'.$_SERVER['SERVER_NAME'].SISTEMA);
define('URL_SISTEMA',       ROOT.'enlinea');
define('URL_INTRANET',      ROOT.'intranet');
/**
 * para los includes
 */
define('SERVER_ROOT',       $_SERVER['DOCUMENT_ROOT'].SISTEMA);
define('TEMPLATE',          SERVER_ROOT."/template/");
define('PROGRAMA_CORREO',   $programa_correo);
define('NOMBRE_APLICACION', 'Condominio en Línea');
define('SMTP_SERVER',       'administracion-condominio.com.ve');
define('PORT',              465);
define('USER_MAIL',         'no-reply@administracion-condominio.com.ve');
define('PASS_MAIL',         '8xWOqJsj5no8');
define('DEMO',              0);