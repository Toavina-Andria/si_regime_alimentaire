<?php
namespace App\Controllers;
use App\Models\Regime;
use App\Services\RegimeService;
class RegimeController extends BaseController
{
    public function index()
    {
        $regimeModel = new Regime();
        $regimes = $regimeModel->findAll();

        return view('regime/list', ['regimes' => $regimes]);
    }

    public function show($id)
    {
        $regimeModel = new Regime()->find($id);
        $regimePrix = RegimeService::getRegimePrixByRegimeId($id);

        if (!$regimeModel) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Régime avec ID $id non trouvé");
        }

        return view('regime/detail', ['regime' => $regimeModel, 'prix' => $regimePrix]);
    }
}
