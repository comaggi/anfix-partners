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

/**
 * Métodos especiales de la entidad CompanyAccountingEntryReference
 * Los métodos search, create, update y delete son similares a todas las demás entidades
 */
  
include 'example_utils.php';  

$companyId = firstCompanyId(); //Obtención del id de la primera empresa disponible (función únicamente válida para ejemplos)

//Completar asiento contable a partir de asiento predefinido.
Anfix\CompanyAccountingEntryReference::completepredefined(array $params, $companyId);

//Traslado de asientos contables entre cuentas.
Anfix\CompanyAccountingEntryReference::transfer(array $params, $companyId);

//Selección de datos de asientos
Anfix\CompanyAccountingEntryReference::select(array $params, $companyId);