<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BlogModel;
use App\Models\PortfolioModel;

final class IntranetDashboardController extends Controller
{
    public function __construct()
    {
        requerir_login();
    }

    public function index(): void
    {
        $proyectos = array_map(
            fn (array $proyecto): array => $proyecto + [
                'tipo' => PortfolioModel::TIPOS[$proyecto['type']],
            ],
            (new PortfolioModel())->ultimos(5)
        );

        $this->render('intranet/dashboard', [
            'titulo' => 'Dashboard',
            'posts' => (new BlogModel())->todosAdmin(5),
            'proyectos' => $proyectos,
        ], 'intranet');
    }
}
