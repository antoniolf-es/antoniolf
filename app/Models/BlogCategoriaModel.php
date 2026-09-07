<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class BlogCategoriaModel
{
    public function todos(): array
    {
        $filas = Database::conexion()
            ->query(
                'SELECT c.id, c.name AS nombre, COUNT(b.id) AS posts
                 FROM blogs_categories c
                 LEFT JOIN blogs b ON b.category_id = c.id
                 GROUP BY c.id, c.name
                 ORDER BY c.id'
            )
            ->fetchAll();

        return array_map(
            fn (array $fila): array => $this->mapear($fila),
            $filas
        );
    }

    public function porId(int $id): ?array
    {
        $consulta = Database::conexion()->prepare('SELECT id, name AS nombre FROM blogs_categories WHERE id = ?');
        $consulta->execute([$id]);

        $fila = $consulta->fetch();

        return $fila === false ? null : $this->mapear($fila);
    }

    public function existe(string $nombre, null|int $ignorarId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM blogs_categories WHERE name = ?';
        $parametros = [$nombre];

        if ($ignorarId !== null) {
            $sql .= ' AND id <> ?';
            $parametros[] = $ignorarId;
        }

        $consulta = Database::conexion()->prepare($sql);
        $consulta->execute($parametros);

        return (int) $consulta->fetchColumn() > 0;
    }

    public function numeroDePosts(int $id): int
    {
        $consulta = Database::conexion()->prepare('SELECT COUNT(*) FROM blogs WHERE category_id = ?');
        $consulta->execute([$id]);

        return (int) $consulta->fetchColumn();
    }

    public function crear(string $nombre): int
    {
        $consulta = Database::conexion()->prepare('INSERT INTO blogs_categories (name) VALUES (?)');
        $consulta->execute([$nombre]);

        return (int) Database::conexion()->lastInsertId();
    }

    public function actualizar(int $id, string $nombre): void
    {
        $consulta = Database::conexion()->prepare('UPDATE blogs_categories SET name = ? WHERE id = ?');
        $consulta->execute([$nombre, $id]);
    }

    public function borrar(int $id): void
    {
        $consulta = Database::conexion()->prepare('DELETE FROM blogs_categories WHERE id = ?');
        $consulta->execute([$id]);
    }

    private function mapear(array $fila): array
    {
        return [
            'id' => (int) $fila['id'],
            'nombre' => (string) $fila['nombre'],
            'posts' => (int) ($fila['posts'] ?? 0),
        ];
    }
}
