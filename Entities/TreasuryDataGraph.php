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

class TreasuryDataGraph
{
    protected $applicationId = 'E';
	protected $Model = 'NextNumberFromSerial';
	protected $apiBaseUrl = 'http://apps.anfix.com/facturapro-simple/gestiona/simple/treasury/search';
	
	/**
	 * Devuelve el siguiente número disponible para un tipo de entidad
	 * @param array $params Debe contener CheckExpenses, CheckRevenues, CheckTreasury obligatoriamente
	 * @param string $companyId Id de empresa
	 */
	public static function compute(array $params, $companyId){

	    $result = Anfix::sendRequest($this->apiBaseUrl,[
            'applicationId' =>  $this->applicationId,
            'companyId' => $companyId,
            'inputBusinessData' => [
                $this->Model => $params
            ]
        ]);

        if(empty($result->outputData->{$this->Model}))
            return false;

        return $result->outputData->{$this->Model};
	}
}