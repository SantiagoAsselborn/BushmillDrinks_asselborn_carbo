<?php

namespace App\Models;

use CodeIgniter\Model;

class Provincia_model extends Model
{
    protected $table = 'provincia';
    protected $primaryKey = 'id_provincia';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_provincia'];

    protected $useTimestamps = false;
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}