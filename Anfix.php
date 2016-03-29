<?php

namespace Anfix;

use Anfix\Exceptions\AnfixResponseException;

class Anfix {

    private static $curl;
	private static $config;
    private static $token;
	
    //Traducción de los errores más comunes
    private static $errorsCodes = [
        'ERR000050000' => 'Es posible que companyId no sea válido',
        'ERR000020001' => 'Debe indicar un oauth_consumer_key y oauth_signature en el fichero de configuración'
    ];

    /**
     * Establece el entorno (token de la cuenta cliente y configuracion) sobre el que hacer las peticiones
     * @param array $token Token y clave para la cuenta a la que deseamos acceder [account_token,account_secret]
     * Si no se indica se obtendrán los datos indicados en default_token
     * @param string $configFile Path del fichero de configuración
     * Si no se indica se obtendrá config.inc.php
     */
	public static function env(array $token = [],$configFile = null){
		if(!empty($configFile))
			self::$config = (include $configFile);
		else if(empty(self::$config))
			self::$config = (include 'config.inc.php');

        if(!empty($token))
            self::$token = $token;
        else
            self::$token = self::$config['default_token'];
	}

    /**
     * Devuelve los valores de configuración
     * @return array ['config' => ..., 'token' => ...]
     */
    public static function getEnv(){
        if(empty(self::$config))
            self::env();

        return ['config' => self::$config, 'token' => self::$token];
    }

    /**
     * Devuelve las cabeceras de una petición
     * @param $url
     * @param array $headers
     * @return mixed
     * @throws Exceptions\AnfixResponseException
     */
    public static function getHeaders($url, array $headers){
        $headers[] = 'Content-Type: application/json';
        return self::send($url,$headers,[],true)['headers'];
    }

    /**
     * Devuelve el retorno de una petición
     * @param $url
     * @param array $data Parámetros de la petición
	 * @param array $config Parámetros de configuración o config por defecto si vacío
	 * @param array $token ['TOKEN','TOKEN_PASSWORD'] o default_token si vacío
     * @param bool $contentType = 'application/json'
     * @param array $extraHeaders = Parámetros extra para las cabeceras
     * @return mixed
     * @throws \Exception
     */
    public static function sendRequest($url, array $data, array $config = [], array $token = [], $contentType = 'application/json', array $extraHeaders = []){
		if(empty($config))
			$config = self::getEnv()['config'];
	
		if(empty($token))
			$token = self::getEnv()['token'];
			
        $headers = ["Authorization: realm=\"{$config['realm']}\",
            oauth_consumer_key=\"{$config['oauth_consumer_key']}\",
            oauth_signature_method=\"PLAINTEXT\",
            oauth_token=\"{$token[0]}\",
            oauth_signature=\"{$config['oauth_signature']}&{$token[1]}\""];

        if($contentType == 'application/json'){
            $data = json_encode($data);

        $headers[] = "Content-Type: $contentType";
        
        return self::send($url,array_merge($headers,$extraHeaders),$data,false)['response'];
    }

    /**
     * Hace una llamada con las cabeceras indicadas
     * @param string $url Url a acceder
     * @param array $headers array de arrays sin índices
     * @param string $data Datos a enviar
     * @param bool $returnHeaders = false
     * @throws Exceptions\AnfixResponseException
     * @return array(body,headers)
     */
    private static function send($url, array $headers, $data = '', $returnHeaders = false){

        if(empty(self::$curl))
            self::$curl = curl_init();
            
        $headers[] = 'Host: anfix.com';     

        curl_setopt(self::$curl, CURLOPT_HTTPGET, false); //Post query
        curl_setopt(self::$curl, CURLOPT_POST, true); //Post query
        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt(self::$curl, CURLOPT_URL, $url); //Set complete url
        curl_setopt(self::$curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt(self::$curl, CURLOPT_HEADER, $returnHeaders);
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);  // Return transfer as string

        $curl_response = curl_exec(self::$curl);

        if(!$curl_response)
            throw new AnfixResponseException('La solicitud curl falló devolviendo el siguiente error: '.curl_error(self::$curl));

        $response_headers = [];

        if($returnHeaders) {
            list($rheader, $response_raw) = explode("\r\n\r\n", $curl_response, 2);

            foreach(explode("\r\n",$rheader) as $value){
                $v = explode(': ',$value,2);
                if(count($v) != 2)
                    continue;
                $response_headers[$v[0]] = $v[1];
            }

        }else
            $response_raw = $curl_response;

        $response = json_decode($response_raw);

        if($response == null) //A veces las cadenas vienen mal codificadas
            $response = json_decode(utf8_decode($response_raw));

        if($response == null)
            throw new AnfixResponseException('La respuesta no puede interpretarse como una cadena Json válida: '.utf8_decode($response_raw));

        if($response->result != 0 && !empty($response->errorList[0]->text)) {
            $err_message = $response->errorList[0]->text . (!empty(self::$errorsCodes[$response->errorList[0]->code]) ? ' ('.self::$errorsCodes[$response->errorList[0]->code].')' : '');
            throw new AnfixResponseException($response->errorList[0]->code . ': ' . $err_message);
        }

        if($response->result != 0)
            throw new AnfixResponseException("Se esperaba result = 1 en la llamada a $url con los datos:".print_r($data,true).' pero la respuesta fue:'.print_r($response,true));

        return ['response' => $response, 'headers' => $response_headers];
    }
	
	// ############################## OEPRACIONES CON TOKEN ##############################
	
	/**
     * Inicio de la generación de un token anfix
	 * @param string $identifier Identificador para control interno
	 * @param string $returnUrl Url de callback para la entrega de las claves
     * @throws Exceptions\AnfixResponseException
     */
	public static function generateToken($identifier, $returnUrl){
        if(empty(self::$config))
            self::env();

		$oauth_callback = urlencode($returnUrl);

        //Comprobación de permiso de escritura del fichero de tokens temporal
        if(!is_writable(self::$config['tokens_temp_file']))
            throw new AnfixResponseException('No se puede escribir el fichero '.self::$config['tokens_temp_file'].' este fichero necesita ser escrito para almacenar las claves temporales.');

        $realm = self::$config['realm'];
        $oauth_consumer_key = self::$config['oauth_consumer_key'];
        $oauth_signature = self::$config['oauth_signature'];
        $loginUrl = self::$config['loginUrl'];

        $response = Anfix::getHeaders(self::$config['requestTokenUrl'],
            ["Authorization: realm=\"{$realm}\",
            oauth_consumer_key=\"{$oauth_consumer_key}\",
            oauth_signature_method=\"PLAINTEXT\",
            oauth_callback=\"{$oauth_callback}\",
            oauth_signature=\"{$oauth_signature}&\""]
        );

        //Obtenemos el fichero temporal de tokens
		$temp = (include self::$config['tokens_temp_file']);

        //Borramos las solicitudes expiradas
        foreach($temp as $k => $values)
            if($values['timestamp'] + 3600 < time())
                unset($temp[$k]);

		$temp[$response['oauth_token']] = ['identifier' => $identifier, 'secret' => $response['oauth_token_secret'], 'timestamp' => time()];
		file_put_contents(self::$config['tokens_temp_file'],'<?php return '.var_export($temp,true).';');

        //Enviamos al usuario a la página de anfix
		header("Location: {$loginUrl}?oauth_token={$response['oauth_token']}", true, 302);
		exit();
	}
	
	/**
     * Realiza la segunda parte de la obtención del token
	 * @param callable $function($ientifier,$token,$secret) Función que se ejecutará al recibir el token definitivo
     */
    public static function onGeneratedToken(callable $function){

        if(empty(self::$config))
            self::env();

        $token = $_GET['oauth_token'];
        $verifier = $_GET['oauth_verifier'];

        $realm = self::$config['realm'];
        $oauth_consumer_key = self::$config['oauth_consumer_key'];
        $oauth_signature = self::$config['oauth_signature'];

		//Obtenemos el fichero temporal de tokens
		$temp = (include 'tokens_temp.php');
        $secret = $temp[$token]['secret'];
		$identifier = $temp[$token]['identifier'];

        $response = Anfix::getHeaders(self::$config['accessTokenUrl'],
            ["Authorization: realm=\"{$realm}\",
            oauth_consumer_key=\"{$oauth_consumer_key}\",
            oauth_signature_method=\"PLAINTEXT\",
            oauth_token=\"$token\",
            oauth_verifier=\"$verifier\",
            oauth_signature=\"{$oauth_signature}&$secret\""]
        );

        //Enviamos la respuesta al closure indicado
		return $function($identifier,$response['oauth_token'],$response['oauth_token_secret']);
    }

    /**
     * Invalida un token
     * @param string $token Token a invalidar
     * @param string $secret Contraseña del token a invalidar
     * @return mixed
     */
    public static function invalidateToken($token,$secret){

        if(empty(self::$config))
            self::env();

        $realm = self::$config['realm'];
        $oauth_consumer_key = self::$config['oauth_consumer_key'];
        $oauth_signature = self::$config['oauth_signature'];

        $response = Anfix::getHeaders(self::$config['invalidateTokenUrl'],
            ["Authorization: realm=\"{$realm}\",
            oauth_consumer_key=\"{$oauth_consumer_key}\",
            oauth_signature_method=\"PLAINTEXT\",
            oauth_token=\"{$token}\",
            oauth_signature=\"{$oauth_signature}&{$secret}\""]
        );

        return true;
    }
}

/**
 * Autocarga de clases
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Anfix\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    $file_entity = $base_dir . 'Entities/'.str_replace('\\', '/', $relative_class) . '.php';
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file_entity))
        require $file_entity;
    else if (file_exists($file))
        require $file;
    else{
        throw new \Exception("El sistema no puede localizar la clase $relative_class, esto puede deberse a un problema de permisos");
    }

});