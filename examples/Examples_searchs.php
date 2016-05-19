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
 * Catálogo de ejemplos de uso de todos los endpoints de la API de anfix
 * Antes de ejecutar ninguno de estos ejemplso asegúrese que dispone de un token y key válidos previamente generados 
 * según se especifica en la QuickStart de Readme.md
 * Puede ejecutar este fichero como un test general
 */
  
include 'example_utils.php';

//Módulo de Servicios Básicos

//1) Ejemplo de obtención de todas las empresas disponibles para la cuenta por defecto:
    $myCompanies = Anfix\Company::all();
	print_result('Lista de empresas disponibles',array_map(function($e){ return array(
		"CompanyCorporateName" => $e->CompanyCorporateName,    
	    "CompanyCommercialName" => $e->CompanyCommercialName,
	    "CompanyIdentificationNumber" => $e->CompanyIdentificationNumber,
	    "AddressId" => $e->AddressId
	); },$myCompanies));		

$companyId = firstCompanyId(); //Obtención del id de la primera empresa disponible (función únicamente válida para ejemplos)	

//2) Ejemplo de obtención de la dirección de una empresa
    $companyAddress = Anfix\Address::all($companyId);
	print_result('Lista de direcciones de la empresa',array_map(function($e){ return array(
		"AddressText" => $e->AddressText,    
	    "AddressPostalCode" => $e->AddressPostalCode,
	    "AddressCity" => $e->AddressCity,
	    "AddressProvince" => $e->AddressProvince,	    
	    "AddressCountry" => $e->AddressCountry,	    	    
	); },$companyAddress));		

//3) Ejemplo de obtención de los tipos de vía estructurales
    $roadTypesAddress = Anfix\RoadType::all();
	print_result('Lista de tipos de vía',array_map(function($e){ return array(
	    "RoadTypeCode" => $e->RoadTypeCode,
	    "RoadTypeCodeINE" => $e->RoadTypeCodeINE,
	    "RoadTypeName" => $e->RoadTypeName	    
	); },$roadTypesAddress));

//4) Ejemplo de obtención de las cuentas bancarias
    $bankAccounts = Anfix\BankAccount::all($companyId);
	print_result('Lista de tipos de vía',array_map(function($e){ return array(
	    "BankName" => $e->BankName,	    	    
	    "BankAccountEntity" => $e->BankAccountEntity,
	    "BankAccountOffice" => $e->BankAccountOffice,
	    "BankAccountControlDigits" => $e->BankAccountControlDigits,		    
	    "BankAccountNumber" => $e->BankAccountNumber,
	    "BankAccountBIC" => $e->BankAccountBIC,
	    "BankAccountIBAN" => $e->BankAccountIBAN,	    
	    "BankAccountingAccount" => $e->BankAccountingAccount
	); },$bankAccounts));

//5) Ejemplo de obtención de los códigos CNAE asociados a una empresa
    $cnaeList = Anfix\Cnae::all($companyId);
	print_result('Lista de tipos de cnae',array_map(function($e){ return array(
	    "CnaeCode" => $e->CnaeCode,	    	    
	    "CnaeDescription" => $e->CnaeDescription
	); },$cnaeList));

//6) Ejemplo de obtención de datos de ciudad a partir del CP
    $citysByPostalCode = Anfix\City::findByPostalCode('47140', '1');
	print_result('Datos de ciudad a partir del CP',$citysByPostalCode);    

//7) Ejemplo de obtención de códigos CNAE
    $cnaeList = Anfix\Cnae::all($companyId);
	print_result('Lista de tipos de cnae',array_map(function($e){ return array(
	    "CnaeCode" => $e->CnaeCode,	    	    
	    "CnaeDescription" => $e->CnaeDescription
	); },$cnaeList));

//8) Actualización de los datos de la empresa
 	$company = Anfix\Company::first([],$companyId); //Obtenemos el presupuesto con el id indicado o un error si no existe

	$company->CompanyCommercialName = 'Saneamientos Pérez';
	$company->CompanyIdentificationTypeId = '4';
	$company->CompanyIdentificationNumber = '123456789N';
    $company->save(); //Guardado del cliente
	print_result('Actualización de los datos de la empresa',$company->getArray());

//9) Ejemplo de obtención de los datos de contacto de una empresa
    $contacts = Anfix\Contact::all($companyId);
	print_result('Lista de contactos',array_map(function($e){ return array(
	    "ContactEMail" => $e->ContactEMail,	    	    
	    "ContactPersonName" => $e->ContactPersonName,
	    "ContactPhoneNumber" => $e->ContactPhoneNumber,
	    "ContactFaxNumber" => $e->ContactFaxNumber	    	    
	); },$contacts));

//10) Ejemplo de obtención de los datos estructurales de países
    $countries = Anfix\Country::all();
	print_result('Lista de contactos',array_map(function($e){ return array(
	    "CountryName" => $e->CountryName
	); },$countries));		

//11) Ejemplo de obtención de los datos de organización
    $organization = Anfix\Organization::all();
	print_result('Datos de la organización del propietario de la empresa',array_map(function($e){ return array(
	    "OrganizationName" => $e->OrganizationName,
	    "OrganizationEmail" => $e->OrganizationEmail,	    
		"OrganizationTaxIdentification" => $e->OrganizationTaxIdentification	    
	); },$organization));

//12) Ejemplo de obtención de los datos estructurales de provincias
    $provinces = Anfix\Province::all();
	print_result('Lista de provincias',array_map(function($e){ return array(
	    "ProvinceName" => $e->ProvinceName
	); },$provinces));

//13) Ejemplo de obtención de los ficheros de mis documentos
	$myDocuments = Anfix\MyDocuments::where(['OwnerId' => $companyId, 'OwnerTypeId' => '2', 'HumanReadablePathExact' => '/'])->get();
	print_result('Lista de documentos de la empresa',array_map(function($e){ return array(
	    "HumanReadableName" => $e->HumanReadableName,
	    "CreationDate" => $e->CreationDate,
	    "Size" => $e->Size    
	); },$myDocuments));

//14) Ejemplo de obtención de las plantillas de un presupuesto
	$customerBudgetTemplates = Anfix\PrintTemplate::where(['TemplateTypeId' => '5Z'],$companyId)->get([],null,null,[],'ASC','search',['ContextApplicationId'=>'E']);
	print_result('Lista de tipos de plantillas de presupuestos de la empresa',array_map(function($e){ return array(
	    "TemplateName" => $e->TemplateName,
	    "CreationDate" => $e->CreationDate,
	    "Size" => $e->Size    
	); },$customerBudgetTemplates));

//Módulo de Facturación

//15) Ejemplo de obtención de los cobros (da igual el origen de los mismos)
	$charges = Anfix\Charge::where(['ChargeSourceId' => 'LQ:,4qF3A', 'ChargeSourceType' => '2'], $companyId)->get();
	print_result('Lista de cobros (independientemente del origen)',array_map(function($e){ return array(
	    "ChargeDescription" => $e->ChargeDescription,
	    "ChargeAmount" => $e->ChargeAmount,
	    "ChargeDate" => $e->ChargeDate    
	); },$charges));

//16) Ejemplo de obtención de cobros vinculados a facturas emitidas
	$charges = Anfix\Charge::where([],$companyId)->searchforissuedinvoice();
	print_result('Lista de cobros vinculados a facturas emitidas',array_map(function($e){ return array(
	    "ChargeDescription" => $e->ChargeDescription,
	    "ChargeAmount" => $e->ChargeAmount,
	    "ChargeDate" => $e->ChargeDate	    
	); },$charges));

//17) Ejemplo de obtención de los clientes de una empresa
	$customers = Anfix\Customer::all($companyId);
	print_result('Lista de clientes de una empresa',array_map(function($e){ return array(
	    "CustomerName" => $e->CustomerName,
	    "CustomerIdentificationNumber" => $e->CustomerIdentificationNumber	    
	); },$customers));

//18) Ejemplo de obtención de los presupuestos de una empresa
	$customerBudgets = Anfix\CustomerBudget::all($companyId);
	print_result('Lista de presupuestos de una empresa',array_map(function($e){ return array(
	    "CustomerBudgetSerialNum" => $e->CustomerBudgetSerialNum,
	    "CustomerBudgetNumber" => $e->CustomerBudgetNumber,	    
	    "CustomerBudgetDate" => $e->CustomerBudgetDate,
	    "CustomerBudgetTotalValue" => $e->CustomerBudgetTotalValue	    
	); },$customerBudgets));

//19) Ejemplo de obtención de vencimientos de facturas emitidas
	$issuedInvoiceExpirations = Anfix\Expiration::where(['IsInRemittance'=> false],$companyId)->searchforissuedinvoice();
	print_result('Lista de vencimientos no remesados',array_map(function($e){ return array(
	    "ExpirationSerialNum" => $e->ExpirationSerialNum,
	    "ExpirationDocumentNumber" => $e->ExpirationDocumentNumber,
	    "ExpirationDate" => $e->ExpirationDate,
	    "ExpirationQuantity" => $e->ExpirationQuantity    	    
	); },$issuedInvoiceExpirations));	

//20) Ejemplo de obtención de las facturas emitidas de una empresa
	$issuedInvoices = Anfix\IssuedInvoice::all($companyId);
	print_result('Lista de facturas emitidas de una empresa',array_map(function($e){ return array(
	    "IssuedInvoiceSerialNum" => $e->IssuedInvoiceSerialNum,
	    "IssuedInvoiceNumber" => $e->IssuedInvoiceNumber,	    
	    "IssuedInvoiceDate" => $e->IssuedInvoiceDate,
	    "IssuedInvoiceTotalValue" => $e->IssuedInvoiceTotalValue	    
	); },$issuedInvoices));

//21) Ejemplo de obtención de las líneas de las facturas emitidas de una empresa
	//TO-DO: da error el servicio pero es porque esa búsqueda no está permitida? comprobar
	/*$issuedInvoiceLines = Anfix\IssuedInvoiceLine::all($companyId);
	print_result('Lista de las líneas de las facturas emitidas de una empresa',array_map(function($e){ return array(
	    "IssuedInvoiceLineItemRef" => $e->IssuedInvoiceLineItemRef    
	); },$issuedInvoiceLines));*/

//Ejemplo de obtención de cobros pendientes
	$charges = Anfix\IssuedInvoice::where(['IssuedInvoiceStateIdDistinct'=>'3'],$companyId)->searchForCharge();
	print_result('Lista de cobros de una factura emitida',array_map(function($e){ return array(
	    "IssuedInvoiceNumber" => $e->IssuedInvoiceNumber,
	    "IssuedInvoiceDate" => $e->IssuedInvoiceDate,
	    "IssuedInvoiceTotalValue" => $e->IssuedInvoiceTotalValue,
	    "IssuedInvoiceChargedAmount" => $e->IssuedInvoiceChargedAmount    	    
	); },$charges));

//22) Ejemplo de obtención de las formas de pago
	$payChargeMethods = Anfix\PayChargeMethod::all($companyId);
	print_result('Lista de formas de pago de una empresa',array_map(function($e){ return array(
	    "PayChargeMethodCode" => $e->PayChargeMethodCode,
	    "PayChargeMethodName" => $e->PayChargeMethodName,	    
	    "PayChargeMethodEInvoiceCodeValue" => $e->PayChargeMethodEInvoiceCodeValue
	); },$payChargeMethods));

//23) Ejemplo de obtención de los pagos (da igual el origen de los mismos)
	$payments = Anfix\Payment::where([], $companyId)->get();
	print_result('Lista de pagos (independientemente del origen)',array_map(function($e){ return array(
	    "PaymentDescription" => $e->PaymentDescription,
	    "PaymentAmount" => $e->PaymentAmount,
	    "PaymentDate" => $e->PaymentDate    
	); },$payments));

//24) Ejemplo de obtención de las facturas recibidas de una empresa
	$receivedInvoices = Anfix\ReceivedInvoice::all($companyId);
	print_result('Lista de facturas recibidas de una empresa',array_map(function($e){ return array(
	    "ReceivedInvoiceSerialNum" => $e->ReceivedInvoiceSerialNum,
	    "ReceivedInvoiceNumber" => $e->ReceivedInvoiceNumber,	    
	    "ReceivedInvoiceDate" => $e->ReceivedInvoiceDate,
	    "ReceivedInvoiceTotalValue" => $e->ReceivedInvoiceTotalValue	    
	); },$receivedInvoices));

//25) Ejemplo de obtención de pagos pendientes
	$receivedInvoices = Anfix\ReceivedInvoice::where([],$companyId)->searchForPayment();
	print_result('Lista de pagos de una factura recibida',array_map(function($e){ return array(
	    "ReceivedInvoiceNumber" => $e->ReceivedInvoiceNumber,
	    "ReceivedInvoiceDate" => $e->ReceivedInvoiceDate,
	    "ReceivedInvoiceTotalValue" => $e->ReceivedInvoiceTotalValue,
	    "ReceivedInvoicePaidAmount" => $e->ReceivedInvoicePaidAmount    	    
	); },$receivedInvoices));

//26) Ejemplo de obtención de las facturas recurrentes de una empresa
	$recurringInvoices = Anfix\RecurringInvoice::all($companyId);
	print_result('Lista de facturas recurrentes de una empresa',array_map(function($e){ return array(
	    "RecurringInvoiceSerialNum" => $e->ReceivedInvoiceSerialNum,
	    "RecurringInvoiceStartDate" => $e->RecurringInvoiceStartDate,
	    "RecurringInvoiceEndDate" => $e->RecurringInvoiceEndDate,	    
	    "RecurringInvoiceTotalValue" => $e->RecurringInvoiceTotalValue	    
	); },$recurringInvoices));

//27) Ejemplo de obtención de los proveedores de una empresa
	$suppliers = Anfix\Supplier::all($companyId);
	print_result('Lista de proveedores de una empresa',array_map(function($e){ return array(
	    "SupplierFiscalName" => $e->SupplierFiscalName,
	    "SupplierIdentificationNumber" => $e->SupplierIdentificationNumber	    
	); },$suppliers));

//28) Ejemplo de obtención de los impuestos de una empresa
	$taxValues = Anfix\TaxValue::all($companyId);
	print_result('Lista de impuestos de una empresa',array_map(function($e){ return array(
	    "VatValue" => $e->VatValue,
	    "VatEsValue" => $e->VatEsValue,	    
	    "VatClassLabel" => $e->VatClassLabel,	    	    
	    "VatInitDate" => $e->VatInitDate,	    
	    "VatEndDate" => $e->VatEndDate    	    
	); },$taxValues));

//29)Ejemplo de obtención de las gráficas de tesorería
	$treasuryData = Anfix\TreasuryDataGraph::search(['CheckExpenses'=> true, 'CheckRevenues'=> true, 'CheckTreasury' => true],$companyId);
	print_result('Datos de tesorería de la empresa',$treasuryData);	

//Módulo de Contabilidad

//30) Ejemplo de obtención de las Cuentas Contables de un plan general contable
	$accountingAccounts = Anfix\AccountingAccount::where(['AccountingPlanId'=>'1'],$companyId)->get();	
	print_result('Lista de cuentas contables de una empresa',array_map(function($e){ return array(
	    "AccountingAccountNumber" => $e->AccountingAccountNumber,
	    "AccountingAccountDescription" => $e->AccountingAccountDescription
	); },$accountingAccounts));

//31) Ejemplo de obtención de los asientos de una empresa y un ejercicio fiscal
	$accountingEntryReferences = Anfix\CompanyAccountingEntryReference::where([],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016]);
	print_result('Lista de asientos de una empresa',array_map(function($e){ return array(
	    "AccountingEntryDate" => $e->AccountingEntryDate,	    
	    "PredefinedAccountingEntryDescription" => $e->PredefinedAccountingEntryDescription,	    	    
	    "CompanyAccountingEntryNote" => $e->CompanyAccountingEntryNote,
	    "Invoice" => $e->Invoice
	); },$accountingEntryReferences));

//32) Ejemplo de obtención de los apuntes de una empresa y un ejercicio fiscal
	$accountingEntryNotes = Anfix\CompanyAccountingEntryNote::where([],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016]);
	print_result('Lista de apuntes de una empresa',array_map(function($e){ return array(
	    "AccountingEntryDate" => $e->AccountingEntryDate,	    
	    "AccountingEntryDocumentDescription" => $e->AccountingEntryDocumentDescription,	    	    
	    "CompanyAccountingAccountNumber" => $e->CompanyAccountingAccountNumber,
	    "CompanyAccountingAccountNumberBalance" => $e->CompanyAccountingAccountNumberBalance
	); },$accountingEntryNotes));

//33) Ejemplo de obtención de los ejercicios de una empresa
	$accountingPeriodYears = Anfix\CompanyAccountingPeriod::where([],$companyId)->get();
	print_result('Lista de ejercicios de una empresa',array_map(function($e){ return array(
	    "AccountingPeriodYear" => $e->AccountingPeriodYear,	    
	    "AccountingPlanName" => $e->AccountingPlanName,	    	    
	    "CompanyAccountingPeriodInitDate" => $e->CompanyAccountingPeriodInitDate,
	    "CompanyAccountingPeriodEndDate" => $e->CompanyAccountingPeriodEndDate
	); },$accountingPeriodYears));

//34) Ejemplo de obtención de planes contables existentes
	$accountingPlans = Anfix\AccountingPlan::all($companyId);
	print_result('Lista de planes contables oficiales y sus cuentas',array_map(function($e){ return array(
	    "AccountingPlanName" => $e->AccountingPlanName,	    
	    "AccountingPlanDescription" => $e->AccountingPlanDescription,	    	    
	    "AccountingPeriodYear" => $e->AccountingPeriodYear
	); },$accountingPeriodYears));


//35) Ejemplo de obtención de parámetros base de un plan contable
	$accountingPlanParameterBase = Anfix\AccountingPlanParameterBase::where(['AccountingPlanParameterBaseAccountingPlanId'=> '1'],$companyId)->get();
	print_result('Plantilla de parametrización base de un plan contable',$accountingPlanParameterBase);

//36) Ejemplo de obtención de datos de actividad de una empresa
	$activities = Anfix\Activity::all($companyId);
	print_result('Lista de actividades',array_map(function($e){ return array(
	    "EconomicActivityCode" => $e->EconomicActivityCode,	    	    
	    "EconomicActivityName" => $e->EconomicActivityName
	); },$activities));

//37) Ejemplo de obtención de cuentas contables de banco de una empresa
	$bankAccounts = Anfix\BankAccount::all($companyId);
	print_result('Lista de cuentas contables de banco',array_map(function($e){ return array(
	    "BankAccountingAccount" => $e->BankAccountingAccount,	    	    
	    "BankAccountIBAN" => $e->BankAccountIBAN
	); },$bankAccounts));

//38) Ejemplo de obtención de cuentas contables de una empresa
	$companyAccountingAccounts = Anfix\CompanyAccountingAccount::where([],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016]);
	print_result('Lista de cuentas contables',array_map(function($e){ return array(
	    "CompanyAccountingAccount" => $e->CompanyAccountingAccount
	); },$companyAccountingAccounts));

//39) Ejemplo de obtención de retenciones de una empresa
	$deductionValues = Anfix\DeductionValue::all($companyId);
	print_result('Lista de retenciones',array_map(function($e){ return array(
	    "DeductionValueValue" => $e->DeductionValueValue,
	    "DeductionValueInitDate" => $e->DeductionValueInitDate,	    
	    "DeductionValueEndDate" => $e->DeductionValueEndDate,	    
	    "DeductionValueName" => $e->DeductionValueName,
	    "DeductionTypeValue" => $e->DeductionTypeValue,	        	    
	); },$deductionValues));

//40) Ejemplo de obtención de las cuentas anuales de una empresa
	$deferments = Anfix\Deferment::where([],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016]);
	print_result('Lista de cuentas anuales',array_map(function($e){ return array(
	    "DefermentTotalValue" => $e->DefermentTotalValue,
	    "DefermentOutsideLegalDeadlineValue" => $e->DefermentOutsideLegalDeadlineValue,	    
	    "DefermentInsideLegalDeadlinePercentage" => $e->DefermentInsideLegalDeadlinePercentage,	    
	    "DefermentPMEDays" => $e->DefermentPMEDays,
	    "DefermentInsideLegalDeadlineValue" => $e->DefermentInsideLegalDeadlineValue,	        	    
	); },$deferments));

//41) Ejemplo de obtención de las actividades económicas estructurales
	$economicActivities = Anfix\EconomicActivity::all($companyId);
	print_result('Lista de cuentas anuales',array_map(function($e){ return array(
	    "EconomicActivityCode" => $e->EconomicActivityCode,	    	    
	    "EconomicActivityName" => $e->EconomicActivityName        	    
	); },$economicActivities));	

//42) Ejemplo de obtención de las facturas de una emprsa
	$invoices = Anfix\Invoice::where([],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016]);
	print_result('Lista de facturas',array_map(function($e){ return array(
	    "InvoiceDate" => $e->InvoiceDate,	    	    
	    "InvoiceSerialNum" => $e->InvoiceSerialNum,
	    "InvoiceType" => $e->InvoiceType,	    	    	    
		"InvoiceTotalValue" => $e->InvoiceTotalValue,	    
	    "InvoiceCustomerSupplierAccountingAccountNumber" => $e->InvoiceCustomerSupplierAccountingAccountNumber,	    
	    "InvoiceLine" => $e->InvoiceLine,
	    "CashFlow" => $e->CashFlow	           	    
	); },$invoices));

//43) Ejemplo de obtención de las personas jurídicas de una empresa
	$legalPerson = Anfix\LegalPerson::all($companyId);
	print_result('Lista de personas jurídicas de la empresa',array_map(function($e){ return array(
	    "LegalPersonAttorneyName" => $e->LegalPersonAttorneyName,	    	    
	    "LegalPersonIdentificationNumber" => $e->LegalPersonIdentificationNumber
	); },$legalPerson));

//44) Ejemplo de obtención de las claves de operación
	$operationKeys = Anfix\OperationKey::where(['OperationKeyDate'=> '01/01/2016', 'OperationKeyInvoiceType'=>1],$companyId)->get();
	print_result('Lista de claves de operación',array_map(function($e){ return array(
	    "OperationKeyCode" => $e->OperationKeyCode,	    	    
	    "OperationKeyStartDate" => $e->OperationKeyStartDate,
	    "OperationKeyDescription" => $e->OperationKeyDescription	    
	); },$operationKeys));

//45) Ejemplo de obtención de los tipos de operación
	$operationTypes = Anfix\OperationType::where(['OperationTypeDate'=> '01/01/2016', 'OperationTypeOwnerTypeId'=> '2', 'OperationTypeAccountingEntryTypeId' => '2'],$companyId)->get();
	print_result('Lista de tipos de operación',array_map(function($e){ return array(
	    "OperationTypeCode" => $e->OperationTypeCode,	    	    
	    "OperationTypeDescription" => $e->OperationTypeDescription	    
	); },$operationTypes));

//46) Ejemplo de obtención de los socios de una empresa
	$partners = Anfix\Partner::all($companyId);
	print_result('Lista de partners de una empresa',array_map(function($e){ return array(
	    "PartnerName" => $e->PartnerName,	    	    
	    "PartnerIdentificationNumber" => $e->PartnerIdentificationNumber,
	    "PartnerShares" => $e->PartnerShares,	    
	    "PartnerPercentage" => $e->PartnerPercentage	    
	); },$partners));

//47) Ejemplo de obtención de predefinidos de una empresa
	$predefinedAccountingEntries = Anfix\PredefinedAccountingEntry::where([],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016, 'IncludeStructuralPredefinedAccountingEntries' => true]);
	print_result('Lista de predefinidos de una empresa',array_map(function($e){ return array(
	    "PredefinedAccountingEntryCode" => $e->PredefinedAccountingEntryCode,	    	    
	    "PredefinedAccountingEntryDescription" => $e->PredefinedAccountingEntryDescription,
	    "PredefinedAccountingEntryTypeName" => $e->PredefinedAccountingEntryTypeName
	); },$predefinedAccountingEntries));

//48) Ejemplo de obtención de predefinidos por tipo de asiento de una empresa
	$predefinedAccountingEntries = Anfix\PredefinedAccountingEntry::where(['PredefinedAccountingEntryTypeId'=> '1'],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016, 'IncludeStructuralPredefinedAccountingEntries' => true]);
	print_result('Lista de predefinidos por tipo de asiento de una empresa',array_map(function($e){ return array(
	    "PredefinedAccountingEntryCode" => $e->PredefinedAccountingEntryCode,	    	    
	    "PredefinedAccountingEntryDescription" => $e->PredefinedAccountingEntryDescription,
	    "PredefinedAccountingEntryTypeName" => $e->PredefinedAccountingEntryTypeName
	); },$predefinedAccountingEntries));

//49) Ejemplo de obtención de empleados asalariados en cuentas anuales de una empresa
	$salariedEmployees = Anfix\SalariedEmployee::where([],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016, 'IncludeStructuralPredefinedAccountingEntries' => true]);
	print_result('Lista de empleados asalariados en cuentas anuales de una empresa',array_map(function($e){ return array(
	    "AccountingPeriodYear" => $e->AccountingPeriodYear,	    	    
	    "SalariedEmployeeTotalFixedFemale" => $e->SalariedEmployeeTotalFixedFemale,
	    "SalariedEmployeeTotalFixedMale" => $e->SalariedEmployeeTotalFixedMale
	); },$salariedEmployees));	

//50) Ejemplo de obtención de los tipos impositivos de una empresa
	$vats = Anfix\Vat::all($companyId);
	print_result('Lista de tipos impositivos de una empresa',array_map(function($e){ return array(
	    "VatValue" => $e->VatValue,
	    "VatEsValue" => $e->VatEsValue,	    
	    "VatClassLabel" => $e->VatClassLabel,	    	    
	    "VatInitDate" => $e->VatInitDate,	    
	    "VatEndDate" => $e->VatEndDate  
	); },$vats));

//51) Ejemplo de obtención de plantillas
	$templates = Anfix\Template::where([],$companyId)->get([],5,1,[],'','search',['AccountingPeriodYear' => 2016]);
	print_result('Lista de plantillas de Balances y PyGs de una empresa',array_map(function($e){ return array(
	    "TemplateName" => $e->TemplateName,	    	    
	    "TemplateTypeName" => $e->TemplateTypeName
	); },$templates));
