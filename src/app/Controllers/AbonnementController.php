<?php

namespace App\Controllers;

use App\Services\AbonnementServices;

class AbonnementController extends BaseController
{

    public function index($abonnementId = null)
    {
        $userId = session()->get('user_id') ?? session()->get('id');
        if ($redirect = self::checkLoggedIn()) return $redirect;
        if ($redirect = self::redirectAdmin()) return $redirect;

        $abonnement = AbonnementServices::getAbonnement($abonnementId);
        if (!$abonnement) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $activeSubscription = AbonnementServices::getUserActiveSubscription($userId);
        $data = [
            'abonnement' => $abonnement,
            'activeSubscription' => $activeSubscription,
            'userId' => $userId
        ];
        return view('abonnement/souscrire', $data);
    }
    private function checkLoggedIn()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/connexion')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
    }
    private function redirectAdmin()
    {
        $userId = session()->get('user_id');
        $user = model('App\Models\Utilisateur')->find($userId);
        if ($user && !empty($user['est_admin'])) {
            session()->set('est_admin', true);
            return redirect()->to('/admin/dashboard');
        }
    }
    public function souscrireRegime()
    {
        $userId = session()->get('user_id') ?? session()->get('id');
        self::checkLoggedIn();
        self::redirectAdmin();

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
        $userId = session()->get('user_id') ?? session()->get('id');
        if ($redirect = self::checkLoggedIn()) return $redirect;
        if ($redirect = self::redirectAdmin()) return $redirect;

        $abonnements = AbonnementServices::getAllAbonnements();
        $activeSubscription = AbonnementServices::getUserActiveSubscription($userId);
        $data = [
            'abonnements' => $abonnements,
            'activeSubscription' => $activeSubscription
        ];
        return view('abonnement/liste', $data);
    }
}
