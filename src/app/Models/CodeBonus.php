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
    protected $allowedFields    = ['code', 'valeur_points', 'est_valide', 'expires_at', 'created_by'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getValid(): array
    {
        return $this->where('est_valide', 1)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getRecent(int $limit = 10): array
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }

    public function transactions()
    {
        return $this->hasMany(TransactionPortefeuille::class, 'code_bonus_id', 'id');
    }
}
