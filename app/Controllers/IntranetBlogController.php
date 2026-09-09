<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BlogCategoriaModel;
use App\Models\BlogModel;
use App\Services\ImagenBlog;
use App\Services\RedactorIA;

final class IntranetBlogController extends Controller
{
    private const ARTICULOS_INICIO = 15;

    public function __construct()
    {
        requerir_login();
    }

    public function index(): void
    {
        $todosBruto = $_GET['todos'] ?? '';
        $verTodos = is_string($todosBruto) && $todosBruto === '1';

        $blog = new BlogModel();

        $this->render('intranet/blog/index', [
            'titulo' => 'Blog',
            'posts' => $blog->todosAdmin($verTodos ? null : self::ARTICULOS_INICIO),
            'total' => $blog->totalAdmin(),
            'verTodos' => $verTodos,
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
            $this->redirigir('/intranet/blog/nuevo');
        }

        $valores = $this->recoger();
        $errores = $this->validar($valores, null);
        $imagen = new ImagenBlog();

        if ($errores === [] && !$imagen->guardar($_FILES['imagen'] ?? [], $valores['slug'])) {
            $errores['imagen'] = $imagen->error() ?? 'La imagen no es válida.';
        }

        if ($errores !== []) {
            $this->pintarFormulario(true, null, $valores, $errores);
        }

        (new BlogModel())->crear([
            'titulo' => $valores['titulo'],
            'slug' => $valores['slug'],
            'resumen' => $valores['resumen'],
            'texto' => $valores['texto'],
            'image' => $valores['slug'] . '.jpg',
            'status' => $valores['status'],
            'category_id' => $valores['categoria'],
            'user_id' => (int) usuario()['id'],
        ]);

        flash('exito', 'Artículo creado correctamente.');
        $this->redirigir('/intranet/blog');
    }

    public function editar(string $id): void
    {
        $post = (new BlogModel())->porId((int) $id);

        if ($post === null) {
            $this->mostrar404();
        }

        $valores = [
            'titulo' => $post['titulo'],
            'slug' => $post['slug'],
            'resumen' => $post['resumen'],
            'texto' => $post['texto'],
            'status' => $post['status'],
            'categoria' => $post['category_id'],
        ];

        $this->pintarFormulario(false, $post, $valores, []);
    }

    public function guardar(string $id): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/blog/' . $id . '/editar');
        }

        $blog = new BlogModel();
        $post = $blog->porId((int) $id);

        if ($post === null) {
            $this->mostrar404();
        }

        $valores = $this->recoger();
        $errores = $this->validar($valores, $post);
        $imagen = new ImagenBlog();
        $hayFichero = ($_FILES['imagen']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        $imagenFinal = $post['image'];

        if ($errores === [] && $hayFichero && !$imagen->guardar($_FILES['imagen'], $valores['slug'])) {
            $errores['imagen'] = $imagen->error() ?? 'La imagen no es válida.';
        }

        if ($errores !== []) {
            $this->pintarFormulario(false, $post, $valores, $errores);
        }

        if ($hayFichero) {
            $imagenFinal = $valores['slug'] . '.jpg';

            if ($post['image'] !== null && $post['image'] !== $imagenFinal) {
                ImagenBlog::borrar($post['image']);
            }
        }

        $blog->actualizar((int) $id, [
            'titulo' => $valores['titulo'],
            'slug' => $valores['slug'],
            'resumen' => $valores['resumen'],
            'texto' => $valores['texto'],
            'image' => $imagenFinal,
            'status' => $valores['status'],
            'category_id' => $valores['categoria'],
        ]);

        flash('exito', 'Artículo actualizado correctamente.');
        $this->redirigir('/intranet/blog');
    }

    public function borrar(string $id): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/blog');
        }

        $blog = new BlogModel();
        $post = $blog->porId((int) $id);

        if ($post === null) {
            flash('error', 'El artículo no existe.');
            $this->redirigir('/intranet/blog');
        }

        $blog->borrar((int) $id);
        ImagenBlog::borrar($post['image']);

        flash('exito', 'Artículo eliminado correctamente.');
        $this->redirigir('/intranet/blog');
    }

    public function mejorarIa(): void
    {
        if (!csrf_valido()) {
            $this->json(['ok' => false, 'error' => 'Tu sesión ha caducado, vuelve a intentarlo.'], 403);
        }

        $titulo = dato_post('titulo');
        $texto = dato_post('texto');

        if (!$this->hayContenido($texto)) {
            $this->json(['ok' => false, 'error' => 'Escribe primero algo de contenido en el artículo.'], 422);
        }

        if (mb_strlen($texto) > RedactorIA::MAX_CARACTERES) {
            $this->json(['ok' => false, 'error' => 'El contenido supera el máximo de ' . number_format(RedactorIA::MAX_CARACTERES, 0, ',', '.') . ' caracteres para revisarlo con IA.'], 422);
        }

        $mejorado = RedactorIA::mejorar($titulo, $texto);

        if ($mejorado === null) {
            $this->json(['ok' => false, 'error' => RedactorIA::error() ?? 'No se ha podido mejorar el texto con IA.'], 502);
        }

        $this->json(['ok' => true, 'texto' => $mejorado]);
    }

    private function json(array $datos, int $codigo = 200): never
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    private function pintarFormulario(bool $esAlta, null|array $post, array $valores, array $errores): void
    {
        $this->render('intranet/blog/formulario', [
            'titulo' => $esAlta ? 'Nuevo artículo' : 'Editar artículo',
            'accion' => $esAlta ? '/intranet/blog/nuevo' : '/intranet/blog/' . $post['id'] . '/editar',
            'esAlta' => $esAlta,
            'post' => $post,
            'valores' => $valores,
            'errores' => $errores,
            'categorias' => (new BlogCategoriaModel())->todos(),
        ], 'intranet');

        exit;
    }

    private function valoresPorDefecto(): array
    {
        return [
            'titulo' => '',
            'slug' => '',
            'resumen' => '',
            'texto' => '',
            'status' => 0,
            'categoria' => null,
        ];
    }

    private function recoger(): array
    {
        $slug = dato_post('slug');
        $categoria = dato_post('categoria');

        return [
            'titulo' => dato_post('titulo'),
            'slug' => slugificar($slug !== '' ? $slug : dato_post('titulo')),
            'resumen' => dato_post('resumen'),
            'texto' => dato_post('texto'),
            'status' => isset($_POST['status']) ? 1 : 0,
            'categoria' => $categoria !== '' ? (int) $categoria : null,
        ];
    }

    private function validar(array $valores, null|array $actual): array
    {
        $errores = [];
        $blog = new BlogModel();

        if ($valores['titulo'] === '') {
            $errores['titulo'] = 'El título es obligatorio.';
        } elseif (mb_strlen($valores['titulo']) > 255) {
            $errores['titulo'] = 'El título no puede superar los 255 caracteres.';
        }

        if ($valores['slug'] === '') {
            $errores['slug'] = 'No se ha podido generar un slug a partir del título.';
        } elseif (!preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $valores['slug'])) {
            $errores['slug'] = 'El slug solo puede tener letras minúsculas, números y guiones.';
        } elseif ($blog->slugExiste($valores['slug'], $actual['id'] ?? null)) {
            $errores['slug'] = 'Ya existe un artículo con ese slug.';
        }

        if ($valores['resumen'] === '') {
            $errores['resumen'] = 'El resumen es obligatorio.';
        } elseif (mb_strlen($valores['resumen']) > 255) {
            $errores['resumen'] = 'El resumen no puede superar los 255 caracteres.';
        }

        if (!$this->hayContenido($valores['texto'])) {
            $errores['texto'] = 'El contenido es obligatorio.';
        }

        if ($valores['categoria'] !== null && (new BlogCategoriaModel())->porId($valores['categoria']) === null) {
            $errores['categoria'] = 'La categoría seleccionada no existe.';
        }

        return $errores;
    }

    private function hayContenido(string $texto): bool
    {
        if (trim(strip_tags($texto)) !== '') {
            return true;
        }

        return (bool) preg_match('/<(img|iframe|video|audio)\b/i', $texto);
    }
}
