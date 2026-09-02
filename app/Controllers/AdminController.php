<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class AdminController extends Controller
{
    public function index(): void
    {
        $this->render('admin/login', [
            'titulo' => 'Acceso',
            'error' => flash_obtener('error'),
        ]);
    }

    public function entrar(): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/admin');
        }

        flash('error', 'Maqueta sin autenticación: el login se conectará a la tabla users en la siguiente fase.');
        $this->redirigir('/admin');
    }
}
