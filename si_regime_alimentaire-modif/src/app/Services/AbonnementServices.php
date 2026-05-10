<?php

namespace App\Services;

use App\Models\Abonnement;
use App\Models\UtilisateurAbonnement;
use App\Services\UtilisateurService;

class AbonnementServices
{
    private static $dateFormat = 'Y-m-d H:i:s';

    /**
     * Buy/subscribe to an abonnement (membership)
     * 
     * @param int $userId User ID
     * @param int $abonnementId Abonnement ID
     * @return array ['success' => bool, 'message' => string]
     */
    public static function buySubscription($userId, $abonnementId)
    {
        try {
            // Validate user ID
            if (!$userId || !is_numeric($userId)) {
                throw new \Exception("ID utilisateur invalide.");
            }

            // Validate abonnement exists
            $abonnementModel = new Abonnement();
            $abonnement = $abonnementModel->find($abonnementId);
            
            if (!$abonnement) {
                throw new \Exception("Abonnement non trouvé.");
            }

            // Validate user has enough points
            $prixPayable = $abonnement['prix'];
            if (!self::validateUserPoints($userId, $prixPayable)) {
                throw new \Exception("Solde insuffisant pour acheter cet abonnement.");
            }

            // Check if user already has an active subscription
            if (self::hasActiveSubscription($userId)) {
                throw new \Exception("Vous avez déjà un abonnement actif.");
            }

            // Create subscription
            $utlisateurAbonnementModel = new UtilisateurAbonnement();
            $data = [
                'utilisateur_id' => $userId,
                'abonnement_id' => $abonnementId,
                'date_debut' => date(self::$dateFormat),
                'date_fin' => date(self::$dateFormat, strtotime('+30 days')),
                'statut' => 'actif'
            ];

            if (!$utlisateurAbonnementModel->insert($data)) {
                throw new \Exception("Erreur lors de la création de l'abonnement.");
            }

            // Deduct points from user wallet
            UtilisateurService::payWithPoints($userId, $prixPayable);

            // Save transaction history
            UtilisateurService::saveTransactionHistorique(
                $userId,
                $prixPayable,
                null,
                'debit',
                "Souscription à l'abonnement : " . $abonnement['nom']
            );

            return [
                'success' => true,
                'message' => "Abonnement '" . $abonnement['nom'] . "' activé avec succès!"
            ];

        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * French alias for buySubscription
     * Wraps the main subscription method
     * 
     * @param int $userId User ID
     * @param int $abonnementId Abonnement ID
     * @return array ['success' => bool, 'message' => string]
     */
    public static function souscrireRegime($userId, $abonnementId)
    {
        return self::buySubscription($userId, $abonnementId);
    }

    /**
     * Validate user has enough points for the purchase
     * 
     * @param int $userId User ID
     * @param float $prix Price to validate against
     * @return bool
     */
    private static function validateUserPoints($userId, $prix)
    {
        try {
            $portefeuille = UtilisateurService::getPortefeuilleByUserId($userId);
            if (!$portefeuille) {
                return false;
            }
            return $portefeuille['solde_points'] >= $prix;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Check if user has an active subscription
     * 
     * @param int $userId User ID
     * @return bool
     */
    private static function hasActiveSubscription($userId)
    {
        $abonnementUserModel = new UtilisateurAbonnement();
        $activeSubscription = $abonnementUserModel
            ->where('utilisateur_id', $userId)
            ->where('date_fin >=', date(self::$dateFormat))
            ->first();

        return $activeSubscription != null;
    }

    /**
     * Get all available abonnements
     * 
     * @return array
     */
    public static function getAllAbonnements()
    {
        $abonnementModel = new Abonnement();
        return $abonnementModel->findAll();
    }

    /**
     * Get abonnement by ID
     * 
     * @param int $abonnementId
     * @return array|null
     */
    public static function getAbonnement($abonnementId)
    {
        $abonnementModel = new Abonnement();
        return $abonnementModel->find($abonnementId);
    }

    /**
     * Get user's active subscription if any
     * 
     * @param int $userId User ID
     * @return array|null
     */
    public static function getUserActiveSubscription($userId)
    {
        $abonnementUserModel = new UtilisateurAbonnement();
        return $abonnementUserModel
            ->where('utilisateur_id', $userId)
            ->where('date_fin >=', date(self::$dateFormat))
            ->first();
    }
}
