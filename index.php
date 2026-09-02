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

use App\Controllers\AdminController;
use App\Controllers\BlogController;
use App\Controllers\ContactoController;
use App\Controllers\HomeController;
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
$router->get('/admin', [AdminController::class, 'index']);
$router->post('/admin', [AdminController::class, 'entrar']);

$router->despachar();
