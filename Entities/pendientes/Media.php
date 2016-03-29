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
    
   /*
	* Descarga un fichero
	* @param array $params Parámetros para la descarga
	* @param $companyId Identificador de la empresa
    * @params string $path Ruta donde se guardará el fichero descargado
	* @return File
	*/
	public static function download(array $params,$companyId,$path){
        return;
	}

}