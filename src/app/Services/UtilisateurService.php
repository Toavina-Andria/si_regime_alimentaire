<?php
namespace App\Services;
use App\Models\CodeBonus;
use App\Models\Portefeuille;
use App\Models\TransactionPortefeuille;

class UtilisateurService
{
    //constructeur


    public static function redeemCode(string $code_bonus, int $id_user)
    {
        try {
            // 1. Validate Code
            $code = self::validateCode($code_bonus);

            // 2. Validate Portefeuille
            $porteFeuille = self::validatePortefeuille($id_user);
            // 3. Validate Transaction
            self::validateTransaction($code['id'], $porteFeuille['id']);

            // add transaction to transaction historique
            self::saveTransactionHistorique($id_user, $code['valeur_points'], $code['id'], 'credit', "Rachat du code bonus : " . $code_bonus);

            // update porte feuille solde
            $portefeuilleModel = new Portefeuille();
            $portefeuilleModel->update($porteFeuille['id'], [
                'solde_points' => $porteFeuille['solde_points'] + $code['valeur_points']
            ]);

        } catch (\Throwable $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }

        return ['success' => true, 'message' => 'Code redeemed successfully'];
    }

    private static function validateCode(string $code_bonus)
    {
        if (!$code_bonus) {
            throw new \Exception("Code bonus manquant");
        }

        $codeModel = new CodeBonus();
        $code = $codeModel->where('code', $code_bonus)->first();

        if ($code == null) {
            throw new \Exception("Code bonus invalide : " . $code_bonus);
        }

        if (!$code['est_valide'] || strtotime($code['expires_at']) < time()) {
            throw new \Exception("Code bonus expiré");
        }

        return $code;
    }

    private static function validatePortefeuille(int $id_user)
    {
        if (!$id_user) {
            throw new \Exception("ID utilisateur manquant");
        }

        $portefeuilleModel = new Portefeuille();
        $porteFeuille = $portefeuilleModel->where('utilisateur_id', $id_user)->first();

        // If not found, attempt to generate and refetch
        if (!$porteFeuille) {
            echo "<br>Portefeuille non trouvé pour l'utilisateur ID: $id_user. Tentative de création...";
            self::generetePortefeuilleForUser($id_user);
            $porteFeuille = $portefeuilleModel->where('utilisateur_id', $id_user)->first();

            if (!$porteFeuille) {
                throw new \Exception("Impossible de créer le portefeuille");
            }
        }

        return $porteFeuille;
    }

    private static function validateTransaction(int $code_id, int $portefeuille_id)
    {
        $transactionModel = new TransactionPortefeuille();
        $transaction = $transactionModel->where('code_bonus_id', $code_id)
            ->where('portefeuille_id', $portefeuille_id)
            ->first();

        if ($transaction) {
            throw new \Exception("Code bonus déjà utilisé");
        }
    }

    public static function generetePortefeuilleForUser($id_user)
    {
        $porteFeuilleModel = new Portefeuille();
        if (!$id_user) {
            throw new \Exception("ID utilisateur manquant");
        }

        $data = ['utilisateur_id' => $id_user, 'solde_points' => 0];
        if ($porteFeuilleModel->insert($data) === false) {
            $errors = implode(', ', $porteFeuilleModel->errors());
            throw new \Exception("Erreur de création de portefeuille: " . $errors);
        }
    }
    // save transaction historique
    public static function saveTransactionHistorique(int $id_user, int $points, ?int $code_bonus_id, string $type, string $description)
    {
        $porteFeuille = self::validatePortefeuille($id_user);
        $transactionModel = new TransactionPortefeuille();
        $transactionModel->insert([
            'portefeuille_id' => $porteFeuille['id'],
            'code_bonus_id' => $code_bonus_id,
            'montant' => $points,
            'type' => $type,
            'description' => $description
        ]);
    }
}
