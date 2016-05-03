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

//1) Generación de un preview de un presupuesto
//TO-DO: está usando una url que no es correcta
    $preview = Anfix\CustomerBudget::preview(['CustomerBudgetSerialNum' => 'P2016', 'CustomerBudgetTemplateId' => '65', 'CustomerBudgetTemplateLanguage' => 1],$companyId);
    print_result('Documento temporal creado',$preview);

//2) Generación de un preview de una factura emitida
//TO-DO: está usando una url que no es correcta
    $preview = Anfix\IssuedInvoice::preview(['IssuedInvoiceSerialNum' => 'F2016', 'IssuedInvoiceTemplateId' => '5Y', 'IssuedInvoiceTemplateLanguage' => 1],$companyId);
    print_result('Documento temporal creado', $preview);

//3) Listado del Plan Contable
    $accountingAccountPlan = Anfix\CompanyAccountingAccount::where(['CompanyAccountingAccountLevelInclude' => [1, 2, 3, 4, 5, 6]], $companyId)->accountPrint(['AccountingPeriodYear' => 2016, 'HierarchyLevel' => true, 'ReportFormat' => 'PDF', 'ShowAccountsWithoutEntries' => true, 'ShowBalance' =>true], [],null ,null ,['CompanyAccountingAccountNumber'],['ASC']);
    print_result('Plan Contable', $accountingAccountPlan);  

//4) Libro Mayor
    $ledger = Anfix\Ledger::report(['AccountingEntryInitDate' => '01/01/2016', 'AccountingEntryEndDate' => '31/12/2016', 'AccountingPeriodYear' => 2016, 'CompanyAccountingInitNumber' => 1000000, 'CompanyAccountingEndNumber' => 9999999, 'FlagDisplayNullItems' => false, 'FlagIncludeAccountWithNoActivity' => false, 'FlagIncludeAccumulatedBalance' => false, 'FlagIncludeClosureEntries' => true, 'FlagIncludeDottedEntries' => true, 'FlagIncludeInternalEntryNumber' => true, 'FlagIncludeNotDottedEntries' => true, 'FlagIncludeOpeningEntries' => false, 'FlagIncludePageBreak' => false], $companyId);
    print_result('Libro mayor', $ledger);

    $ledger = Anfix\Ledger::report(['LedgerFormat' => 'PDF', 'AccountingEntryInitDate' => '01/01/2016', 'AccountingEntryEndDate' => '31/12/2016', 'AccountingPeriodYear' => 2016, 'CompanyAccountingInitNumber' => 1000000, 'CompanyAccountingEndNumber' => 9999999, 'FlagDisplayNullItems' => false, 'FlagIncludeAccountWithNoActivity' => false, 'FlagIncludeAccumulatedBalance' => false, 'FlagIncludeClosureEntries' => true, 'FlagIncludeDottedEntries' => true, 'FlagIncludeInternalEntryNumber' => true, 'FlagIncludeNotDottedEntries' => true, 'FlagIncludeOpeningEntries' => false, 'FlagIncludePageBreak' => false], $companyId);
    print_result('Libro mayor', $ledger);

//5) Libro Diario
    $journal = Anfix\Journal::report(['AccountingEntryInitDate' => '01/01/2016', 'AccountingEntryEndDate' => '31/12/2016', 'AccountingPeriodYear' => 2016, 'FlagIncludeAccountDescription' => true, 'JournalTypeId' => '1'], $companyId);
    print_result('Libro diario', $journal);
    
    $journal = Anfix\Journal::report(['JournalFormat' => 'PDF', 'AccountingEntryInitDate' => '01/01/2016', 'AccountingEntryEndDate' => '31/12/2016', 'AccountingPeriodYear' => 2016, 'FlagIncludeAccountDescription' => true, 'JournalTypeId' => '1'], $companyId);
    print_result('Libro diario', $journal);

//6) Sumas y Saldos
    $trialBalance = Anfix\TrialBalance::report(['AccountingEntryInitDate' => '01/01/2016', 'AccountingEntryEndDate' => '31/12/2016', 'AccountingPeriodYear' => 2016, 'CompanyAccountingInitNumber' => 1000000, 'CompanyAccountingEndNumber' => 9999999, 'FlagDisplayNullItems' => false, 'FlagIncludeClosureEntries' => false, 'FlagIncludeOpeningEntries' => true, 'HierarchyLevel' => true, 'CompanyAccountingAccountLevelInclude' => ['1', '2', '3', '4', '5', '6']], $companyId);
    print_result('Sumas y Saldos', $trialBalance);

    $trialBalance = Anfix\TrialBalance::report(['TrialBalanceFormat' => 'PDF','AccountingEntryInitDate' => '01/01/2016', 'AccountingEntryEndDate' => '31/12/2016', 'AccountingPeriodYear' => 2016, 'CompanyAccountingInitNumber' => 1000000, 'CompanyAccountingEndNumber' => 9999999, 'FlagDisplayNullItems' => false, 'FlagIncludeClosureEntries' => false, 'FlagIncludeOpeningEntries' => true, 'HierarchyLevel' => true, 'CompanyAccountingAccountLevelInclude' => ['1', '2', '3', '4', '5', '6']], $companyId);
    print_result('Sumas y Saldos', $trialBalance);

//7) Balance
    $balance = Anfix\Sheet::balance(['AccountingEntryInitMonth' => 1, 'AccountingEntryEndMonth' => 12, 'AccountingPeriodYear' => 2016, 'FlagDisplayNullItems' => false, 'SheetFormat' => 'SHEET_PDF', 'TemplateId' => '8'], $companyId);
    print_result('Balance', $balance);

//8) PyG
    $profitLost = Anfix\Sheet::balance(['AccountingEntryInitMonth' => 1, 'AccountingEntryEndMonth' => 12, 'AccountingPeriodYear' => 2016, 'FlagDisplayNullItems' => false, 'SheetFormat' => 'SHEET_PDF', 'TemplateId' => 'n'], $companyId);
    print_result('PyG', $profitLost); 

//9) Listado de IVA Repercutido
    $issuedInvoiceReport = Anfix\Invoice::reportIssued(['IsVatListIssuedInvoice' => true, 'AccountingPeriodYear' => 2016], $companyId);
    print_result('Listado de IVA Repercutido', $issuedInvoiceReport);

    $issuedInvoiceReport = Anfix\Invoice::reportIssued(['IsVatListIssuedInvoice' => true, 'AccountingPeriodYear' => 2016, 'ReportFormat' => 'PDF'], $companyId);
    print_result('Listado de IVA Repercutido', $issuedInvoiceReport);    

//10) Listado de IVA Soportado
    $receivedInvoiceReport = Anfix\Invoice::reportReceived(['InvoiceIncludeCapitalAssets' => 1, 'AccountingPeriodYear' => 2016], $companyId);
    print_result('Listado de IVA Soportado', $receivedInvoiceReport); 

    $receivedInvoiceReport = Anfix\Invoice::reportReceived(['InvoiceIncludeCapitalAssets' => 1, 'AccountingPeriodYear' => 2016, 'ReportFormat' => 'PDF'], $companyId);
    print_result('Listado de IVA Soportado', $receivedInvoiceReport);   

//11) efactura
    //TO-DO: cómo lo genero?
    //$eInvoice = Anfix\IssuedInvoice::generateEInvoice();
    //print_result('Documento temporal creado', $eInvoice);
