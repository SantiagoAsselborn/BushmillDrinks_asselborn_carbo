<?php

namespace App\Libraries\Pagos;

class PagoTransferencia implements EstrategiaPago
{

    private string $alias = "bushmilldrinks";
    private string $cbu = "2850590940090418135201";
    private string $titular = "Bushmill Drinks SRL";
    private string $qr = "/img/qr-transferencia.png";


    public function procesarPago(float $total): bool
    {
        return true;
    }

    public function obtenerDatosVista(float $total): array
    {
        return [

            'html' => '

            <div class="card mt-3">

                <div class="card-body">

                    <h5>Transferencia bancaria</h5>

                    <p><strong>Total:</strong> $'.number_format($total,2,',','.').'</p>

                    <p><strong>Alias:</strong> bushmill.drinks</p>

                    <img
                        src="'.base_url('assets/img/qr_transferencia.jpg').'"
                        width="200">

                    <p class="mt-2">
                        Luego de realizar la transferencia
                        envíanos el comprobante.
                    </p>

                </div>

            </div>

            '

        ];
    }
}