<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $rutas = [];

    public function get(string $ruta, array $accion): void
    {
        $this->anadir('GET', $ruta, $accion);
    }

    public function post(string $ruta, array $accion): void
    {
        $this->anadir('POST', $ruta, $accion);
    }

    private function anadir(string $metodo, string $ruta, array $accion): void
    {
        $ruta = rtrim($ruta, '/') ?: '/';
        $patron = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $ruta) . '$#u';

        $this->rutas[] = ['metodo' => $metodo, 'patron' => $patron, 'accion' => $accion];
    }

    public function despachar(): void
    {
        $metodoPeticion = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($metodoPeticion === 'HEAD') {
            $metodoPeticion = 'GET';
        }

        $ruta = ruta_actual();

        foreach ($this->rutas as $rutaDefinida) {
            if ($rutaDefinida['metodo'] !== $metodoPeticion) {
                continue;
            }

            if (!preg_match($rutaDefinida['patron'], $ruta, $coincidencias)) {
                continue;
            }

            $parametros = array_values(array_filter($coincidencias, 'is_string', ARRAY_FILTER_USE_KEY));
            [$clase, $metodo] = $rutaDefinida['accion'];

            $controlador = new $clase();
            $controlador->{$metodo}(...$parametros);

            return;
        }

        $this->noEncontrado();
    }

    public function noEncontrado(): never
    {
        http_response_code(404);

        echo View::render('errors/404', ['titulo' => 'Página no encontrada']);
        exit;
    }
}
