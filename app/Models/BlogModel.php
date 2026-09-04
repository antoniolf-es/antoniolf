<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class BlogModel
{
    private const COLUMNAS = 'id, slug, title AS titulo, intro AS resumen, text AS texto, image, category_id, created_at AS fecha';

    public function categorias(): array
    {
        $filas = Database::conexion()
            ->query('SELECT id, name AS nombre FROM blogs_categories ORDER BY id')
            ->fetchAll();

        return array_map(
            fn (array $fila): array => ['id' => (int) $fila['id'], 'nombre' => (string) $fila['nombre']],
            $filas
        );
    }

    public function categoria(int $id): ?array
    {
        $consulta = Database::conexion()->prepare('SELECT id, name AS nombre FROM blogs_categories WHERE id = ?');
        $consulta->execute([$id]);

        $fila = $consulta->fetch();

        return $fila === false ? null : ['id' => (int) $fila['id'], 'nombre' => (string) $fila['nombre']];
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
