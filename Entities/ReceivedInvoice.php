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

class ReceivedInvoice extends BaseModel
{
    protected $applicationId = 'E';
    protected $update = true;
    protected $create = true;
    protected $delete = true;

    /**
     * Duplicación de una factura recibida.
     * @param string $receivedInvoiceIds Identificadores únicos de las facturas recibidas a duplicar
     * @param string $companyId
     * @return Object
     */
    public static function duplicate($receivedInvoiceIds,$companyId){
        $obj = new static([],false,$companyId);
        $result = self::_send(['ReceivedInvoiceIds' => $receivedInvoiceIds],$companyId,'duplicate');
        return $result->outputData->{$obj->Model};
    }


    /**
     * Consulta de pagos de una factura emitida.
     * Esta función se utiliza como get, normalmente después de ::where para establecer el filtrado
     * @param array $fields Campos a devolver
     * @param int $maxRows = null Máximo de filas a mostrar, si no se indica se devolverán 50
     * @param null $minRowNumber Primera entrada a devolver como resultado del conjunto total de entradas devueltas por la operación
     * @param array $order Lista con los campos por los que se quiere ordenar los resultados
     * @param string $orderTypes ”ASC” o ”DESC”
     * @return array
     */
    public function searchForPayment(array $fields = [],$maxRows = null, $minRowNumber = null, array $order = [], $orderTypes = 'ASC'){
        return parent::get($fields, $maxRows, $minRowNumber, $order, $orderTypes, 'searchForPayment', [], str_replace('servicios/','simple/',$this->apiBaseUrl));
    }

    /**
     * Crea un cobro de una factura
     * @param string $date
     * @param float $amount
     * @throws Exceptions\AnfixException
     * @throws Exceptions\AnfixResponseException
     * @return Payment
     */
    public function createPayment($date, $amount){
        $formapago = $this->IssuedInvoicePayChargeMethodId == 'c' ? 'Domiciliación' : 'Tarjeta';
        return Payment::create([
            'PaymentSourceId' => $this->{$this->primaryKey},
            'PaymentSourceType' => '1', //2 Fra recibida
            'PaymentDate' => $date,
            'PaymentDescription' => 'Pago de la factura '.$this->ReceivedInvoiceRefNumber,
            'PaymentAmount' => $amount > 0 ? $amount : $amount * -1,
            'PaymentComments' => 'Pago mediante '.$formapago,
            'PaymentIsRefund' => $amount < 0
        ], $this->companyId);
    }
    
    /**
     * Exporta una serie de facturas a contabilidad
     *
     * @param array $params [AccountingPeriodYear, IssuedInvoiceInitNumber, IssuedInvoiceEndNumber, IssuedInvoiceSerialNum ]
     * @param bool $ExportPayment
     * @param string $companyId
     * @return object
     */
    public static function exportMultiple(array $params, $ExportPayment, $companyId){
        $obj = new static([],false,$companyId);
        $params['ExportPayment'] =  $ExportPayment;
        $result =  parent::_send($params, $companyId,'synchronization/export');

        return $result->outputData->{$obj->Model};
    }

    /**
     * Exporta la factura a contabilidad
     * @param bool $ExportPayment
     * @throws Exceptions\AnfixException
     * @return object
     */
    public function export($AccountingPeriodYear,$ExportPayment){
        if(! $this->IssuedInvoiceNumber)
            throw new AnfixException('Para exportar una factura debe partir de una factura ya registrada en anfix');

        return self::exportMultiple(['IssuedInvoiceInitNumber' => $this->IssuedInvoiceNumber, 'IssuedInvoiceEndNumber' => $this->IssuedInvoiceNumber, 'IssuedInvoiceSerialNum' => $this->IssuedInvoiceSerialNum],$ExportPayment,$this->companyId);
    }
}
