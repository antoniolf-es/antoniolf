<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/config/config.php';
require __DIR__ . '/app/Core/helpers.php';

spl_autoload_register(static function (string $clase): void {
    if (!str_starts_with($clase, 'App\\')) {
        return;
    }

    $ruta = __DIR__ . '/app/' . str_replace('\\', '/', substr($clase, 4)) . '.php';

    if (is_file($ruta)) {
        require $ruta;
    }
});

use App\Controllers\BlogController;
use App\Controllers\ContactoController;
use App\Controllers\HomeController;
use App\Controllers\IntranetController;
use App\Controllers\IntranetPortafolioController;
use App\Controllers\PortafolioController;
use App\Core\Router;

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/blog', [BlogController::class, 'index']);
$router->get('/blog/{slug}', [BlogController::class, 'ver']);
$router->get('/portafolio', [PortafolioController::class, 'index']);
$router->get('/portafolio/{slug}', [PortafolioController::class, 'ver']);
$router->get('/contacto', [ContactoController::class, 'index']);
$router->post('/contacto', [ContactoController::class, 'enviar']);
$router->get('/contacto/enviado', [ContactoController::class, 'exito']);

$router->get('/intranet', [IntranetController::class, 'index']);
$router->post('/intranet', [IntranetController::class, 'entrar']);
$router->get('/intranet/salir', [IntranetController::class, 'salir']);
$router->get('/intranet/portafolio', [IntranetPortafolioController::class, 'index']);
$router->get('/intranet/portafolio/nuevo', [IntranetPortafolioController::class, 'nuevo']);
$router->post('/intranet/portafolio/nuevo', [IntranetPortafolioController::class, 'crear']);
$router->get('/intranet/portafolio/{id}/editar', [IntranetPortafolioController::class, 'editar']);
$router->post('/intranet/portafolio/{id}/editar', [IntranetPortafolioController::class, 'guardar']);
$router->post('/intranet/portafolio/{id}/borrar', [IntranetPortafolioController::class, 'borrar']);

$router->despachar();
