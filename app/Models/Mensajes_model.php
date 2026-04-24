<?php

namespace App\Models;

use CodeIgniter\Model;

class Mensajes_model extends Model
{
    protected $table = 'mensaje';
    protected $primaryKey = 'id_mensaje';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre_mensaje', 'mail_mensaje', 'telefono_mensaje', 'consulta_mensaje', 'estado_mensaje'];

    protected $useTimestamps = false;
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}