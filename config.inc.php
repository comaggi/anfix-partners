<?php return array (
    'realm' => 'panel',
    'tokens_temp_file' => __DIR__.'/tokens_temp.php', //Fichero donde se almacenarán los tokens temporales
    'oauth_consumer_key' => '584B5E480D0B4F5C7343319BA86562CA', //Consumer key de su cuenta partner anfix
    'oauth_signature' => '9FC81CD4DD31D54105EDDE5DF67B79E7',    //Clave de firma de su cuenta partner anfix
    'applicationIdUrl' => array(
        '1' => 'https://apps.anfix.com/os/os/parc/',
        'E' => 'https://apps.anfix.com/facturapro-servicios/gestiona/servicios/'
    ),
    'requestTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/request_token',
    'accessTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/access_token',
    'invalidateTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/invalidate_token',
    'loginUrl' => 'https://anfix.com/login-partner',
    'default_token' => [ //Si no indica ningún token de acceso en sus conexiones se utilizarán estos valores por defecto (Monocuenta)
        '', //Token para conexión a cuenta por defecto
        ''  //Secret para conexión a cuenta por defecto
    ]
);