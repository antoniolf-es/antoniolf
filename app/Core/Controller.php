<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $vista, array $datos = [], string $layout = 'principal'): void
    {
        echo View::render($vista, $datos, $layout);
    }

    protected function redirigir(string $ruta): never
    {
        header('Location: ' . url($ruta));
        exit;
    }

    protected function mostrar404(): never
    {
        http_response_code(404);

        echo View::render('errors/404', ['titulo' => 'Página no encontrada']);
        exit;
    }
}
