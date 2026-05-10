<?php

namespace App\Services;

use App\Models\Regime;
use App\Models\RegimePrix;
use App\Models\ActiviteSportive;
use App\Models\Utilisateur;

class HomeService
{
    public function getRegimes(): array
    {
        $regimeModel = new Regime();
        $regimes = $regimeModel->orderBy('created_at', 'DESC')->findAll();

        foreach ($regimes as &$r) {
            $prixOptions = (new RegimePrix())
                ->where('regime_id', $r['id'])
                ->orderBy('duree_jours', 'ASC')
                ->findAll();

            $r['variation'] = $r['variation_poids_kg'];
            $r['duree'] = $r['duree_jours'];
            $r['prix_options'] = [];
            foreach ($prixOptions as $po) {
                $r['prix_options'][] = [
                    'prix'  => number_format((float)$po['prix_base'], 2),
                    'duree' => $po['duree_jours'],
                ];
            }
        }

        return $regimes;
    }

    public function getActivites(): array
    {
        return (new ActiviteSportive())
            ->orderBy('nom', 'ASC')
            ->findAll();
    }

    public function getStats(): array
    {
        return [
            'utilisateurs' => (new Utilisateur())->countAllResults(),
            'regimes'      => (new Regime())->countAllResults(),
            'activites'    => (new ActiviteSportive())->countAllResults(),
        ];
    }
}
