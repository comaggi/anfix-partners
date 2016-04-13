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
*  Date: 9/2/16 18:57
*  @author Networkkings <info@lucidnetworks.es>
*  @copyright  2006-2015 Lucid Networks
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

namespace Anfix;

class Media extends StaticModel
{
    protected static $applicationId = '1';
	protected static $apiBaseUrl = 'http://apps.anfix.com/os/media/';
    
   /**
	* Descarga un fichero
	* @param array $params namespace y show obligatorios
    * @param string $path Ruta donde se guardará el fichero descargado
	* @return true
	*/
	public static function download(array $params, $path){
        return self::_download($params,$path);
	}
	
   /**
	* Obtiene el progreso del fichero en proceso de carga
	* @return Object
	*/
	public static function uploadprogress(){
        $result = self::_send([],null,'uploadprogress');
        return $result->outputData->{self::$Model};
	}

   /**
	* Subida de un fichero
	* @param string $path Ruta del fichero a enviar
	* @return Object
	*/
	public static function upload($path){
		return parent::_upload($path);
	}

}