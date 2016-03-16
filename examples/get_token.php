<?php

/**
 * Ejemplo de solicitud de token a la API
 */

require '../Anfix.php'; //Este fichero debe incluirse siempre

$self_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; //Url a la que anfix llamará para entregar las claves
$tokens_file = __DIR__.'/tokens.php'; //Fichero donde almacenar los tokens de este ejemplo

//Nos aseguramos de que el fichero donde se almacenarán los tokens permite escritura
if(!is_writable (__DIR__.'/tokens.php'))
    throw new Exception('Para ejecutar este ejemplo se necesita poder escribir en el fichero '.__DIR__.'/tokens.php');

if(empty($_POST)) //Si se trata de una petición simple get estamos en la primera parte de la obtención del token
    Anfix\Anfix::generateToken('test_identifier', $self_url);

else //Si se reciben datos por post estaremos en la segunda parte (llamada desde anfix a nuestro script)
    \Anfix\Anfix::onGeneratedToken(function($identifier,$token,$secret) use ($tokens_file){

        /**
         * Ejemplo de almacenamiento de los tokens en un fichero,
         * Se recomienda almacenarlos en una base de datos
         */
        $temp = file_exists($tokens_file) ? (include $tokens_file) : []; //Cargamos el fichero
        $temp[] = ['identifier' => $identifier, 'token' => $token, 'secret' => $secret]; //Almacenamos los datos recibidos
        file_put_contents($tokens_file,'<?php return '.var_export($temp,true).';'); //Guaradamos el fichero
    });


