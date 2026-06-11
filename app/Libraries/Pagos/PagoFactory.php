<?php

namespace App\Libraries\Pagos;

class PagoFactory
{
    public static function crear(string $medioPago): EstrategiaPago
    {
        return match (strtolower($medioPago)) {
            'efectivo' => new PagoEfectivo(),
            'transferencia' => new PagoTransferencia(),
            'tarjeta' => new PagoTarjeta(),
            default => new PagoEfectivo(),
        };
    }
}