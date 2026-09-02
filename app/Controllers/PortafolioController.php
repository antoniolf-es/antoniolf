<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PortfolioModel;
use App\Models\TechModel;

final class PortafolioController extends Controller
{
    public function index(): void
    {
        $portfolio = new PortfolioModel();
        $tech = new TechModel();

        $proyectos = array_map(
            fn (array $proyecto): array => $proyecto + [
                'tipo' => PortfolioModel::TIPOS[$proyecto['type']],
                'techs' => $tech->porIds($proyecto['tech']),
            ],
            $portfolio->todos()
        );

        $this->render('portafolio/index', [
            'titulo' => 'Portafolio',
            'proyectos' => $proyectos,
            'tipos' => PortfolioModel::TIPOS,
        ]);
    }

    public function ver(string $slug): void
    {
        $portfolio = new PortfolioModel();
        $proyecto = $portfolio->porSlug($slug);

        if ($proyecto === null) {
            $this->mostrar404();
        }

        $this->render('portafolio/ver', [
            'titulo' => $proyecto['titulo'],
            'proyecto' => $proyecto + [
                'tipo' => PortfolioModel::TIPOS[$proyecto['type']],
                'techs' => (new TechModel())->porIds($proyecto['tech']),
            ],
        ]);
    }
}
