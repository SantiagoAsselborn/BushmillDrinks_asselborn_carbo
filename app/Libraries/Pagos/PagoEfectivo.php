<?php

namespace App\Libraries\Pagos;

class PagoEfectivo implements EstrategiaPago
{

    public function obtenerDatosVista(float $total): array
    {
        return [

            'html' => '
                <div class="alert alert-success mt-3">
                    <h5>Pago en efectivo</h5>
                    <p>Total a pagar:</p>
                    <h3>$'.number_format($total,2,',','.').'</h3>
                    <small>El pago se realizará al recibir el pedido.</small>
                </div>
            '

        ];
    }

    public function procesarPago(float $total): bool
    {
        return true;
    }
}