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
*  @author Lucid Networks <info@lucidnetworks.es>
*  @copyright  2006-2015 Lucid Networks
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

namespace Anfix;

class Invoice extends BaseModel
{
    protected $applicationId = '3';
    protected $update = true;
    protected $create = true;
    protected $delete = true;
	
   /**
	* Renumera las facturas
	* @param int $accountingPeriodYear Año
	* @param string $invoiceType Tipo de factura a renumerar según doc anfix
	* @param string $companyId Identificador de la empresa
	* @return int Número de facturas afectadas
	*/
	public static function renumerate($accountingPeriodYear,$invoiceType,$companyId){
		$obj = new static([],false,$companyId);
        $result = self::_send(['AccountingPeriodYear' => $accountingPeriodYear, 'InvoiceType' => $invoiceType],$companyId,'renumerate');
        return $result->outputData->{$obj->Model}->rowcount;
	}
	
   /**
	* Informe de IVA repercutido
	* @param array $params Parámetros para el reporte, AccountingPeriodYear es obligatorio
	* @param string $companyId Identificador de la empresa
	* @return Object
	*/
	public static function reportIssued(array $params,$companyId){
		$obj = new static([],false,$companyId);
		$urlBase = str_replace('/invoice/','/',$obj->apiBaseUrl);
        $result = self::_send($params,$companyId,'report/issuedinvoice',[],[],$urlBase);
		if(!empty($result->outputData->{$obj->Model}))
			return $result->outputData->{$obj->Model};

        return $result->outputData;
	}
	
   /**
	* Informe de IVA soportado
	* @param array $params Parámetros para el reporte, AccountingPeriodYear es obligatorio
	* @param string $companyId Identificador de la empresa
	* @return Object
	*/
	public static function reportReceived($params,$companyId){
		$obj = new static([],false,$companyId);
		$urlBase = str_replace('/invoice/','/',$obj->apiBaseUrl);
        $result = self::_send($params,$companyId,'report/receivedinvoice',[],[],$urlBase);
		if(!empty($result->outputData->{$obj->Model}))
			return $result->outputData->{$obj->Model};

        return $result->outputData;
	}
}