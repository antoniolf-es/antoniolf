<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BlogModel;

final class BlogController extends Controller
{
    private const ARTICULOS_INICIO = 10;

    public function index(): void
    {
        $categoriaBruta = $_GET['categoria'] ?? '';
        $buscarBruto = $_GET['buscar'] ?? '';
        $todosBruto = $_GET['todos'] ?? '';

        $categoria = is_string($categoriaBruta) && $categoriaBruta !== '' ? (int) $categoriaBruta : null;
        $buscar = is_string($buscarBruto) ? trim($buscarBruto) : '';
        $verTodos = is_string($todosBruto) && $todosBruto === '1';

        $blog = new BlogModel();
        $sinFiltros = $categoria === null && $buscar === '';

        $posts = $sinFiltros && !$verTodos
            ? $blog->ultimos(self::ARTICULOS_INICIO)
            : $blog->todos($categoria, $buscar);

        $total = $blog->total();

        $posts = array_map(
            fn (array $post): array => $post + ['categoria' => $blog->categoria($post['category_id'])],
            $posts
        );

        $this->render('blog/index', [
            'titulo' => 'Blog',
            'posts' => $posts,
            'categorias' => $blog->categorias(),
            'categoriaActual' => $categoria,
            'buscarActual' => $buscar,
            'totalPosts' => $total,
            'postsLimitados' => $sinFiltros && !$verTodos && count($posts) < $total,
        ]);
    }

    public function ver(string $slug): void
    {
        $blog = new BlogModel();
        $post = $blog->porSlug($slug);

        if ($post === null) {
            $this->mostrar404();
        }

        $vecinos = $blog->vecinos($post['id']);

        $this->render('blog/ver', [
            'titulo' => $post['titulo'],
            'post' => $post + ['categoria' => $blog->categoria($post['category_id'])],
            'anterior' => $vecinos['anterior'],
            'siguiente' => $vecinos['siguiente'],
        ]);
    }
}
