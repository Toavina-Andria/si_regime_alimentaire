<?php

namespace App\Controllers;
use App\Services\UtilisateurService;
class CodeController extends BaseController
{
    public function index(): string
    {
        return view('code/form');
    }

    //---------------------------------insert code---------------------------------
    public function verifier(): string
    {
        $code = $this->request->getPost('code');
        $id_user = 1;//TODO: attendre de la session session()->get('user_id');

        $utilisateurService = new UtilisateurService();
        $result = $utilisateurService->redeemCode($code, $id_user);
        $data = [];
        if ($result['success']) {
            $data['status'] = 1; // Code is valid
            $data['message'] = 'Code redeemed successfully!';
        } else {
            $data['status'] = 0; // Code is invalid
            $data['message'] = 'Invalid code.';
        }
        return view('code/form', $data);
    }
}