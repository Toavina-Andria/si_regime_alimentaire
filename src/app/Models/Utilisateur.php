<?php

namespace App\Models;

use CodeIgniter\Model;

class Utilisateur extends Model
{
    protected $table            = 'utilisateur';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'prenom', 'email', 'mot_de_passe', 'date_naissance', 'genre', 'adresse', 'taille_cm', 'poids_kg', 'objectif'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    // Validation
    protected $validationRules = [
        'nom'              => 'required|string|max_length[100]',
        'prenom'           => 'required|string|max_length[100]',
        'email'            => 'required|valid_email|is_unique[utilisateur.email,id,{id}]',
        'mot_de_passe'     => 'required|min_length[8]|max_length[255]',
        'date_naissance'   => 'permit_empty|valid_date[Y-m-d]',
        'genre'            => 'required|in_list[homme,femme,autre]',
        'adresse'          => 'permit_empty|max_length[255]',
        'taille_cm'        => 'permit_empty|numeric|greater_than[50]|less_than[300]',
        'poids_kg'         => 'permit_empty|numeric|greater_than[20]|less_than[500]',
        'objectif'         => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé.'
        ]
    ];

    protected $skipValidation = false;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // Relationships
    public function abonnements()
    {
        return $this->hasMany(UtilisateurAbonnement::class, 'utilisateur_id', 'id');
    }

    public function portefeuille()
    {
        return $this->hasOne(Portefeuille::class, 'utilisateur_id', 'id');
    }

    public function souscriptions()
    {
        return $this->hasMany(SouscriptionRegime::class, 'utilisateur_id', 'id');
    }

    public function historiquePoids()
    {
        return $this->hasMany(HistoriquePoids::class, 'utilisateur_id', 'id');
    }

    // Callback : Hash password only if changed
    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['mot_de_passe'])) {
            return $data;
        }

        if ($data['data']['mot_de_passe'] !== '') {
            $data['data']['mot_de_passe'] = password_hash($data['data']['mot_de_passe'], PASSWORD_BCRYPT);
        }

        return $data;
    }

    // Calculer l'IMC
    public function calculerIMC(?float $taille_cm = null, ?float $poids_kg = null): ?float
    {
        $taille = $taille_cm ?? $this['taille_cm'] ?? null;
        $poids = $poids_kg ?? $this['poids_kg'] ?? null;

        if ($taille && $poids && $taille > 0 && $poids > 0) {
            $taille_m = $taille / 100;
            return round($poids / ($taille_m * $taille_m), 2);
        }
        return null;
    }

    // Déterminer la catégorie IMC
    public function categorieIMC(?float $imc = null): ?string
    {
        $imc = $imc ?? $this->calculerIMC();

        if (!$imc) return null;

        if ($imc < 18.5) return 'Poids insuffisant';
        if ($imc < 25) return 'Poids normal';
        if ($imc < 30) return 'Surpoids';
        return 'Obésité';
    }

    // Vérifier mot de passe
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->getAttribute('mot_de_passe'));
    }
}
