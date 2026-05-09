<?php

namespace App\Controllers;

use App\Services\AbonnementServices;

class AbonnementController extends BaseController
{

    public function index($abonnementId = null)
    {
        // Check if user is logged in
        $userId = session()->get('user_id') ?? session()->get('id');
        self::checkLoggedIn();

        // Get abonnement details
        $abonnement = AbonnementServices::getAbonnement($abonnementId);
        if (!$abonnement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if user already has active subscription
        $activeSubscription = AbonnementServices::getUserActiveSubscription($userId);
        $data = [
            'abonnement' => $abonnement,
            'activeSubscription' => $activeSubscription,
            'userId' => $userId
        ];
        return view('abonnement/souscrire', $data);
    }
    // test utilisteur est connecté
    private function checkLoggedIn()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/connexion')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
    }
    public function souscrireRegime()
    {
        // Check if user is logged in
        $userId = session()->get('user_id') ?? session()->get('id');
        self::checkLoggedIn();


        // Get POST data
        $abonnementId = $this->request->getPost('abonnement_id');

        // Validate input
        if (!$abonnementId || !is_numeric($abonnementId)) {
            return redirect()->back()->with('error', 'ID abonnement invalide.');
        }

        // Call service to process subscription
        $result = AbonnementServices::buySubscription($userId, $abonnementId);

        if ($result['success']) {
            return redirect()->to('/dashboard')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }


    public function liste()
    {
        // Check if user is logged in
        $userId = session()->get('user_id') ?? session()->get('id');
        self::checkLoggedIn();

        $abonnements = AbonnementServices::getAllAbonnements();
        $activeSubscription = AbonnementServices::getUserActiveSubscription($userId);
        $data = [
            'abonnements' => $abonnements,
            'activeSubscription' => $activeSubscription
        ];
        return view('abonnement/liste', $data);
    }
}
