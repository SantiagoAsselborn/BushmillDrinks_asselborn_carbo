<?php

namespace App\Libraries\Pagos;

class PagoTarjeta implements EstrategiaPago
{

    private array $bancos = [
        "BBVA",
        "Santander",
        "Galicia",
        "Macro",
        "Provincia",
        "Nación"
    ];

    private array $cuotas = [
        1 => 0,
        3 => 5,
        6 => 10,
        12 => 20
    ];

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

                    <h5>Pago con tarjeta</h5>

                    <div class="mb-3">

                        <label>Banco</label>

                        <select class="form-select">

                            <option>Banco Nación</option>
                            <option>Banco Galicia</option>
                            <option>Banco Macro</option>
                            <option>BBVA</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Cuotas</label>

                        <select class="form-select">

                            <option>1 cuota</option>
                            <option>3 cuotas</option>
                            <option>6 cuotas</option>
                            <option>12 cuotas</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Número de tarjeta</label>

                        <input
                            type="text"
                            class="form-control">

                    </div>

                    <div class="row">

                        <div class="col">

                            <label>Vencimiento</label>

                            <input
                                type="text"
                                class="form-control">

                        </div>

                        <div class="col">

                            <label>CVV</label>

                            <input
                                type="password"
                                class="form-control">

                        </div>

                    </div>

                </div>

            </div>

            '

        ];
    }
}