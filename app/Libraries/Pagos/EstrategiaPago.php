<?php

namespace App\Libraries\Pagos;

interface EstrategiaPago
{
    public function procesarPago(float $total): bool;
}