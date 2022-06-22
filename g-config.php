<?php
require __DIR__.'/vendor/autoload.php';

$gClient = new Google_Client();
$gClient->setClientId('1001343383317-irgmpd66k9d2kogfvt6jj8572pnlro8o.apps.googleusercontent.com');
$gClient->setClientSecret('jNmXLuF21XmgEG5yHN-RFLlS');
$gClient->setApplicationName('Administracion Soporte V2');
$gClient->setRedirectUri('https://soporte.administracion-condominio.com.ve/g-callback.php');
$gClient->addScope('https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email');