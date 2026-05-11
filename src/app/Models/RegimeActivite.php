<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeActivite extends Model
{
    protected $table            = 'regime_activite';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['regime_id', 'activite_id', 'frequence_semaine'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    public function regime()
    {
        return $this->belongsTo(Regime::class, 'regime_id', 'id');
    }

    public function activite()
    {
        return $this->belongsTo(ActiviteSportive::class, 'activite_id', 'id');
    }
}
