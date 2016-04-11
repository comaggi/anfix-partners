<?php

/*
* 2006-2015 Lucid Networks
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
*
* DISCLAIMER
*
*  Date: 9/2/16 18:21
*  @author Networkkings <info@lucidnetworks.es>
*  @copyright  2006-2015 Lucid Networks
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

namespace Anfix;

use Anfix\Exceptions\AnfixException;

/**
 * Class BaseModel
 * Esta clase es la clase base desde la que deben heredar todos los modelos de anfix
 * su comportamiento en muy similar al de Eloquent, salvo en la intrucción where que no admite llamadas recursivas
 * debe rellenar en cada modelo los datos obligatorios para poder establecer una conexión con anfix
 * @package Lucid\Anfix
 */
class StaticModel{

    protected static $applicationId; //Obligatorio, Identificador de la App Anfix, este identificador asocia la Url base por defecto conforme a config/anfix.php
    protected static $Model; //Opcional, Nombre de la entidad en Anfix, por defecto será el nombre de la clase
    protected static $primaryKey; //Opcional, Nombre de la clave primaria en Anfix, por defecto {$Model}Id
    protected static $apiBaseUrl; //Opcional, Url de la API a la que conectar, por defecto se obtiene de config/anfix en función del applicationId
    protected static $apiUrlSufix; //Opcional, Sufijo que se añade a la url de la API, por defecto nombre de la entidad, si se indica apiBaseUrl no se tendrá en cuenta este parámetro
    
    /**
     * Construye la configuración estática
     */
    protected static function constructStatic(){
    
        if(empty(self::$applicationId))
            throw new AnfixException('Debe indicar un applicationId en el modelo para poder utilizar la API');
            
        if(empty(self::$Model)) {
            $class = explode('\\', get_called_class());
            self::$Model = end($class);
        }
            
        if(empty(self::$apiUrlSufix))
            self::$apiUrlSufix = strtolower(self::$Model).'/';

        if(empty(self::$apiBaseUrl))
            self::$apiBaseUrl = Anfix::getEnv()['config']['applicationIdUrl'][self::$applicationId].self::$apiUrlSufix;

        if(empty(self::$primaryKey))
            self::$primaryKey = self::$Model.'Id';
    }

    /**
     * Envia una solicitud estándar
     * @param array $params
     * @param null $companyId
     * @param $path
     * @return mixed
     */
    protected static function _send(array $params, $companyId = null, $path){
        self::constructStatic();

        return Anfix::sendRequest(self::$apiBaseUrl.$path,[
            'applicationId' =>  self::$applicationId,
            'companyId' => $companyId,
            'inputBusinessData' => [
                self::$Model => !empty($params) ? $params : new \stdClass()
            ]
        ]);
    }
    
    
   /**
	* Descarga un fichero
	* @param array $params Parámetros para la descarga
	* @params string $path Ruta donde se guardará el fichero descargado
	* @params string $url Url punto acceso, por defecto {self::$apiBaseUrl}/download
	* @return true
	*/
	protected static function _download(array $params, $path, $url = null){
		self::constructStatic();

		if(empty($url))
			$url = self::$apiBaseUrl.'/download';

		return BaseModel::_download($params,$path,$url);
	}
    
    
	/**
	* Subida de un fichero
	* @params string $path Ruta del fichero a enviar
	* @params string $url Url punto acceso, por defecto {self::$apiBaseUrl}/upload
	* @return Object
	*/
	protected static function _upload($path, $url = null){
		self::constructStatic();

		if(empty($url))
			$url = self::$apiBaseUrl.'/upload';
			
		return BaseModel::_upload($path,$url);
	}
    
}