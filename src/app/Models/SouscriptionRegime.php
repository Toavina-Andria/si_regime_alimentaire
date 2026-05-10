<?php

namespace App\Models;

use CodeIgniter\Model;

class SouscriptionRegime extends Model
{
    protected $table            = 'souscription_regime';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['utilisateur_id', 'regime_prix_id', 'date_debut', 'date_fin', 'prix_paye', 'remise_appliquee', 'statut'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getActiveByUser(int $userId): ?array
    {
        return $this->where('utilisateur_id', $userId)
            ->where('statut', 'actif')
            ->where('date_debut <=', date('Y-m-d'))
            ->where('date_fin >=', date('Y-m-d'))
            ->first();
    }

    public function getHistoryByUser(int $userId): array
    {
        return $this->select('souscription_regime.*, regime.nom')
            ->join('regime_prix', 'regime_prix.id = souscription_regime.regime_prix_id')
            ->join('regime', 'regime.id = regime_prix.regime_id')
            ->where('souscription_regime.utilisateur_id', $userId)
            ->orderBy('souscription_regime.date_debut', 'DESC')
            ->findAll();
    }

    // Relationships
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id', 'id');
    }

    public function regimePrix()
    {
        return $this->belongsTo(RegimePrix::class, 'regime_prix_id', 'id');
    }

    public function regime()
    {
        return $this->regimePrix()->with('regime');
    }
}
