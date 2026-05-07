<?php

namespace App\Services;
use App\Models\Regime;
use App\Models\RegimePrix;
use App\Models\RegimeActivite;

class RegimeService
{
    public static function getRegimePrixByRegimeId($regimeId)
    {
        $regimePrixModel = new RegimePrix();
        return $regimePrixModel->where('regime_id', $regimeId)->findAll();
    }

}
