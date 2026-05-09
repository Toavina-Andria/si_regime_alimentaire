<?php

namespace App\Services;
use App\Models\Regime;
use App\Models\RegimePrix;
use App\Models\RegimeActivite;
use App\Models\Abonnement;
use App\Models\UtilisateurAbonnement;
use App\Models\SouscriptionRegime;
use App\Services\DebugLogger;

class RegimeService
{
    private static $dateFormat = 'Y-m-d H:i:s';

    // Get regime by ID
    public static function getRegimeById($regimeId)
    {
        $regimeModel = new Regime();
        return $regimeModel->find($regimeId);
    }

    // Get regime prix by regime ID
    public static function getRegimePrixByRegimeId($regimeId)
    {
        $regimePrixModel = new RegimePrix();
        return $regimePrixModel->where('regime_id', $regimeId)->findAll();
    }

    // Create regime with validation
    public static function createRegime($data)
    {
        try {
            log_message('info', 'createRegime called: ' . implode(', ', $data));
            $regimeModel = new Regime();

            if (!$regimeModel->validate($data)) {
                throw new \Exception('Erreur de validation: ' . implode(', ', $regimeModel->errors()));
            }

            if ($regimeModel->insert($data)) {
                log_message('info', 'createRegime success: ID ' . $regimeModel->getInsertID());
            } else {
                throw new \Exception('Erreur lors de la création du régime: ');
            }
        } catch (\Throwable $th) {
            log_message('error', 'createRegime exception: ' . $th->getMessage());
            throw new \Exception('Erreur lors de la création du régime: ' . $th->getMessage());
        }


    }

    // Update regime with validation
    public static function updateRegime($regimeId, $data)
    {
        try {
            log_message('info', 'updateRegime called: ' . json_encode(['regimeId' => $regimeId, 'data' => $data]));
            $regimeModel = new Regime();
            // show logs
            log_message('debug', 'updateRegime called with regimeId: ' . $regimeId . ' and data: ' . json_encode($data));
            // Check if regime exists
            $regime = $regimeModel->find($regimeId);
            if (!$regime) {
                return [
                    'success' => false,
                    'message' => 'Régime introuvable'
                ];
            }

            if (!$regimeModel->validate($data)) {
                return [
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $regimeModel->errors()
                ];
            }

            if ($regimeModel->update($regimeId, $data)) {
                return [
                    'success' => true,
                    'message' => 'Régime mis à jour avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du régime'
                ];
            }
        } catch (\Throwable $th) {
            DebugLogger::error('updateRegime exception', ['message' => $th->getMessage(), 'trace' => $th->getTraceAsString()]);
            return [
                'success' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    // Delete regime
    public static function deleteRegime($regimeId)
    {
        try {
            $regimeModel = new Regime();

            if ($regimeModel->delete($regimeId)) {
                return [
                    'success' => true,
                    'message' => 'Régime supprimé avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Impossible de supprimer ce régime'
                ];
            }
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage()
            ];
        }
    }
    // Souscription à un régime
    public static function souscrireRegime($userId, $regimePrixId)
    {
        try {
            log_message('info', 'souscrireRegime start: ' . json_encode(['userId' => $userId, 'regimePrixId' => $regimePrixId]));
            UtilisateurService::validateUser($userId);

            $souscriptionModel = new SouscriptionRegime();
            // get reime prix details
            $regimePrixModel = new RegimePrix();
            $regimePrix = $regimePrixModel->find($regimePrixId);

            $prixPayable = $regimePrix['prix'];
            $tauxRemise = 0;
            // check if user has abonnement actif
            if ($abonne = self::isAbonne($userId)) {
                // apply abonnement discount
                $tauxRemise = $abonne['taux_reduction'] ?? 0;
                $prixPayable *= (1 - $tauxRemise / 100);
            }
            if (!self::validateUserPoints($userId, $prixPayable)) {
                DebugLogger::error('Insufficient points for souscription', ['userId' => $userId, 'prix' => $prixPayable]);
                throw new \Exception("Solde insuffisant pour souscrire à ce régime.");
            }
            // set the registration date and end date
            $data = [
                'utilisateur_id' => $userId,
                'regime_prix_id' => $regimePrixId,
                'date_debut' => date(self::$dateFormat),
                'date_fin' => date(self::$dateFormat, strtotime('+' . $regimePrix['duree_jours'] . ' days')),
                'prix_paye' => $prixPayable,
                'remise_appliquee' => $tauxRemise
            ];
            $souscriptionModel->insert($data);
            DebugLogger::info('souscription saved', ['utilisateur_id' => $userId, 'regime_prix_id' => $regimePrixId, 'prix_paye' => $prixPayable]);
            // deduct points from user portefeuille
            UtilisateurService::payWithPoints($userId, $prixPayable);
            // save transaction historique
            UtilisateurService::saveTransactionHistorique($userId, $prixPayable, null, 'debit', "Souscription au régime : " . $regimePrixId);

            DebugLogger::info('souscrireRegime completed', ['userId' => $userId, 'prix_paye' => $prixPayable]);
            return ['success' => true, 'message' => "Souscription réussie"];
        } catch (\Throwable $th) {
            DebugLogger::error('souscrireRegime exception', ['message' => $th->getMessage(), 'trace' => $th->getTraceAsString()]);
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }
    // validate user solde points
    public static function validateUserPoints($userId, $prix)
    {
        $portefeuilleModel = UtilisateurService::getPortefeuilleByUserId($userId);
        $soldePoints = $portefeuilleModel['solde_points'];
        return $soldePoints >= $prix;
    }
    // est abonne a un abonnement
    public static function isAbonne($userId)
    {
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
    // buy subscription
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
            // apply subscription
            self::applySubscription($userId, $abonnementId);
            // deduct points from user portefeuille
            UtilisateurService::payWithPoints($userId, $prixPayable);
            // save transaction historique
            UtilisateurService::saveTransactionHistorique($userId, $prixPayable, null, 'debit', "Achat de l'abonnement : " . $abonnement['nom']);

            return ['success' => true, 'message' => "Abonnement " . $abonnement['nom'] . ' - ' . $abonnement['statut'] . " acheté avec succès"];
        } catch (\Throwable $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }
}
