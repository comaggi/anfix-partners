<?php

/**
 * Ejemplo de solicitud de token a la API
 */

require '../Anfix.php'; //Este fichero debe incluirse siempre

$self_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; //Url a la que anfix llamará para entregar las claves

if(empty($_GET['oauth_token'])) //Si se trata de una petición simple get estamos en la primera parte de la obtención del token
    Anfix\Anfix::generateToken('test_identifier', $self_url);

else //Si se reciben datos por post estaremos en la segunda parte (llamada desde anfix a nuestro script)
    \Anfix\Anfix::onGeneratedToken(function($identifier,$token,$secret) use ($tokens_file){

        //Introducimos los datos del token en una variable
        $tokenData = ['identifier' => $identifier, 'token' => $token, 'secret' => $secret];

        /**
         * Ejemplo de almacenamiento de los tokens en un fichero,
         * Se recomienda almacenarlos en una base de datos
         */
        $tokens_file = __DIR__.'/tokens.php';
        $temp = file_exists($tokens_file) ? (include $tokens_file) : []; //Cargamos el fichero
        $temp[] =  $tokenData; //Almacenamos los datos recibidos
        file_put_contents($tokens_file,'<?php return '.var_export($temp,true).';'); //Guaradamos el fichero

        /**
         * Ejemplo de mostrado de los datos en pantalla
         */
        echo "Token generado con éxito.<br/> Identifier: $identifier <br/> Token: $token <br/> Secret: $secret";
    });

