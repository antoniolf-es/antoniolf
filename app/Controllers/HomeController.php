<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BlogModel;
use App\Models\PortfolioModel;
use App\Models\TechModel;

final class HomeController extends Controller
{
    public function index(): void
    {
        $blog = new BlogModel();
        $portfolio = new PortfolioModel();
        $tech = new TechModel();

        $posts = array_map(
            fn (array $post): array => $post + ['categoria' => $blog->categoria($post['category_id'])],
            $blog->ultimos(3)
        );

        $proyectos = array_map(
            fn (array $proyecto): array => $proyecto + [
                'tipo' => PortfolioModel::TIPOS[$proyecto['type']],
                'techs' => $tech->porIds($proyecto['tech']),
            ],
            $portfolio->destacados(3)
        );

        $this->render('home/index', [
            'titulo' => 'Inicio',
            'posts' => $posts,
            'proyectos' => $proyectos,
        ]);
    }
}
