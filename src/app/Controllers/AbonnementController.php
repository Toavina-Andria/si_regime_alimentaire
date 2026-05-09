<?php

namespace App\Controllers;

use App\Services\AbonnementServices;

class AbonnementController extends BaseController
{
    /**
     * Display subscription page for a specific abonnement
     * 
     * GET /abonnement/{id}
     * 
     * @param int $abonnementId Abonnement ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function index($abonnementId = null)
    {
        // Check if user is logged in
        $userId = session()->get('user_id') ?? session()->get('id');
        if (!$userId) {
            return redirect()->to('/connexion')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

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

    /**
     * Process subscription (POST)
     * 
     * POST /abonnement/souscrire
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function souscrireRegime()
    {
        // Check if user is logged in
        $userId = session()->get('user_id') ?? session()->get('id');
        if (!$userId) {
            return redirect()->to('/connexion')->with('error', 'Veuillez vous connecter.');
        }

        // // Validate CSRF token
        // if (!$this->validate(['csrf_token' => 'required'])) {
        //     return redirect()->back()->with('error', 'Erreur CSRF. Veuillez réessayer.');
        // }

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

    /**
     * Display all available abonnements
     * 
     * GET /abonnements
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function liste()
    {
        // Check if user is logged in
        $userId = session()->get('user_id') ?? session()->get('id');
        if (!$userId) {
            return redirect()->to('/connexion')->with('error', 'Veuillez vous connecter.');
        }

        $abonnements = AbonnementServices::getAllAbonnements();
        $activeSubscription = AbonnementServices::getUserActiveSubscription($userId);

        return view('abonnement/liste', [
            'abonnements' => $abonnements,
            'activeSubscription' => $activeSubscription
        ]);
    }
}
