<?php

namespace App\Services;

use App\Models\Abonnement;
use App\Models\UtilisateurAbonnement;
use App\Services\UtilisateurService;

class AbonnementServices
{
    private static $dateFormat = 'Y-m-d';

    public static function buySubscription($userId, $abonnementId)
    {
        try {

            if (!$userId || !is_numeric($userId)) {
                throw new \Exception("ID utilisateur invalide.");
            }

            $abonnementModel = new Abonnement();
            $abonnement = $abonnementModel->find($abonnementId);

            if (!$abonnement) {
                throw new \Exception("Abonnement non trouvé.");
            }

            $prixPayable = $abonnement['prix'];
            if (!self::validateUserPoints($userId, $prixPayable)) {
                throw new \Exception("Solde insuffisant pour acheter cet abonnement.");
            }

            if (self::hasActiveSubscription($userId)) {
                throw new \Exception("Vous avez déjà un abonnement actif.");
            }

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

            UtilisateurService::payWithPoints($userId, $prixPayable);

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

    public static function souscrireRegime($userId, $abonnementId)
    {
        return self::buySubscription($userId, $abonnementId);
    }

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

    private static function hasActiveSubscription($userId)
    {
        $abonnementUserModel = new UtilisateurAbonnement();
        $activeSubscription = $abonnementUserModel
            ->where('utilisateur_id', $userId)
            ->where('date_fin >=', date(self::$dateFormat))
            ->first();

        return $activeSubscription != null;
    }

    public static function getAllAbonnements()
    {
        $abonnementModel = new Abonnement();
        return $abonnementModel->findAll();
    }

    public static function getAbonnement($abonnementId)
    {
        $abonnementModel = new Abonnement();
        return $abonnementModel->find($abonnementId);
    }

    public static function getUserActiveSubscription($userId)
    {
        $abonnementUserModel = new UtilisateurAbonnement();
        return $abonnementUserModel
            ->where('utilisateur_id', $userId)
            ->where('date_fin >=', date(self::$dateFormat))
            ->first();
    }

    public static function getUserActiveAbonnementWithDetails(int $userId): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('utilisateur_abonnement ua')
            ->select('a.nom, a.statut')
            ->join('abonnement a', 'a.id = ua.abonnement_id')
            ->where('ua.utilisateur_id', $userId)
            ->where('ua.statut', 'actif')
            ->orderBy('ua.created_at', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }
}
