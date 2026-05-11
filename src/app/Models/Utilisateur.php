<?php

namespace App\Models;

use CodeIgniter\Model;

class Utilisateur extends Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nom', 'prenom', 'email', 'mot_de_passe', 'date_naissance', 'genre', 'adresse', 'taille_cm', 'poids_kg', 'objectif', 'est_admin'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    protected $validationRules = [
        'nom' => 'required|string|max_length[100]',
        'prenom' => 'required|string|max_length[100]',
        'email' => 'required|valid_email|is_unique[utilisateur.email,id,{id}]',
        'mot_de_passe' => 'required|min_length[6]|max_length[255]',

    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé.'
        ]
    ];

    protected $skipValidation = false;

    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    public function adminUpdate(int $id, array $data): bool
    {
        $this->skipValidation = true;
        $this->allowedFields[] = 'est_admin';
        $result = $this->update($id, $data);
        $this->skipValidation = false;
        return $result;
    }

    public function adminDelete(int $id): bool
    {
        $this->skipValidation = true;
        $this->db->transStart();
        $this->db->table('souscription_regime')->where('utilisateur_id', $id)->delete();
        $this->db->table('utilisateur_abonnement')->where('utilisateur_id', $id)->delete();
        $this->db->table('portefeuille')->where('utilisateur_id', $id)->delete();
        $this->db->table('historique_poids')->where('utilisateur_id', $id)->delete();
        $this->db->table('transaction_portefeuille')->where('utilisateur_id', $id)->delete();
        $result = $this->delete($id);
        $this->db->transComplete();
        return $result;
    }

    public static $validationRulesProfil = [
        'date_naissance' => 'required|valid_date[Y-m-d]',
        'genre' => 'required|in_list[homme,femme]',
        'taille_cm' => 'required|numeric|greater_than[50]|less_than[300]',
        'poids_kg' => 'required|numeric|greater_than[20]|less_than[500]',
        'objectif' => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
        'adresse' => 'permit_empty|max_length[255]',
    ];
    public static $validationMessagesProfil = [
        'date_naissance' => [
            'valid_date' => 'La date de naissance doit être au format YYYY-MM-DD.'
        ],
        'genre' => [
            'in_list' => 'Le genre doit être "homme" ou "femme".'
        ],
        'taille_cm' => [
            'greater_than' => 'La taille doit être supérieure à 50 cm.',
            'less_than' => 'La taille doit être inférieure à 300 cm.'
        ],
        'poids_kg' => [
            'greater_than' => 'Le poids doit être supérieur à 20 kg.',
            'less_than' => 'Le poids doit être inférieur à 500 kg.'
        ],
        'objectif' => [
            'in_list' => 'L\'objectif doit être "augmenter_poids", "reduire_poids" ou "imc_ideal".'
        ]
    ];

    public static $validationRulesInscription = [
        'nom' => 'required|string|max_length[100]',
        'prenom' => 'required|string|max_length[100]',
        'email' => 'required|valid_email|is_unique[utilisateur.email,id,{id}]',
        'mot_de_passe' => 'required|min_length[6]|max_length[255]',

    ];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['mot_de_passe']) || $data['data']['mot_de_passe'] === '') {
            unset($data['data']['mot_de_passe']);
            return $data;
        }
        $data['data']['mot_de_passe'] = password_hash($data['data']['mot_de_passe'], PASSWORD_BCRYPT);
        return $data;
    }

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

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->getAttribute('mot_de_passe'));
    }

    public function categorieIMC(?float $imc = null): ?string
    {
        if ($imc === null)
            return null;
        if ($imc < 18.5)
            return 'Poids insuffisant';
        if ($imc < 25)
            return 'Poids normal';
        if ($imc < 30)
            return 'Surpoids';
        return 'Obésité';
    }

    public function getByObjectif(string $objectif): array
    {
        return $this->where('objectif', $objectif)->findAll();
    }

    public function getRecent(int $limit = 10): array
    {
        return $this->orderBy('created_at', 'DESC')->limit($limit)->findAll();
    }
}
