<?php

namespace App\Services;
use App\Models\Regime;
use App\Models\RegimePrix;
use App\Models\RegimeActivite;
use App\Models\Abonnement;
use App\Models\UtilisateurAbonnement;
use App\Models\SouscriptionRegime;

class RegimeService
{
    private static $dateFormat = 'Y-m-d H:i:s';
    public static function getRegimePrixByRegimeId($regimeId)
    {
        $regimePrixModel = new RegimePrix();
        return $regimePrixModel->where('regime_id', $regimeId)->findAll();
    }

    // validate user solde points
    public static function validateUserPoints($userId, $prix)
    {        $portefeuilleModel = UtilisateurService::getPortefeuilleByUserId($userId);
        $soldePoints = $portefeuilleModel['solde_points'];
        return $soldePoints >= $prix;
    }
}
