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
 * Catálogo de ejemplos básicos de uso
 * Antes de ejecutar ninguno de estos ejemplso asegúrese que dispone de un token y key válidos previamente generados 
 * según se especifica en la QuickStart de Readme.md
 * Puede ejecutar este fichero como un test general
 */
  
//Ejemplo de obtención de todas las empresas disponibles para la cuenta por defecto:
    $myEnterprises = Anfix\Enterprises::all();
	print_result('Lista de empresas disponibles',$myEnterprises);

//Obtención del identificador de la primera empresa disponible o error
	$companyId = Anfix\Enterprises::firstOrFail()->CompanyId;
	print_result('CompanyId de la primera empresa disponible',$companyId);
	
//Ejemplo de obtencion del primer cliente con nombre Anfix o creación del mismo si no existe y modificación del mismo
	$customer = Anfix\Customer::firstOrNew(['CustomerName' => 'Anfix'],$companyId);
	$customer->CustomerCode = '2222';
	$customer->CustomerIdentificationTypeId = '6';
	$customer->CustomerIdentificationNumber = '123456789N';
    $customer->save(); //Guardado del cliente
	print_result('Creación del cliente Anfix',$customer);
	
//Modificación del cliente
	$customer = Anfix\Customer::firstOrFail(['CustomerName' => 'Anfix'],$companyId);
	$customer->CustomerIdentificationNumber = '987654321N';	
    $customer->save(); //Guardado del cliente
	print_result('Actualización del nif cliente Anfix',$customer);
	
//Borrado del cliente
	$customer = Anfix\Customer::firstOrFail(['CustomerName' => 'Anfix'],$companyId);
    $customer->delete();
  
  
  
  
//Función auxiliar para imprimir contenido en la pantalla
function print_result($title,$value){
	echo "<div style='color: #6d6d6d; background-color: #eee; font-family: Arial; padding: 5px 0 5px 10px; margin: 5px 0 15px;'>
	<b style='color:#007acc;'>$title</b><pre>".print_r($value,true).'</pre></div>';
}  