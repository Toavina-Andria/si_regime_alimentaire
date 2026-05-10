<?php

namespace App\Models;

use CodeIgniter\Model;

class Abonnement extends Model
{
    protected $table            = 'abonnement';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'statut', 'taux_reduction', 'prix', 'description'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getByStatut(string $statut): array
    {
        return $this->where('statut', $statut)->orderBy('created_at', 'DESC')->findAll();
    }

    public function getRecent(int $limit = 10): array
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }

    // Relationships
    public function utilisateurs()
    {
        return $this->hasMany(UtilisateurAbonnement::class, 'abonnement_id', 'id');
    }
}
