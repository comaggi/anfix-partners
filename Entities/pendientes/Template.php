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

class Template extends BaseModel
{
    protected $applicationId = '3';
    protected $apiUrlSufix = 'company/template/';
    protected $update = true;
    protected $create = false;
    protected $delete = true;

   /*
	* Establecer la plantilla por defecto.
	* @param $templateId Identificador único de la plantilla a establecer como plantilla por defecto
	* @param $templateTypeId Tipo de documento para el que se establecerá la plantilla por defecto
	* @param $companyId Identificador de la empresa
	* @return Object
	*/
	public static function setdefault($templateId,$templateTypeId,$companyId){
		$obj = new static([],false,$companyId);
        $result = self::send(['TemplateId' => $templateId, 'TemplateTypeId' => $templateTypeId],$companyId,'setdefault');
        return true;
	}
}