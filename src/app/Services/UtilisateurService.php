<?php
namespace App\Services;
use App\Models\CodeBonus;
use App\Models\Portefeuille;
use App\Models\TransactionPortefeuille;

class UtilisateurService
{

    public static function redeemCode($code_bonus, $id_user)
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
            $codeBonusModel = new CodeBonus();
            $code = $codeBonusModel->where('code', $code_bonus)->first();
            // test if code exist
            if (!$code) {
                throw new \Exception("Code bonus invalide");
            }
            // test vaidite code
            if (!$code['est_valide'] || strtotime($code['expires_at']) < time()) {
                throw new \Exception("Code bonus expiré");
            }
            // get user porte feuille
            $porteFeuille = new Portefeuille()->where('utilisateur_id', $id_user)->first();
            if (!$porteFeuille){
                UtilisateurService::generetePortefeuilleForUser($id_user);
            }
            // test if code already used by user
            $transactionModel = new TransactionPortefeuille()->where('code_bonus_id', $code['id'])
                ->where('portefeuille_id', $porteFeuille['id'])
                ->first();
            if ($transactionModel) {
                throw new \Exception("Code bonus déjà utilisé");
            }
            // add transaction to transaction historique
            $transaction = new TransactionPortefeuille();
            $transaction->insert([
                'portefeuille_id' => $porteFeuille['id'],
                'code_bonus_id' => $code['id'],
                'montant' => $code['valeur_points'],
                'type' => 'credit',
                'description' => "Rachat du code bonus : " . $code_bonus
            ]);
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
            $porteFeuilleModel = new Portefeuille();
            $porteFeuilleModel->insert(['utilisateur_id' => $id_user, 'solde_points' => 0]);
        } catch (\Throwable $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }
}
