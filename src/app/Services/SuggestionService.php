<?php

namespace App\Services;

use App\Models\Regime;
use App\Models\RegimePrix;
use App\Models\RegimeActivite;
use App\Models\ActiviteSportive;

class SuggestionService
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Retourne une liste de régimes suggérés en fonction de l'objectif et de l'IMC.
     *
     * @param string $objectif   'augmenter_poids', 'reduire_poids', 'imc_ideal'
     * @param float|null $imc    IMC actuel de l'utilisateur (si connu)
     * @return array
     */
    public function getSuggestions(string $objectif, ?float $imc = null): array
    {
        $regimeModel = new Regime();

        // 1. Récupérer tous les régimes actifs
        $allRegimes = $regimeModel->findAll();

        // 2. Filtrer selon l'objectif
        $suggestions = [];
        foreach ($allRegimes as $regime) {
            $variation = (float) $regime['variation_poids_kg'];
            $ok = false;

            if ($objectif === 'augmenter_poids' && $variation > 0) {
                $ok = true;
            } elseif ($objectif === 'reduire_poids' && $variation < 0) {
                $ok = true;
            } elseif ($objectif === 'imc_ideal') {
                // Régimes équilibrés (variation proche de zéro)
                if (abs($variation) <= 0.5) {
                    $ok = true;
                }
            }

            if ($ok) {
                // Compléter avec les prix et les activités
                $regime['prix_options'] = $this->getPrixByRegime($regime['id']);
                $regime['activites'] = $this->getActivitesByRegime($regime['id']);
                $suggestions[] = $regime;
            }
        }

        // Limiter à 3 suggestions
        return array_slice($suggestions, 0, 3);
    }

    /**
     * Récupère les différentes durées et prix pour un régime.
     */
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

    /**
     * Récupère les activités sportives associées à un régime.
     */
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