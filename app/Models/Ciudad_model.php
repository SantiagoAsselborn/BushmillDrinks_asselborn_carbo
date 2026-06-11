<?php

namespace App\Models;

use CodeIgniter\Model;

class Ciudad_model extends Model
{
    protected $table = 'ciudad';
    protected $primaryKey = 'id_ciudad';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_ciudad', 'id_provincia'];

    protected $useTimestamps = false;
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}