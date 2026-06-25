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

    /* Operación: crear_promocion()*/
    public function crear_promocion($data)
    {
        return $this->insert([
            'id_bebida'        => $data['id_bebida'],
            'tipo_promocion'   => $data['tipo_promocion'], 
            'valor_promocion'  => $data['valor_promocion'], 
            'fecha_inicio'     => $data['fecha_inicio'],
            'fecha_fin'        => $data['fecha_fin'],
            'estado_promocion' => $data['estado_promocion'] ?? 1 
        ]);
    }

    /* Operación: validar_promocion()*/
    public function validar_promocion($id_bebida)
    {
        $hoy = date('Y-m-d H:i:s');
        return $this->where('id_bebida', $id_bebida)
                    ->where('estado_promocion', 1)
                    ->where('fecha_inicio <=', $hoy)
                    ->where('fecha_fin >=', $hoy)
                    ->first(); 
    }

    /* Operación: obtener_promociones()*/
    public function obtener_promociones()
    {
        $hoy = date('Y-m-d H:i:s');
        return $this->select('promocion.*, bebida.nombre_bebida, bebida.precio_bebida')
                    ->join('bebida', 'promocion.id_bebida = bebida.id_bebida')
                    ->where('promocion.estado_promocion', 1)
                    ->where('promocion.fecha_inicio <=', $hoy)
                    ->where('promocion.fecha_fin >=', $hoy)
                    ->findAll();
    }
}