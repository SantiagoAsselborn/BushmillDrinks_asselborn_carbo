<?php

namespace App\Models;

use CodeIgniter\Model;

class Medio_pago_model extends Model
{
    protected $table = 'medio_pago';
    protected $primaryKey = 'id_medio_pago';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'nombre_medio_pago',
        'estado_medio_pago'
    ];
}