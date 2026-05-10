<?php

namespace App\Controllers;
use App\Services\UtilisateurService;
use App\Models\CodeBonus;
class CodeController extends BaseController
{
    public function index(): string
    {
        return view('code/form');
    }

    //---------------------------------insert code---------------------------------
    public function verifier()
    {
        $code = $this->request->getPost('code');
        $id_user = session()->get('user_id');
        if ($id_user === null) {
            return redirect()->to('/')->with('error', 'Vous devez être connecté pour utiliser un code bonus.');
        }

        $result = UtilisateurService::redeemCode($code, $id_user);
        $data = [];
        if ($result['success']) {
            $data['status'] = 1; // Code is valid
            $data['message'] = $result['message'];
        } else {
            $data['status'] = 0; // Code is invalid
            $data['message'] = $result['message'];
        }
        return view('code/form', $data);
    }
}
