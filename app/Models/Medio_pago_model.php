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

class Medio_pago_model extends Model
{
    protected $table = 'medio_pago';
    protected $primaryKey = 'id_medio_pago';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'nombre_medio_pago',
        'estado_medio_pago'
    ];
}