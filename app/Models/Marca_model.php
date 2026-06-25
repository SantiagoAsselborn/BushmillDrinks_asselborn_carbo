<?php

namespace App\Models;

use CodeIgniter\Model;

class Marca_model extends Model
{
    protected $table = 'marca';
    protected $primaryKey = 'id_marca';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_marca'];

    protected $useTimestamps = false;
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /* Operación: registrar_marca()*/
    public function registrar_marca($nombre_marca)
    {
        return $this->insert([
            'nombre_marca' => $nombre_marca
        ]);
    }

    /* Operación: listar_marcas()*/
    public function listar_marcas()
    {
        return $this->orderBy('nombre_marca', 'ASC')->findAll();
    }
}