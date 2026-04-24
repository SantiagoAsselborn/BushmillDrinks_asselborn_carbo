<?php

namespace App\Models;

use CodeIgniter\Model;

class Promocion_model extends Model
{
    protected $table = 'promocion';
    protected $primaryKey = 'id_promocion';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_bebida', 'tipo_promocion', 'valor_promocion', 'fecha_inicio', 'fecha_fin', 'estado_promocion'];

    protected $useTimestamps = false;
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}