<?php

namespace App\Services;

use App\Models\RegimePrix;

class RegimePrixService
{

    public static function getPrixByRegime(int $regimeId): array
    {
        $prixModel = new RegimePrix();
        $prixList = $prixModel
            ->where('regime_id', $regimeId)
            ->orderBy('duree_jours', 'ASC')
            ->findAll();

        $result = [];
        foreach ($prixList as $p) {
            $result[] = [
                'duree_jours' => $p['duree_jours'],
                'prix_base'   => (float) $p['prix_base']
            ];
        }
        return $result;
    }

    public static function getMinPrix(int $regimeId): ?array
    {
        $prixModel = new RegimePrix();
        $min = $prixModel
            ->select('prix_base, duree_jours')
            ->where('regime_id', $regimeId)
            ->orderBy('prix_base', 'ASC')
            ->first();

        if ($min) {
            return [
                'prix_base' => (float) $min['prix_base'],
                'duree_jours' => $min['duree_jours']
            ];
        }
        return null;
    }
}