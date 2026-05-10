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
    // total pct = viande + volaille + poisson = 100% (ou moins si végétarien, vegan...)
    protected $validationRules = [
        'nom' => 'required|min_length[3]',
        'pct_viande' => 'required|numeric',
        'pct_volaille' => 'required|numeric',
        'pct_poisson' => 'required|numeric',
        'variation_poids_kg' => 'required|numeric',
        'duree_jours' => 'required|integer|greater_than[0]'
    ];

    public  $validationMessages = [
        'nom' => [
            'required' => 'Le nom du régime est obligatoire.',
            'min_length' => 'Le nom doit comporter au moins 3 caractères.'
        ],
        'pct_viande' => [
            'required' => 'Le pourcentage de viande est obligatoire.',
            'numeric' => 'Le pourcentage de viande doit être un nombre.'
        ],
        'pct_volaille' => [
            'required' => 'Le pourcentage de volaille est obligatoire.',
            'numeric' => 'Le pourcentage de volaille doit être un nombre.'
        ],
        'pct_poisson' => [
            'required' => 'Le pourcentage de poisson est obligatoire.',
            'numeric' => 'Le pourcentage de poisson doit être un nombre.'
        ],
        'variation_poids_kg' => [
            'required' => 'La variation de poids est obligatoire.',
            'numeric' => 'La variation de poids doit être un nombre.'
        ],
        'duree_jours' => [
            'required' => 'La durée en jours est obligatoire.',
            'integer' => 'La durée doit être un nombre entier.',
            'greater_than' => 'La durée doit être supérieure à zéro.'
        ]
    ];
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
