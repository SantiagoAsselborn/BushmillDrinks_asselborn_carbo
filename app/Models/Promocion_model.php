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