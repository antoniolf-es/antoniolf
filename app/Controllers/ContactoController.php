<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ContactoModel;
use App\Services\Mailjet;
use App\Services\Recaptcha;

final class ContactoController extends Controller
{
    public function index(): void
    {
        $this->render('contacto/index', [
            'titulo' => 'Contacto',
            'errores' => flash_obtener('errores') ?? [],
            'antiguos' => flash_obtener('antiguos') ?? [],
        ]);
    }

    public function enviar(): void
    {
        if (!csrf_valido()) {
            flash('errores', ['_formulario' => 'Tu sesión ha caducado, vuelve a enviar el formulario.']);
            $this->redirigir('/contacto');
        }

        $nombre = dato_post('nombre');
        $email = dato_post('email');
        $asunto = dato_post('asunto');
        $mensaje = dato_post('mensaje');

        $errores = [];

        if ($nombre === '') {
            $errores['nombre'] = 'Escribe tu nombre.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Escribe un email válido.';
        }

        if ($asunto === '') {
            $errores['asunto'] = 'Indica un asunto.';
        }

        if (mb_strlen($mensaje) < 10) {
            $errores['mensaje'] = 'El mensaje debe tener al menos 10 caracteres.';
        }

        if ($errores !== []) {
            flash('errores', $errores);
            flash('antiguos', ['nombre' => $nombre, 'email' => $email, 'asunto' => $asunto, 'mensaje' => $mensaje]);
            $this->redirigir('/contacto');
        }

        $token = $_POST['g-recaptcha-response'] ?? '';

        if (!Recaptcha::verificar(is_string($token) ? trim($token) : '')) {
            flash('errores', ['_formulario' => 'No hemos podido verificar que eres una persona real. Inténtalo de nuevo.']);
            flash('antiguos', ['nombre' => $nombre, 'email' => $email, 'asunto' => $asunto, 'mensaje' => $mensaje]);
            $this->redirigir('/contacto');
        }

        if (!Mailjet::enviar($nombre, $email, $asunto, $mensaje)) {
            flash('errores', ['_formulario' => 'No se ha podido enviar el mensaje. Prueba de nuevo dentro de un rato.']);
            flash('antiguos', ['nombre' => $nombre, 'email' => $email, 'asunto' => $asunto, 'mensaje' => $mensaje]);
            $this->redirigir('/contacto');
        }

        (new ContactoModel())->crear($nombre, $email, $asunto, $mensaje);

        flash('exito', true);
        $this->redirigir('/contacto/enviado');
    }

    public function exito(): void
    {
        if (flash_obtener('exito') === null) {
            $this->redirigir('/contacto');
        }

        $this->render('contacto/exito', ['titulo' => 'Mensaje enviado']);
    }
}
