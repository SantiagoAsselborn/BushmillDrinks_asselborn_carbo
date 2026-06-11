<?php

namespace App\Models;

use CodeIgniter\Model;

class Ventas_model extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id_venta';

    protected $useAutoIncrement = true;
    protected $returnType = 'array'; 
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_usuario', 'id_medio_pago', 'fecha_venta', 'total_venta'];

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
