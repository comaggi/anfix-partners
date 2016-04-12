<?php return array (
    'realm' => 'panel',
    'tokens_temp_file' => __DIR__.'/tokens_temp.php', //Fichero donde se almacenarán los tokens temporales
    'oauth_consumer_key' => 'EAC196FDD6D8EABA7EB0DBAD538DBCD9', //Consumer key de su cuenta partner anfix
    'oauth_signature' => '36E5E96FC8CDEB977B7F342C759C1775',    //Clave de firma de su cuenta partner anfix
    'applicationIdUrl' => array(
        '1' => 'http://apps.anfix.com/os/os/parc/',
        'E' => 'http://apps.anfix.com/facturapro-servicios/gestiona/servicios/',
        '3' => 'http://apps.anfix.com/contapro/conta/',
        '5' => 'http://apps.anfix.com/documentos/'
    ),
    'requestTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/request_token',
    'accessTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/access_token',
    'invalidateTokenUrl' => 'http://apps.anfix.com/os/os/parc/partner/invalidate_token',
    'loginUrl' => 'http://anfix.com/login-partner',
    'default_token' => [ //Si no indica ningún token de acceso en sus conexiones se utilizarán estos valores por defecto (Monocuenta)
        'C57B2223BEDB8D97559DA5C7C6DB7815', //Token para conexión a cuenta por defecto
        '7240105D5093A26D029CA1F785F37F39'  //Secret para conexión a cuenta por defecto
    ]
);