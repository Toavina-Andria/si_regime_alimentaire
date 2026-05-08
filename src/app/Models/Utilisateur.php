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
    protected $updatedField  = null;  // pas de champ updated_at dans ta table

    // Validation par défaut (pour l'insertion initiale – seulement email/mdp)
    protected $validationRules = [
        'nom'          => 'required|string|max_length[100]',
        'prenom'       => 'required|string|max_length[100]',
        'email'        => 'required|valid_email|is_unique[utilisateur.email,id,{id}]',
        'mot_de_passe' => 'required|min_length[6]|max_length[255]',
        // Les autres champs ne sont pas requis ici (seront ajoutés plus tard)
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

    // Règles spécifiques pour la mise à jour du profil (deuxième étape)
    public $validationRulesProfil = [
        'date_naissance'   => 'required|valid_date[Y-m-d]',
        'genre'            => 'required|in_list[homme,femme]',
        'taille_cm'        => 'required|numeric|greater_than[50]|less_than[300]',
        'poids_kg'         => 'required|numeric|greater_than[20]|less_than[500]',
        'objectif'         => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
        'adresse'          => 'permit_empty|max_length[255]',
    ];

    // Hachage automatique
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

    // Calcul IMC (optionnel)
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

    // Vérification mot de passe
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->getAttribute('mot_de_passe'));
    }

    // catégorie IMC selon valeur
    public function categorieIMC(?float $imc = null): ?string
    {
        if ($imc === null) return null;
        if ($imc < 18.5) return 'Poids insuffisant';
        if ($imc < 25) return 'Poids normal';
        if ($imc < 30) return 'Surpoids';
        return 'Obésité';
    }
}
