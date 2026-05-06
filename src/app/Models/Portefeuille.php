<?php

namespace App\Models;

use CodeIgniter\Model;

class Portefeuille extends Model
{
    protected $table            = 'portefeuille';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['utilisateur_id', 'solde_points'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $updatedField  = 'updated_at';

    // Relationships
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionPortefeuille::class, 'portefeuille_id', 'id');
    }
}
