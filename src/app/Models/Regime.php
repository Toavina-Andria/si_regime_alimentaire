<?php

namespace App\Models;

use CodeIgniter\Model;

class Regime extends Model
{
    protected $table = 'regime';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nom', 'description', 'pct_viande', 'pct_volaille', 'pct_poisson', 'variation_poids_kg', 'duree_jours'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'nom' => 'required|min_length[3]',
        'pct_viande' => 'required|numeric',
        'pct_volaille' => 'required|numeric',
        'pct_poisson' => 'required|numeric',
        'variation_poids_kg' => 'required|numeric',
        'duree_jours' => 'required|integer|greater_than[0]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    // Relationships
    public function prix()
    {
        return $this->hasMany(RegimePrix::class, 'regime_id', 'id');
    }

    public function activites()
    {
        return $this->hasMany(RegimeActivite::class, 'regime_id', 'id');
    }

    public function souscriptions()
    {
        return $this->hasMany(SouscriptionRegime::class, 'regime_prix_id', 'id');
    }
}
