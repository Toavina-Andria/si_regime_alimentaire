<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiviteSportive extends Model
{
    protected $table            = 'activite_sportive';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'description', 'intensite', 'calories_heure'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByIntensite(int $niveau): array
    {
        return $this->where('intensite', $niveau)->orderBy('nom', 'ASC')->findAll();
    }

    public function getRecent(int $limit = 10): array
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }

    // Relationships
    public function regimes()
    {
        return $this->hasMany(RegimeActivite::class, 'activite_id', 'id');
    }
}
