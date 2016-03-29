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

class PredefinedAccountingEntry extends BaseModel
{
    protected $applicationId = '3';
    protected $apiUrlSufix = 'company/predefinedaccountingentry/';
    protected $update = true;
    protected $create = true;
    protected $delete = true;
	
   /*
	* Duplicación de asientos predefinidos entre empresas.
	* @param array $params Parámetros para el reporte, AccountingPeriodYear, Action, CompanyIdSource y PredefinedEntriesId obligatorios
	* @param $companyId Identificador de la empresa
	* @return Int Número de elementos afectados
	*/
	public static function copy(array $params,$companyId){
		$obj = new static([],false,$companyId);
        $result = self::send($params,$companyId,'copy');
        return $result->outputData->{$obj->Model}->rowcount;
	}
	
   /*
	* Búsqueda de asientos predefinidos por tipo de asiento.
	* Su utilización es similar a ->get, siempre después de ::where()
	* @param $accountingPeriodYear 	Año del ejercicio contable en le que se realiza la búsqueda
	* @param $includePredefined true o false indicando si se deben incluir en los resultados los asientos predefinidos estructurales o no, respectivamente
	* @param array $fields = [] Campos a devolver
	* @param $order Ordenación 	PredefinedAccountingEntryCode, PredefinedAccountingEntryTypeId
	* @return array BaseModel
	*/
	public function getbyentrytype($accountingPeriodYear, $includePredefined, array $fields = [], $order = 'PredefinedAccountingEntryCode'){
        $params = ['AccountingPeriodYear' => $accountingPeriodYear, 'IncludeStructuralPredefinedAccountingEntries' => $includePredefined, 'Order' => $order];
		return $this->get($fields,null,'searchbyentrytype',$params);
	}
	
   /*
	* Selección de datos de asientos predefinidos.
	* @param $accountingPeriodYear Año
	* @param $predefinedEntryId Identificador del asiento predefinido a seleccionar.
	* @param $companyId Identificador de la empresa
	* @return Object
	*/
	public static function selectpredefined($accountingPeriodYear,$predefinedEntryId,$companyId){
		$obj = new static([],false,$companyId);
        $result = self::send(['AccountingPeriodYear' => $accountingPeriodYear, 'AccountingEntryPredefinedEntryId' => $predefinedEntryId],$companyId,'selectpredefined');
        return $result->outputData->{$obj->Model};
	}
	
}