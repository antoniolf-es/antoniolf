<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BlogModel;

final class BlogController extends Controller
{
    public function index(): void
    {
        $categoriaBruta = $_GET['categoria'] ?? '';
        $buscarBruto = $_GET['buscar'] ?? '';

        $categoria = is_string($categoriaBruta) && $categoriaBruta !== '' ? (int) $categoriaBruta : null;
        $buscar = is_string($buscarBruto) ? trim($buscarBruto) : '';

        $blog = new BlogModel();

        $posts = array_map(
            fn (array $post): array => $post + ['categoria' => $blog->categoria($post['category_id'])],
            $blog->todos($categoria, $buscar)
        );

        $this->render('blog/index', [
            'titulo' => 'Blog',
            'posts' => $posts,
            'categorias' => $blog->categorias(),
            'categoriaActual' => $categoria,
            'buscarActual' => $buscar,
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
