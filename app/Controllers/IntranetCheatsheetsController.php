<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CheatsheetModel;

final class IntranetCheatsheetsController extends Controller
{
    public function __construct()
    {
        requerir_login();
    }

    public function index(): void
    {
        $this->render('intranet/cheatsheets/index', [
            'titulo' => 'Cheatsheets',
            'cheatsheets' => (new CheatsheetModel())->todosAdmin(),
            'exito' => flash_obtener('exito'),
            'error' => flash_obtener('error'),
        ], 'intranet');
    }

    public function nuevo(): void
    {
        $this->pintarFormulario(true, null, $this->valoresPorDefecto(), []);
    }

    public function crear(): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/cheatsheets/nuevo');
        }

        $valores = $this->recoger();
        $errores = $this->validar($valores, null);

        if ($errores !== []) {
            $this->pintarFormulario(true, null, $valores, $errores);
        }

        (new CheatsheetModel())->crear([
            'titulo' => $valores['titulo'],
            'slug' => $valores['slug'],
            'image' => $valores['imagen'],
            'texto' => $valores['texto'],
            'orden' => $valores['orden'],
        ]);

        flash('exito', 'Cheatsheet creada correctamente.');
        $this->redirigir('/intranet/cheatsheets');
    }

    public function editar(string $id): void
    {
        $cheatsheet = (new CheatsheetModel())->porId((int) $id);

        if ($cheatsheet === null) {
            $this->mostrar404();
        }

        $valores = [
            'titulo' => $cheatsheet['titulo'],
            'slug' => $cheatsheet['slug'],
            'imagen' => $cheatsheet['image'],
            'orden' => $cheatsheet['orden'],
            'texto' => (string) $cheatsheet['texto'],
        ];

        $this->pintarFormulario(false, $cheatsheet, $valores, []);
    }

    public function guardar(string $id): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/cheatsheets/' . $id . '/editar');
        }

        $modelo = new CheatsheetModel();
        $cheatsheet = $modelo->porId((int) $id);

        if ($cheatsheet === null) {
            $this->mostrar404();
        }

        $valores = $this->recoger();
        $errores = $this->validar($valores, $cheatsheet);

        if ($errores !== []) {
            $this->pintarFormulario(false, $cheatsheet, $valores, $errores);
        }

        $modelo->actualizar((int) $id, [
            'titulo' => $valores['titulo'],
            'slug' => $valores['slug'],
            'image' => $valores['imagen'],
            'texto' => $valores['texto'],
            'orden' => $valores['orden'],
        ]);

        flash('exito', 'Cheatsheet actualizada correctamente.');
        $this->redirigir('/intranet/cheatsheets');
    }

    public function borrar(string $id): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/cheatsheets');
        }

        $modelo = new CheatsheetModel();
        $cheatsheet = $modelo->porId((int) $id);

        if ($cheatsheet === null) {
            flash('error', 'La cheatsheet no existe.');
            $this->redirigir('/intranet/cheatsheets');
        }

        $modelo->borrar((int) $id);

        flash('exito', 'Cheatsheet eliminada correctamente.');
        $this->redirigir('/intranet/cheatsheets');
    }

    private function pintarFormulario(bool $esAlta, null|array $cheatsheet, array $valores, array $errores): void
    {
        $this->render('intranet/cheatsheets/formulario', [
            'titulo' => $esAlta ? 'Nueva cheatsheet' : 'Editar cheatsheet',
            'accion' => $esAlta ? '/intranet/cheatsheets/nuevo' : '/intranet/cheatsheets/' . $cheatsheet['id'] . '/editar',
            'esAlta' => $esAlta,
            'cheatsheet' => $cheatsheet,
            'valores' => $valores,
            'errores' => $errores,
            'iconos' => (new CheatsheetModel())->iconosDisponibles(),
        ], 'intranet');

        exit;
    }

    private function valoresPorDefecto(): array
    {
        $iconos = (new CheatsheetModel())->iconosDisponibles();

        return [
            'titulo' => '',
            'slug' => '',
            'imagen' => $iconos[0] ?? '',
            'orden' => (new CheatsheetModel())->siguienteOrden(),
            'texto' => '',
        ];
    }

    private function recoger(): array
    {
        $slug = dato_post('slug');
        $orden = dato_post('orden');

        return [
            'titulo' => dato_post('titulo'),
            'slug' => slugificar($slug !== '' ? $slug : dato_post('titulo')),
            'imagen' => dato_post('imagen'),
            'orden' => $orden === '' ? 0 : (int) $orden,
            'texto' => dato_post('texto'),
        ];
    }

    private function validar(array $valores, null|array $actual): array
    {
        $errores = [];
        $modelo = new CheatsheetModel();

        if ($valores['titulo'] === '') {
            $errores['titulo'] = 'El título es obligatorio.';
        } elseif (mb_strlen($valores['titulo']) > 255) {
            $errores['titulo'] = 'El título no puede superar los 255 caracteres.';
        }

        if ($valores['slug'] === '') {
            $errores['slug'] = 'No se ha podido generar un slug a partir del título.';
        } elseif (!preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $valores['slug'])) {
            $errores['slug'] = 'El slug solo puede tener letras minúsculas, números y guiones.';
        } elseif ($modelo->slugExiste($valores['slug'], $actual['id'] ?? null)) {
            $errores['slug'] = 'Ya existe una cheatsheet con ese slug.';
        }

        if (!in_array($valores['imagen'], $modelo->iconosDisponibles(), true)) {
            $errores['imagen'] = 'Selecciona un icono válido.';
        }

        if ($valores['orden'] < 0) {
            $errores['orden'] = 'El orden no puede ser negativo.';
        }

        if (!$this->hayContenido($valores['texto'])) {
            $errores['texto'] = 'El contenido es obligatorio.';
        }

        return $errores;
    }

    private function hayContenido(string $texto): bool
    {
        if (trim(strip_tags($texto)) !== '') {
            return true;
        }

        return (bool) preg_match('/<(img|iframe|video|audio|hr|table)\b/i', $texto);
    }
}
