<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeBonus extends Model
{
    protected $table            = 'code_bonus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['code', 'valeur_points', 'est_valide', 'expires_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    // Relationships
    public function transactions()
    {
        return $this->hasMany(TransactionPortefeuille::class, 'code_bonus_id', 'id');
    }
}
