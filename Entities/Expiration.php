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

class Expiration extends BaseModel
{
    protected $applicationId = 'E';
    protected $update = true;
    protected $create = true;
    protected $delete = true;

    /**
     * Cálculo de vencimientos según una forma de cobro.
     * @param $params array de parámetros, ExpirationsPayChargeMethodId, ExpirationsQuantity y ExpirationsStartDate obligatorios
     * @param $companyId
     * @return Object
     */
    public static function compute(array $params,$companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send($params,$companyId,'compute');
        return $result->outputData->{$obj->Model};
    }

    /**
     * Consulta de vencimientos asociados a facturas emitidas.
     * Esta función se utiliza como get, normalmente después de ::where para establecer el filtrado
     * @param array $fields Campos a obtener
     * @param int $maxRows = null Máximo de filas a mostrar, si no se indica se devolverán 50
     * @param null $minRowNumber Primera entrada a devolver como resultado del conjunto total de entradas devueltas por la operación
     * @param array $order Lista con los campos por los que se quiere ordenar los resultados
     * @param string $orderTypes ”ASC” o ”DESC”
     * @return array
     */
    public function searchforissuedinvoice(array $fields = [], $maxRows = null, $minRowNumber = null, array $order = [], $orderTypes = 'ASC'){
        return parent::get($fields, $maxRows, $minRowNumber, $order, $orderTypes, 'search-WithIssuedInvoiceAction');
    }
}