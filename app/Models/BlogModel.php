<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class BlogModel
{
    private const COLUMNAS = 'id, slug, title AS titulo, intro AS resumen, text AS texto, image, category_id, created_at AS fecha';

    public function categorias(): array
    {
        return (new BlogCategoriaModel())->todos();
    }

    public function categoria(int $id): ?array
    {
        return (new BlogCategoriaModel())->porId($id);
    }

    public function todos(null|int $categoria = null, string $buscar = ''): array
    {
        $sql = 'SELECT ' . self::COLUMNAS . ' FROM blogs WHERE status = 1';
        $parametros = [];

        if ($categoria !== null) {
            $sql .= ' AND category_id = ?';
            $parametros[] = $categoria;
        }

        if ($buscar !== '') {
            $sql .= ' AND (title LIKE ? OR intro LIKE ? OR text LIKE ?)';
            $patron = '%' . $buscar . '%';
            $parametros[] = $patron;
            $parametros[] = $patron;
            $parametros[] = $patron;
        }

        $sql .= ' ORDER BY created_at DESC, id DESC';

        $consulta = Database::conexion()->prepare($sql);
        $consulta->execute($parametros);

        return array_map(
            fn (array $fila): array => $this->mapear($fila),
            $consulta->fetchAll()
        );
    }

    public function ultimos(int $limite): array
    {
        return array_slice($this->todos(), 0, $limite);
    }

    public function porSlug(string $slug): ?array
    {
        $consulta = Database::conexion()->prepare('SELECT ' . self::COLUMNAS . ' FROM blogs WHERE slug = ? AND status = 1');
        $consulta->execute([$slug]);

        $fila = $consulta->fetch();

        return $fila === false ? null : $this->mapear($fila);
    }

    public function total(): int
    {
        return (int) Database::conexion()
            ->query('SELECT COUNT(*) FROM blogs WHERE status = 1')
            ->fetchColumn();
    }

    public function vecinos(int $id): array
    {
        $posts = Database::conexion()
            ->query('SELECT id, slug, title AS titulo FROM blogs WHERE status = 1 ORDER BY created_at DESC, id DESC')
            ->fetchAll();

        $posicion = array_search($id, array_map(static fn (array $post): int => (int) $post['id'], $posts), true);

        if ($posicion === false) {
            return ['anterior' => null, 'siguiente' => null];
        }

        return [
            'siguiente' => $posicion > 0 ? $posts[$posicion - 1] : null,
            'anterior' => $posicion + 1 < count($posts) ? $posts[$posicion + 1] : null,
        ];
    }

    public function todosAdmin(): array
    {
        $filas = Database::conexion()
            ->query(
                'SELECT b.id, b.title AS titulo, b.slug, b.image, b.status, b.category_id, b.created_at AS fecha,
                        c.name AS categoria
                 FROM blogs b
                 LEFT JOIN blogs_categories c ON c.id = b.category_id
                 ORDER BY b.created_at DESC, b.id DESC'
            )
            ->fetchAll();

        return array_map(
            fn (array $fila): array => $this->mapearAdmin($fila),
            $filas
        );
    }

    public function porId(int $id): ?array
    {
        $consulta = Database::conexion()->prepare(
            'SELECT id, title AS titulo, slug, intro AS resumen, text AS texto, image, status, category_id, user_id
             FROM blogs WHERE id = ?'
        );
        $consulta->execute([$id]);

        $fila = $consulta->fetch();

        if ($fila === false) {
            return null;
        }

        return [
            'id' => (int) $fila['id'],
            'titulo' => (string) $fila['titulo'],
            'slug' => (string) $fila['slug'],
            'resumen' => (string) $fila['resumen'],
            'texto' => (string) $fila['texto'],
            'image' => $fila['image'] === null ? null : (string) $fila['image'],
            'status' => (int) $fila['status'],
            'category_id' => $fila['category_id'] === null ? null : (int) $fila['category_id'],
            'user_id' => (int) $fila['user_id'],
        ];
    }

    public function slugExiste(string $slug, null|int $ignorarId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM blogs WHERE slug = ?';
        $parametros = [$slug];

        if ($ignorarId !== null) {
            $sql .= ' AND id <> ?';
            $parametros[] = $ignorarId;
        }

        $consulta = Database::conexion()->prepare($sql);
        $consulta->execute($parametros);

        return (int) $consulta->fetchColumn() > 0;
    }

    public function crear(array $post): int
    {
        $consulta = Database::conexion()->prepare(
            'INSERT INTO blogs (title, slug, intro, text, image, status, category_id, user_id, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())'
        );
        $consulta->execute([
            $post['titulo'],
            $post['slug'],
            $post['resumen'],
            $post['texto'],
            $post['image'],
            $post['status'],
            $post['category_id'],
            $post['user_id'],
        ]);

        return (int) Database::conexion()->lastInsertId();
    }

    public function actualizar(int $id, array $post): void
    {
        $consulta = Database::conexion()->prepare(
            'UPDATE blogs
             SET title = ?, slug = ?, intro = ?, text = ?, image = ?, status = ?, category_id = ?, updated_at = NOW()
             WHERE id = ?'
        );
        $consulta->execute([
            $post['titulo'],
            $post['slug'],
            $post['resumen'],
            $post['texto'],
            $post['image'],
            $post['status'],
            $post['category_id'],
            $id,
        ]);
    }

    public function borrar(int $id): void
    {
        $consulta = Database::conexion()->prepare('DELETE FROM blogs WHERE id = ?');
        $consulta->execute([$id]);
    }

    private function mapearAdmin(array $fila): array
    {
        $fila['id'] = (int) $fila['id'];
        $fila['status'] = (int) $fila['status'];
        $fila['category_id'] = $fila['category_id'] === null ? null : (int) $fila['category_id'];
        $fila['categoria'] = $fila['categoria'] === null ? null : (string) $fila['categoria'];
        $fila['imagen'] = $fila['image'] === null || $fila['image'] === ''
            ? url('/assets/img/logo.svg')
            : url('/img/blog/' . $fila['image']);
        unset($fila['image']);

        return $fila;
    }

    private function mapear(array $fila): array
    {
        $fila['id'] = (int) $fila['id'];
        $fila['category_id'] = $fila['category_id'] === null ? null : (int) $fila['category_id'];
        $fila['imagen'] = $fila['image'] === null || $fila['image'] === ''
            ? url('/assets/img/logo.svg')
            : url('/img/blog/' . $fila['image']);
        unset($fila['image']);

        return $fila;
    }
}
