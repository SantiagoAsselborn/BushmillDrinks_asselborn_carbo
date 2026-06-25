<?php

namespace App\Models;

use CodeIgniter\Model;

class Categoria_model extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_categoria'];

    protected $useTimestamps = false;
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;


    /* Operación: registrar_categoria(nombre_categoria)*/
    public function registrar_categoria($nombre_categoria)
    {
        return $this->insert([
            'nombre_categoria' => $nombre_categoria
        ]);
    }

    /* Operación: listar_categorias()*/
    public function listar_categorias()
    {
        return $this->orderBy('nombre_categoria', 'ASC')->findAll();
    }
}