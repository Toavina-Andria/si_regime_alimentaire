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
    // Souscription à un régime
    public static function souscrireRegime($userId, $regimePrixId)

            return ['success' => true, 'message' => "Souscription réussie"];
        } catch (\Throwable $th) {
    // validate user solde points
    public static function validateUserPoints($userId, $prix)
    {        $portefeuilleModel = UtilisateurService::getPortefeuilleByUserId($userId);
        $soldePoints = $portefeuilleModel['solde_points'];
        return $soldePoints >= $prix;
    }
    // est abonne a un abonnement
    public static function isAbonne($userId){
        $abonnentuserModel = new UtilisateurAbonnement();
        $abonnement = $abonnentuserModel->where('utilisateur_id', $userId)
            ->where('date_fin >=', date(self::$dateFormat))
            ->first();
        return $abonnement != null;
    }
    // s'abonner a un abonnement
    public static function applySubscription($userId, $abonnementId)
    {
        $abonnentuserModel = new UtilisateurAbonnement();
        $data = [
            'utilisateur_id' => $userId,
            'abonnement_id' => $abonnementId,
            'date_debut' => date(self::$dateFormat),
            'date_fin' => date(self::$dateFormat, strtotime('+30 days')),
        ];
        return $abonnentuserModel->insert($data);
    }
}
