<?php
return array(
"base_url" => "https://soporte.administracion-condominio.com.ve/vendor/hybridauth/hybridauth",
"providers" => array (
    "Facebook" => array (
        "enabled" => true,
        "keys"    => array ( "id" => "242011383598796", "secret" => "151a51441d76a82b33a9e6ac8501a896"),
        "scope"   => ['email', 'user_about_me', 'user_birthday', 'user_hometown'], // optional
        "photo_size" => 200, // optional
    )
));