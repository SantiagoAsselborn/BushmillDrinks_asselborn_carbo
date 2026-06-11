<?php

namespace App\Libraries\Pagos;

class PagoEfectivo implements EstrategiaPago
{
    public function procesarPago(float $total): bool
    {
        return true;
    }
}