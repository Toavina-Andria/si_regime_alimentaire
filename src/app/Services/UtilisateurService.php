<?php
namespace App\Services;
use App\Models\CodeBonus;
use App\Models\Portefeuille;
use App\Models\TransactionPortefeuille;
use App\Models\Utilisateur;

class UtilisateurService
{

    public static function redeemCode(string $code_bonus, int $id_user)
    {
        try {

            $code = self::validateCode($code_bonus);

            $porteFeuille = self::validatePortefeuille($id_user);

            self::validateTransaction($code['id'], $porteFeuille['id']);

            self::saveTransactionHistorique($id_user, $code['valeur_points'], $code['id'], 'credit', "Rachat du code bonus : " . $code_bonus);

            self::addPointsToPortefeuille($id_user, $code['valeur_points']);

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
        self::validateUser($id_user);

        $portefeuilleModel = new Portefeuille();
        $porteFeuille = $portefeuilleModel->where('utilisateur_id', $id_user)->first();

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
        self::validateUser($id_user);

        $data = ['utilisateur_id' => $id_user, 'solde_points' => 0];
        if ($porteFeuilleModel->insert($data) === false) {
            $errors = implode(', ', $porteFeuilleModel->errors());
            throw new \Exception("Erreur de création de portefeuille: " . $errors);
        }
    }

    public static function getPortefeuilleByUserId($id_user)
    {
        $portefeuilleModel = new Portefeuille();
        return $portefeuilleModel->where('utilisateur_id', $id_user)->first();
    }

    public static function payWithPoints(int $id_user, $points)
    {
        $porteFeuille = self::validatePortefeuille($id_user);
        if ($porteFeuille['solde_points'] < $points) {
            throw new \Exception("Solde insuffisant");
        }
        $portefeuilleModel = new Portefeuille();
        $portefeuilleModel->update($porteFeuille['id'], [
            'solde_points' => $porteFeuille['solde_points'] - $points
        ]);

    }

    public static function addPointsToPortefeuille(int $id_user, $points)
    {
        $porteFeuille = self::validatePortefeuille($id_user);
        $portefeuilleModel = new Portefeuille();
        $portefeuilleModel->update($porteFeuille['id'], [
            'solde_points' => $porteFeuille['solde_points'] + $points
        ]);
    }

    public static function saveTransactionHistorique(int $id_user, $points, ?int $code_bonus_id, string $type, string $description)
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

    public static function validateUser(int $id_user)
    {
        if (!$id_user) {
            throw new \Exception("ID utilisateur manquant");
        }
    }
}
