<?php return array (
    'realm' => 'panel',
    'oauth_consumer_key' => '', //Consumer key de su cuenta partner anfix
    'oauth_signature' => '',    //Clave de firma de su cuenta partner anfix
    'applicationIdUrl' => array(
        '1' => 'https://apps.anfix.com/os/os/parc/',
        'E' => 'https://apps.anfix.com/facturapro-servicios/gestiona/servicios/'
    ),
    'requestTokenUrl' => 'https://apps.anfix.com/os/os/parc/partner/request_token',
    'accessTokenUrl' => 'https://apps.anfix.com/os/os/parc/partner/access_token',
    'loginUrl' => 'https://anfix.com/login-partner',
    'default_token' => [ //Si no indica ningún token de acceso en sus conexiones se utilizarán estos valores por defecto (Monocuenta)
        '', //Token para conexión a cuenta por defecto
        ''  //Secret para conexión a cuenta por defecto
    ]
);