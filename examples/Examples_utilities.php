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
*  Date: 26/4/16 16:53
*  @author anfix <info@anfix.com>
*  @copyright  anfix
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

/**
 * Catálogo de ejemplos de uso de los endpoints de algunas utilidades de la API de anfix
 * Antes de ejecutar ninguno de estos ejemplso asegúrese que dispone de un token y key válidos previamente generados 
 * según se especifica en la QuickStart de Readme.md
 * Puede ejecutar este fichero como un test general
 */
  
include 'example_utils.php';

$companyId = firstCompanyId(); //Obtención del id de la primera empresa disponible (función únicamente válida para ejemplos)	

//1) Obtención de sugerencia de vencimientos
    //$expirations = Anfix\Expiration::compute(['ExpirationsQuantity' => 1000.23, 'ExpirationsStartDate' => '01/01/2016', 'ExpirationsPayChargeMethodId' => 'e'],$companyId);
	/*print_result('Sugerencia de vencimientos',array_map(function($e){ return array(
		"ExpirationDate" => $e->ExpirationDate,    
	    "ExpirationQuantity" => $e->ExpirationQuantity,
	    "ExpirationPercentage" => $e->ExpirationPercentage  
	); },$expirations));*/

//2) Obtención del siguiente número de cliente/proveedor
    //$nextNumber = Anfix\NextNumber::compute('1',$companyId);
    //print_result('Siguiente número de cliente libre',$nextNumber);

//3) Obtención del siguiente número de documento (factura emitida)
//TO-DO: da un error por el nombre de la cebolla
    //$nextNumberFromSerial = Anfix\NextNumberFromSerial::compute(['DocumentDate'=> '01/01/2016', 'SerialNum' => 'F2016', 'DocumentTypeId' => '7'],$companyId);
    //print_result('Siguiente número de Factura',$nextNumberFromSerial);

//4) Renumeración de facturas en contabilidad
	//$renumeratedInvoices = Anfix\Invoice::renumerate(2016, 'R', $companyId);
    //print_result('Número de facturas renumeradas',$renumeratedInvoices);

//5) Obtención de sugerencia de esquema de asiento en base a un predefinido y una cuenta
    //TO-DO: pintar un objeto no un array
	//$completePredefined = Anfix\CompanyAccountingEntryReference::completepredefined(['AccountingPeriodYear' => 2016, 'AccountingEntryPredefinedEntryId' => 'P', 'CompanyAccountingAccountNumber' => 4300000], $companyId);
    //print_result('Sugerencia de asiento en base al predefinido',$completePredefined);
	/*print_result('Sugerencia de asiento en base al predefinido',array_map(function($e){ return array(
		"CompanyAccountingEntryNote" => $e->CompanyAccountingEntryNote,    
	    "InvoiceLine" => $e->InvoiceLine,
	    "InvoiceOperationKeyId" => $e->InvoiceOperationKeyId,
	    "InvoiceOperationKeyDescription" => $e->InvoiceOperationKeyDescription,
	    "InvoiceOperationKeyCode" => $e->InvoiceOperationKeyCode  	    	    
	); },$completePredefined));*/

//6) Transferir movimientos de una cuenta a otra
	//$accountingEntryNotesTransferred = Anfix\CompanyAccountingEntryReference::transfer(['AccountingPeriodYear' => 2016, 'CompanyAccountingPeriodRegularizationEntryDate' => 4300004, 'SourceCompanyAccountingAccountNumber' => 43000000, 'CompanyAccountingEntryNote' => [array('AccountingEntryId' => 1, 'AccountingEntryNoteId' => 1, 'CompanyAccountingAccountNumber' => 430000)]], $companyId);
    //print_result('Movimientos transferidos de cuenta',$accountingEntryNotesTransferred);

//7) Cerrar ejercicio
    //$accountingPeriodYearClosed = Anfix\CompanyAccountingPeriod::close(['AccountingPeriodYear' => 2017, 'CompanyAccountingPeriodRegularizationEntryDate' => '31/12/2017', 'CompanyAccountingPeriodOpenEntryDate' => '01/01/2018', 'CompanyAccountingPeriodRegularizationEntryDate' => '31/12/2017'], $companyId);
    //print_result('Ejercicio cerrado',$accountingPeriodYearClosed); 

//8) Crear un ejercicio fiscal a partir de un plan contable determinado
//TO-DO: da un error al interpretar la respuesta
	//$accountingPeriodYear = Anfix\CompanyAccountingPeriod::createwithplan(['AccountingPeriodYear' => 2019,'CompanyAccountingPeriodInitDate' => '01/01/2019', 'CompanyAccountingPeriodEndDate' => '31/12/2019', 'CompanyAccountingPlanId' => '1'], $companyId);
    //print_result('Ejercicio creado',$accountingPeriodYear); 	

//9) Bloquear/Desbloquear un ejercicio
	//$accountingPeriodYear = Anfix\CompanyAccountingPeriod::lockunlock(['AccountingPeriodYear' => 2019,'CompanyAccountingPeriodLocked' => false], $companyId);
    //print_result('Ejercicio bloqueado',$accountingPeriodYear);

//10) Recalcular saldos de un ejercicio contable
	//$accountingPeriodYear = Anfix\CompanyAccountingPeriod::regeneratebalance(2016, $companyId);
    //print_result('Actualización de saldos',$accountingPeriodYear);

//11) Obtención de la plantilla base de parametrización de una empresa    
	//$parametrizationBaseTemplate = Anfix\CompanyParameter::parameterinitialize(['SourceAccountingPlanId' => '1'], $companyId);
    //print_result('Parametrización Base del plan contable Plan General de Contabilidad 2008',$parametrizationBaseTemplate);	
	
//12) Obtención de una cuenta contable para posteriormente modificarla
	//$accountingAccount = Anfix\CompanyAccountingAccount::select(2016, 1000000,$companyId)->get();
    //print_result('Obtención de datos de una cuenta contable',$accountingAccount);

//13) Copia de un predefinido
	$predefinedAccountingEntryCopy = Anfix\PredefinedAccountingEntry::copy(['AccountingPeriodYear' => 2016, 'Action' => 'COPY','CompanyIdSource' => $companyId, 'PredefinedEntriesId' => ['P']],$companyId);
    print_result('Copia de un predefinido',$predefinedAccountingEntryCopy);	

//14) Búsqueda de asientos predefinidos por tipo de asiento
	//$predefinedAccountingEntry = Anfix\PredefinedAccountingEntry::where(['EntryTypeToPredefinedEntryEntryTypeId' => '2'],$companyId)->get([],5,1,[],'','searchbyentrytype',['AccountingPeriodYear' => 2016]);
    /*print_result('Predefinido en base a un tipo de asiento',array_map(function($e){ return array(
	    "PredefinedAccountingEntryCode" => $e->PredefinedAccountingEntryCode,  	    	    
	    "PredefinedAccountingEntryTypeName" => $e->PredefinedAccountingEntryTypeName,
		"PredefinedAccountingEntryDescription" => $e->PredefinedAccountingEntryDescription,    
	    "PredefinedAccountingEntryNote" => $e->PredefinedAccountingEntryNote,
	    "PredefinedAccountingEntryTaxLine" => $e->PredefinedAccountingEntryTaxLine
	); },$predefinedAccountingEntry));*/

//15) Obtención de un predefinido contable para posteriormente modificarlo
//TO-DO: da un error porque la url no es la correcta
	//$predefinedAccountingEntry = Anfix\CompanyAccountingEntryReference::selectPredefined(2016, '1',$companyId)->get();
    /*print_result('Predefinido en base a un tipo de asiento',array_map(function($e){ return array(
	    "PredefinedAccountingEntryCode" => $e->PredefinedAccountingEntryCode,  	    	    
	    "PredefinedAccountingEntryTypeName" => $e->PredefinedAccountingEntryTypeName,
		"PredefinedAccountingEntryDescription" => $e->PredefinedAccountingEntryDescription,    
	    "PredefinedAccountingEntryNote" => $e->PredefinedAccountingEntryNote,
	    "PredefinedAccountingEntryTaxLine" => $e->PredefinedAccountingEntryTaxLine
	); },$predefinedAccountingEntry));*/

//16) Fijar una plantilla por defecto
	//$template = Anfix\Template::setdefault('7', '1', $companyId);
    //print_result('Fijada una plantilla por defecto',$template);

//17) Duplicar un presupuesto
	//$customerBudget = Anfix\CustomerBudget::duplicate(['LQYEKEtJY'],$companyId);
	//print_result('Presupuesto duplicado',$customerBudget);

//18) Duplicar una factura emitida
	//$issuedInvoice = Anfix\IssuedInvoice::duplicate(['LQ:,4qF3A'],$companyId);
	//print_result('Factura emitida duplicada',$issuedInvoice);	

//19) Duplicar una factura recibida    	
	//$receivedInvoice = Anfix\ReceivedInvoice::duplicate(['MbS010Qe8'],$companyId);
	//print_result('Factura recibida duplicada',$receivedInvoice);		




