<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimePrix extends Model
{
    protected $table            = 'regime_prix';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['regime_id', 'duree_jours', 'prix_base'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    public function regime()
    {
        return $this->belongsTo(Regime::class, 'regime_id', 'id');
    }

    public function souscriptions()
    {
        return $this->hasMany(SouscriptionRegime::class, 'regime_prix_id', 'id');
    }
}
