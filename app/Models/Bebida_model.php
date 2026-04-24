<?php

namespace App\Models;

use CodeIgniter\Model;

class Bebida_model extends Model
{
    protected $table = 'bebida';
    protected $primaryKey = 'id_bebida';

    protected $useAutoIncrement = true;
    protected $returnType = 'array'; 
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_bebida', 'descripcion_bebida', 'precio_bebida', 'stock_bebida', 'imagen_bebida', 'volumen_bebida', 'grado_bebida', 'estado_bebida', 'id_categoria', 'id_marca'];

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
