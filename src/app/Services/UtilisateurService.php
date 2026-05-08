<?php
namespace App\Services;
use App\Models\CodeBonus;
use App\Models\Portefeuille;
use App\Models\TransactionPortefeuille;

class UtilisateurService
{

    public static function redeemCode(string $code_bonus, int $id_user)
    {
        try {
            if (!$id_user) {
                throw new \Exception("ID utilisateur manquant");
            }
            if (!$code_bonus) {
                throw new \Exception("Code bonus manquant");
            }
            //  verifier si le code bonus existe et est valide
            // chercher code valide
            $code = new CodeBonus()->where('code', $code_bonus)->first();
            // test if code exist
            if ($code == null) {
                throw new \Exception("Code bonus invalide " . "+" . $code_bonus);
            }
            echo "<br>code ok";
            // test vaidite code
            if (!$code['est_valide'] || strtotime($code['expires_at']) < time()) {
                throw new \Exception("Code bonus expiré");
            }
            echo "<br>code valide";
            // get user porte feuille
            try {
                $porteFeuille = new Portefeuille()->where('utilisateur_id', $id_user)->first();
                echo "<br>porte feuille ok";
            } catch (\Throwable $th) {
                if ($th->getMessage() !== "Trying to access array offset on null") {
                    throw new \Exception("Error fetching portefeuille: " . $th->getMessage());
                } else {
                    echo "<br>no portefeuille found for user id: " . $id_user;
                    echo "<br>generating portefeuille for user id: " . $id_user;
                    UtilisateurService::generetePortefeuilleForUser($id_user);
                }
            }
            $porteFeuille = new Portefeuille()->where('utilisateur_id', $id_user)->first();
            echo "<br>create porte feuille ok";
            // test if code already used by user
            try {

                $transaction = new TransactionPortefeuille()->where('code_bonus_id', $code['id'])
                    ->where('portefeuille_id', $porteFeuille['id'])
                    ->first();
                if ($transaction) {
                    throw new \Exception("Code bonus déjà utilisé");
                }
            } catch (\Throwable $th) {
                if ($th->getMessage() !== "Trying to access array offset on null") {
                    throw new \Exception("Error checking transaction: " . $th->getMessage());
                }
            }
            echo "<br>check transaction ok";
            // test data for transaction
            echo "<br>code id: " . $code['id'];
            echo "<br>montant: " . $code['valeur_points'];
            echo "<br>description: " . "Rachat du code bonus : " . $code_bonus;
            echo "<br>portefeuille id: " . $porteFeuille['id'];
            // add transaction to transaction historique
            new TransactionPortefeuille()->insert([
                'portefeuille_id' => $porteFeuille['id'],
                'code_bonus_id' => $code['id'],
                'montant' => $code['valeur_points'],
                'type' => 'credit',
                'description' => "Rachat du code bonus : " . $code_bonus
            ]);
            echo "<br>insert transaction ok";
            // update porte feuille solde
            $porteFeuille->update($porteFeuille['id'], [
                'solde_points' => $porteFeuille['solde_points'] + $code['valeur_points']
            ]);

        } catch (\Throwable $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }

        return ['success' => true, 'message' => 'Code redeemed successfully'];

    }

    public static function generetePortefeuilleForUser($id_user)
    {
        try {
            if (!$id_user) {
                throw new \Exception("ID utilisateur manquant");
            }
            echo "generetePortefeuilleForUser for user id: " . $id_user;
            $porteFeuilleModel = new Portefeuille();
            $porteFeuilleModel->insert(['utilisateur_id' => $id_user, 'solde_points' => 0]);
        } catch (\Throwable $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }
}
