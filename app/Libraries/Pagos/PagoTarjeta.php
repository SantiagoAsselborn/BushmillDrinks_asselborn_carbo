<?php

namespace App\Libraries\Pagos;

class PagoTarjeta implements EstrategiaPago
{
    public function procesarPago(float $total): bool
    {
        return true;
    }
}