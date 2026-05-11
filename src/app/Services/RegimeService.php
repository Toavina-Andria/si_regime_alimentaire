<?php

namespace App\Services;
use App\Models\ActiviteSportive;
use App\Models\Regime;
use App\Models\RegimePrix;
use App\Models\RegimeActivite;
use App\Models\Abonnement;
use App\Models\UtilisateurAbonnement;
use App\Models\SouscriptionRegime;
use App\Services\DebugLogger;

class RegimeService
{
    private static $dateFormat = 'Y-m-d';

    public static function getRegimeById($regimeId)
    {
        $regimeModel = new Regime();
        return $regimeModel->find($regimeId);
    }

    public static function getRegimePrixByRegimeId($regimeId)
    {
        $regimePrixModel = new RegimePrix();
        return $regimePrixModel->where('regime_id', $regimeId)->findAll();
    }

    public static function getActiviteByRegimeId($idRegime)
    {

        try {

            $regimeActiviteModel = new RegimeActivite();
            $results = $regimeActiviteModel
                ->select('a.*, regime_activite.frequence_semaine')
                ->join('activite_sportive a', 'a.id = regime_activite.activite_id')
                ->where('regime_activite.regime_id', $idRegime)
                ->orderBy('a.nom', 'ASC')
                ->findAll();

            return $results ?: [];
        } catch (\Throwable $th) {
            throw new \Exception('error: ' . $th->getMessage());
        }

    }
    public static function createRegime($data)
    {
        log_message('info', 'createRegime called: ' . implode(', ', $data));
        $regimeModel = new Regime();
        if (!self::validateptcTotal($data['pct_viande'], $data['pct_volaille'], $data['pct_poisson'])) {
            throw new \Exception('Le total des pourcentages de viande, volaille et poisson ne peut pas dépasser 100%.');
        }
        if (!$regimeModel->validate($data)) {
            throw new \Exception('Erreur de validation: ' . implode(', ', $regimeModel->errors()));
        }
        if (!$regimeModel->insert($data)) {
            throw new \Exception('Erreur lors de la création du régime.');
        }
        log_message('info', 'createRegime success: ID ' . $regimeModel->getInsertID());
    }

    public static function updateRegime($regimeId, $data)
    {
        $regimeModel = new Regime();
        if (!self::validateptcTotal($data['pct_viande'], $data['pct_volaille'], $data['pct_poisson'])) {
            throw new \Exception('Le total des pourcentages de viande, volaille et poisson ne peut pas dépasser 100%.');
        }
        $regime = $regimeModel->find($regimeId);
        if (!$regime) {
            throw new \Exception("Régime non trouvé.");
        }
        if (!$regimeModel->validate($data)) {
            throw new \Exception('Erreur de validation: ' . implode(', ', $regimeModel->errors()));
        }
        if (!$regimeModel->update($regimeId, $data)) {
            throw new \Exception('Erreur lors de la mise à jour du régime.');
        }
    }
    public static function validateptcTotal($pctViande, $pctVolaille, $pctPoisson)
    {
        $total = $pctViande + $pctVolaille + $pctPoisson;
        return $total <= 100;
    }
    public static function deleteRegime($regimeId)
    {
        $regimeModel = new Regime();
        if (!$regimeModel->delete($regimeId)) {
            throw new \Exception('Erreur lors de la suppression du régime.');
        }
    }

    public static function souscrireRegime($userId, $regimePrixId)
    {
        try {
            log_message('info', 'souscrireRegime start: ' . json_encode(['userId' => $userId, 'regimePrixId' => $regimePrixId]));
            UtilisateurService::validateUser($userId);

            $souscriptionModel = new SouscriptionRegime();

            $regimePrixModel = new RegimePrix();
            $regimePrix = $regimePrixModel->find($regimePrixId);

            $prixPayable = $regimePrix['prix_base'];
            $tauxRemise = 0;

            if ($abonne = self::isAbonne($userId)) {

                $tauxRemise = $abonne['taux_reduction'] ?? 0;
                $prixPayable *= (1 - $tauxRemise / 100);
            }
            if (!self::validateUserPoints($userId, $prixPayable)) {
                throw new \Exception("Solde insuffisant pour souscrire à ce régime.");
            }

            $data = [
                'utilisateur_id' => $userId,
                'regime_prix_id' => $regimePrixId,
                'date_debut' => date(self::$dateFormat),
                'date_fin' => date(self::$dateFormat, strtotime('+' . $regimePrix['duree_jours'] . ' days')),
                'prix_paye' => $prixPayable,
                'remise_appliquee' => $tauxRemise
            ];
            $souscriptionModel->insert($data);

            UtilisateurService::payWithPoints($userId, $prixPayable);

            UtilisateurService::saveTransactionHistorique($userId, $prixPayable, null, 'debit', "Souscription au régime : " . $regimePrixId);

            return ['success' => true, 'message' => "Souscription réussie"];
        } catch (\Throwable $th) {
            throw new \Exception('Erreur lors de la souscription au régime: ' . $th->getMessage());
        }
    }

    public static function validateUserPoints($userId, $prix)
    {
        $portefeuille = UtilisateurService::getPortefeuilleByUserId($userId);
        $soldePoints = $portefeuille['solde_points'] ?? 0;
        return $soldePoints >= $prix;
    }

    public static function isAbonne($userId)
    {
        $abonnentuserModel = new UtilisateurAbonnement();
        $abonnement = $abonnentuserModel
            ->select('ua.*, a.taux_reduction')
            ->join('abonnement a', 'a.id = ua.abonnement_id')
            ->where('ua.utilisateur_id', $userId)
            ->where('ua.date_fin >=', date(self::$dateFormat))
            ->first();
        return $abonnement;
    }

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

    public static function buySubscription($userId, $abonnementId)
    {
        try {
            UtilisateurService::validateUser($userId);
            $abonnementModel = new Abonnement();
            $abonnement = $abonnementModel->find($abonnementId);
            if (!$abonnement) {
                throw new \Exception("Abonnement non trouvé.");
            }
            $prixPayable = $abonnement['prix'];
            if (!self::validateUserPoints($userId, $prixPayable)) {
                throw new \Exception("Solde insuffisant pour acheter cet abonnement.");
            }

            self::applySubscription($userId, $abonnementId);

            UtilisateurService::payWithPoints($userId, $prixPayable);

            UtilisateurService::saveTransactionHistorique($userId, $prixPayable, null, 'debit', "Achat de l'abonnement : " . $abonnement['nom']);

            return ['success' => true, 'message' => "Abonnement " . $abonnement['nom'] . ' - ' . $abonnement['statut'] . " acheté avec succès"];
        } catch (\Throwable $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }
}
