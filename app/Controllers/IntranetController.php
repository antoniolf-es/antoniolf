<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UsuarioModel;

final class IntranetController extends Controller
{
    public function index(): void
    {
        if (autenticado()) {
            $this->redirigir('/intranet/portafolio');
        }

        $this->render('intranet/login', [
            'titulo' => 'Acceso',
            'error' => flash_obtener('error'),
            'email' => (string) (flash_obtener('email') ?? ''),
        ], 'intranet');
    }

    public function entrar(): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet');
        }

        $email = dato_post('email');
        $password = $_POST['password'] ?? '';
        $password = is_string($password) ? $password : '';

        if ($email === '' || $password === '') {
            flash('error', 'Introduce tu email y contraseña.');
            flash('email', $email);
            $this->redirigir('/intranet');
        }

        $usuario = (new UsuarioModel())->porEmail($email);

        if ($usuario === null || !password_verify($password, $usuario['password'])) {
            flash('error', 'Email o contraseña incorrectos.');
            flash('email', $email);
            $this->redirigir('/intranet');
        }

        if ($usuario['active'] !== 1) {
            flash('error', 'Tu cuenta está desactivada.');
            flash('email', $email);
            $this->redirigir('/intranet');
        }

        if ($usuario['role'] !== 1) {
            flash('error', 'Tu cuenta no tiene acceso a la intranet.');
            flash('email', $email);
            $this->redirigir('/intranet');
        }

        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
        ];

        $this->redirigir('/intranet/portafolio');
    }

    public function salir(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();

        header('Location: ' . url('/intranet'));
        exit;
    }
}
