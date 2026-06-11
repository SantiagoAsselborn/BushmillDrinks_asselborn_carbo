<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * @method bool insert(array $data = null, bool $returnID = true)
 * @method array|null find($id = null)
 * @method bool update($id = null, $data = null)
 * @method bool delete($id = null)
 * @method array|null first()
 * @method $this where($key, $value = null, bool $escape = null)
 * @method $this like($key, $match = null, $side = 'both', $escape = null)
 */

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