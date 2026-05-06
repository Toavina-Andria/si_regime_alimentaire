<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionPortefeuille extends Model
{
    protected $table            = 'transaction_portefeuille';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['portefeuille_id', 'code_bonus_id', 'montant', 'type', 'description'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    // Relationships
    public function portefeuille()
    {
        return $this->belongsTo(Portefeuille::class, 'portefeuille_id', 'id');
    }

    public function codeBonus()
    {
        return $this->belongsTo(CodeBonus::class, 'code_bonus_id', 'id');
    }
}
