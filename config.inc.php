<?php return array (
    'realm' => 'panel',
    'tokens_temp_file' => __DIR__.'/tokens_temp.php', //Fichero donde se almacenarán los tokens temporales
    'oauth_consumer_key' => 'EAC196FDD6D8EABA7EB0DBAD538DBCD9', //Consumer key de su cuenta partner anfix
    'oauth_signature' => '36E5E96FC8CDEB977B7F342C759C1775',    //Clave de firma de su cuenta partner anfix
    'applicationIdUrl' => array(
        '1' => 'https://apps.anfix.com/os/os/parc/',
        'E' => 'https://apps.anfix.com/facturapro-servicios/gestiona/servicios/'
        '3' => 'https://apps.anfix.com/contapro/conta/'
    ),
    'requestTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/request_token',
    'accessTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/access_token',
    'invalidateTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/invalidate_token',
    'loginUrl' => 'https://anfix.com/login-partner',
    'default_token' => [ //Si no indica ningún token de acceso en sus conexiones se utilizarán estos valores por defecto (Monocuenta)
        'F196EFEF702C3BE6ED1AE9B5471AB9D3', //Token para conexión a cuenta por defecto
        '0BD4DAC4FDDE2B12E56B887B8AE4293E'  //Secret para conexión a cuenta por defecto
    ]
);