<?php

namespace App\Libraries\Pagos;

class PagoTransferencia implements EstrategiaPago
{
    public function procesarPago(float $total): bool
    {
        return true;
    }
}