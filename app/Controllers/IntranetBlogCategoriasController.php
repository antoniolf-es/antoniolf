<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BlogCategoriaModel;

final class IntranetBlogCategoriasController extends Controller
{
    public function __construct()
    {
        requerir_login();
    }

    public function index(): void
    {
        $this->render('intranet/blog/categorias/index', [
            'titulo' => 'Categorías del blog',
            'categorias' => (new BlogCategoriaModel())->todos(),
            'exito' => flash_obtener('exito'),
            'error' => flash_obtener('error'),
        ], 'intranet');
    }

    public function nuevo(): void
    {
        $this->pintarFormulario(true, null, ['nombre' => ''], []);
    }

    public function crear(): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/blog/categorias/nuevo');
        }

        $valores = ['nombre' => dato_post('nombre')];
        $errores = $this->validar($valores, null);

        if ($errores !== []) {
            $this->pintarFormulario(true, null, $valores, $errores);
        }

        (new BlogCategoriaModel())->crear($valores['nombre']);

        flash('exito', 'Categoría creada correctamente.');
        $this->redirigir('/intranet/blog/categorias');
    }

    public function editar(string $id): void
    {
        $categoria = (new BlogCategoriaModel())->porId((int) $id);

        if ($categoria === null) {
            $this->mostrar404();
        }

        $this->pintarFormulario(false, $categoria, ['nombre' => $categoria['nombre']], []);
    }

    public function guardar(string $id): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/blog/categorias/' . $id . '/editar');
        }

        $modelo = new BlogCategoriaModel();
        $categoria = $modelo->porId((int) $id);

        if ($categoria === null) {
            $this->mostrar404();
        }

        $valores = ['nombre' => dato_post('nombre')];
        $errores = $this->validar($valores, $categoria);

        if ($errores !== []) {
            $this->pintarFormulario(false, $categoria, $valores, $errores);
        }

        $modelo->actualizar((int) $id, $valores['nombre']);

        flash('exito', 'Categoría actualizada correctamente.');
        $this->redirigir('/intranet/blog/categorias');
    }

    public function borrar(string $id): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/blog/categorias');
        }

        $modelo = new BlogCategoriaModel();
        $categoria = $modelo->porId((int) $id);

        if ($categoria === null) {
            flash('error', 'La categoría no existe.');
            $this->redirigir('/intranet/blog/categorias');
        }

        $posts = $modelo->numeroDePosts((int) $id);

        if ($posts > 0) {
            $flash = 'No se puede eliminar «' . $categoria['nombre'] . '»: tiene ' . $posts
                . ($posts === 1 ? ' artículo asociado.' : ' artículos asociados.');

            flash('error', $flash);
            $this->redirigir('/intranet/blog/categorias');
        }

        $modelo->borrar((int) $id);

        flash('exito', 'Categoría eliminada correctamente.');
        $this->redirigir('/intranet/blog/categorias');
    }

    private function pintarFormulario(bool $esAlta, null|array $categoria, array $valores, array $errores): void
    {
        $this->render('intranet/blog/categorias/formulario', [
            'titulo' => $esAlta ? 'Nueva categoría' : 'Editar categoría',
            'accion' => $esAlta
                ? '/intranet/blog/categorias/nuevo'
                : '/intranet/blog/categorias/' . $categoria['id'] . '/editar',
            'esAlta' => $esAlta,
            'categoria' => $categoria,
            'valores' => $valores,
            'errores' => $errores,
        ], 'intranet');

        exit;
    }

    private function validar(array $valores, null|array $actual): array
    {
        $errores = [];

        if ($valores['nombre'] === '') {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (mb_strlen($valores['nombre']) > 255) {
            $errores['nombre'] = 'El nombre no puede superar los 255 caracteres.';
        } elseif ((new BlogCategoriaModel())->existe($valores['nombre'], $actual['id'] ?? null)) {
            $errores['nombre'] = 'Ya existe una categoría con ese nombre.';
        }

        return $errores;
    }
}
