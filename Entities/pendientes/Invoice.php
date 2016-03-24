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

class Invoice extends BaseModel
{
    protected $applicationId = '3';
    protected $update = true;
    protected $create = true;
    protected $delete = true;
	
   /*
	* Renumera las facturas
	* @param $accountingPeriodYear Año
	* @param $invoiceType Tipo de factura a renumerar según doc anfix
	* @param $companyId Identificador de la empresa
	* @return int Número de facturas afectadas
	*/
	public static function renumerate($accountingPeriodYear,$invoiceType,$companyId){
		//renumerate
		return $response->rowcount;
	}
	
   /*
	* Informe de IVA repercutido
	* @param array $params Parámetros para el reporte, AccountingPeriodYear es obligatorio
	* @param $companyId Identificador de la empresa
	*/
	public static function reportIssued(array $params,$companyId){
		//report/issuedinvoice
	}
	
   /*
	* Informe de IVA soportado
	* @param array $params Parámetros para el reporte, AccountingPeriodYear es obligatorio
	* @param $companyId Identificador de la empresa
	*/
	public static function reportReceived($params,$companyId){
		//report/issuedinvoice
	}
}