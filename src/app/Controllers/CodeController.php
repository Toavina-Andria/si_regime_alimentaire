<?php

namespace App\Controllers;
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
        $isValid = \App\Services\CodeService::verifierCode($code);
        $data = [];
        if ($isValid) {
            $data['status'] = 1; // Code is valid
            $data['msg'] = 'Code redeemed successfully!';
        } else {
            $data['status'] = 0; // Code is invalid
            $data['msg'] = 'Invalid code.';
        }
        return view('code/form', $data);
    }
}