<?php

namespace App\Models;

use CodeIgniter\Model;

class Direccion_model extends Model
{
    protected $table = 'direccion';
    protected $primaryKey = 'id_direccion';

    protected $useAutoIncrement = true;
    protected $returnType = 'array'; 
    protected $useSoftDeletes = false;

    protected $allowedFields = ['calle', 'altura', 'codigo_postal', 'id_usuario', 'id_ciudad'];

    protected $useTimestamps = false;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}