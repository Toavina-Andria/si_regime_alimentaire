<?php

namespace App\Controllers;

use App\Services\HomeService;

class Home extends BaseController
{
    public function index()
    {
        $service = new HomeService();

        $data = [
            'regimes'       => $service->getRegimes(),
            'activites'     => $service->getActivites(),
            'stats'         => $service->getStats(),
            'testimonials'  => [
                [
                    'name'    => 'Marie D.',
                    'avatar'  => '👩',
                    'text'    => 'Grâce à NutriPlan, j\'ai perdu 8 kg en 3 mois tout en mangeant équilibré. Le suivi est incroyable !',
                    'goal'    => 'Perte de poids',
                ],
                [
                    'name'    => 'Thomas L.',
                    'avatar'  => '👨',
                    'text'    => 'Les régimes sont variés et adaptés à mes besoins. L\'abonnement Gold m\'a fait économiser sur le long terme.',
                    'goal'    => 'IMC idéal',
                ],
                [
                    'name'    => 'Sophie M.',
                    'avatar'  => '👩‍🦰',
                    'text'    => 'Le suivi du poids et les activités recommandées m\'aident à rester motivée chaque jour.',
                    'goal'    => 'Prise de masse',
                ],
            ],
        ];

        return view('home/index', $data);
    }
}
