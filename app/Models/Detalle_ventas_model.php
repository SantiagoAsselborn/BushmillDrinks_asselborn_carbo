<?php

namespace App\Models;

use CodeIgniter\Model;

class Detalle_ventas_model extends Model
{
    protected $table = 'detalle_ventas';
    protected $primaryKey = 'id_detalle';

    protected $useAutoIncrement = true;
    protected $returnType = 'array'; 
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_venta', 'id_bebida', 'detalle_cantidad', 'detalle_precio'];

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
