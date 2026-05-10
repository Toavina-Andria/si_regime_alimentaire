<?php

namespace App\Services;

use App\Models\Regime;
use App\Models\RegimePrix;
use App\Models\RegimeActivite;

class SuggestionService
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Retourne une liste de régimes suggérés pour l'IMC idéal.
     * Chaque élément contient : ['regime' => [], 'prixOptions' => [], 'activites' => []]
     */
    public function getSuggestions(string $objectif, ?float $imc = null): array
    {
        // Pour l'IMC idéal, on cherche les régimes équilibrés (variation proche de zéro)
        $regimes = (new Regime())
            ->where('variation_poids_kg >=', -0.5)
            ->where('variation_poids_kg <=', 0.5)
            ->findAll();

        $suggestions = [];
        foreach ($regimes as $regime) {
            $prixOptions = $this->getPrixByRegime($regime['id']);
            $activites = $this->getActivitesByRegime($regime['id']);

            $suggestions[] = [
                'regime'      => $regime,
                'prixOptions' => $prixOptions,
                'activites'   => $activites,
            ];

            if (count($suggestions) >= 3) break;
        }

        return $suggestions;
    }

    private function getPrixByRegime(int $regimeId): array
    {
        $prixModel = new RegimePrix();
        $prixList = $prixModel->where('regime_id', $regimeId)
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

    private function getActivitesByRegime(int $regimeId): array
    {
        $builder = $this->db->table('regime_activite ra');
        $builder->select('a.nom, a.description, a.intensite, a.calories_heure, ra.frequence_semaine');
        $builder->join('activite_sportive a', 'a.id = ra.activite_id');
        $builder->where('ra.regime_id', $regimeId);
        $builder->orderBy('a.intensite', 'ASC');

        return $builder->get()->getResultArray();
    }
}