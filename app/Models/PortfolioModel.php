<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class PortfolioModel
{
    public const TIPOS = [
        0 => 'Web',
        1 => 'Web-App',
        2 => 'Movil',
        3 => 'Legacy'
    ];

    private const COLUMNAS = 'id, slug, title AS titulo, description AS descripcion, url, image, tech, type, destacado';

    public function tipos(): array
    {
        return self::TIPOS;
    }

    public function todos(null|int $tipo = null): array
    {
        $sql = 'SELECT ' . self::COLUMNAS . ' FROM portfolios';
        $parametros = [];

        if ($tipo !== null) {
            $sql .= ' WHERE type = ?';
            $parametros[] = $tipo;
        }

        $sql .= ' ORDER BY id';

        $consulta = Database::conexion()->prepare($sql);
        $consulta->execute($parametros);

        return array_map(
            fn (array $fila): array => $this->mapear($fila),
            $consulta->fetchAll()
        );
    }

    public function destacados(int $limite): array
    {
        $consulta = Database::conexion()->prepare('SELECT ' . self::COLUMNAS . ' FROM portfolios WHERE destacado = 1 ORDER BY id');
        $consulta->execute();

        return array_slice(
            array_map(fn (array $fila): array => $this->mapear($fila), $consulta->fetchAll()),
            0,
            $limite
        );
    }

    public function porSlug(string $slug): ?array
    {
        $consulta = Database::conexion()->prepare('SELECT ' . self::COLUMNAS . ' FROM portfolios WHERE slug = ?');
        $consulta->execute([$slug]);

        $fila = $consulta->fetch();

        return $fila === false ? null : $this->mapear($fila);
    }

    public function porId(int $id): ?array
    {
        $consulta = Database::conexion()->prepare('SELECT ' . self::COLUMNAS . ' FROM portfolios WHERE id = ?');
        $consulta->execute([$id]);

        $fila = $consulta->fetch();

        if ($fila === false) {
            return null;
        }

        return [
            'id' => (int) $fila['id'],
            'titulo' => (string) $fila['titulo'],
            'slug' => (string) $fila['slug'],
            'descripcion' => (string) $fila['descripcion'],
            'url' => (string) $fila['url'],
            'image' => (string) $fila['image'],
            'tech' => (string) $fila['tech'],
            'type' => (int) $fila['type'],
            'destacado' => (int) $fila['destacado'],
        ];
    }

    public function slugExiste(string $slug, null|int $ignorarId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM portfolios WHERE slug = ?';
        $parametros = [$slug];

        if ($ignorarId !== null) {
            $sql .= ' AND id <> ?';
            $parametros[] = $ignorarId;
        }

        $consulta = Database::conexion()->prepare($sql);
        $consulta->execute($parametros);

        return (int) $consulta->fetchColumn() > 0;
    }

    public function crear(array $proyecto): int
    {
        $consulta = Database::conexion()->prepare(
            'INSERT INTO portfolios (title, slug, description, url, image, tech, type, destacado, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())'
        );
        $consulta->execute([
            $proyecto['titulo'],
            $proyecto['slug'],
            $proyecto['descripcion'],
            $proyecto['url'],
            $proyecto['image'],
            $proyecto['tech'],
            $proyecto['type'],
            $proyecto['destacado'],
        ]);

        return (int) Database::conexion()->lastInsertId();
    }

    public function actualizar(int $id, array $proyecto): void
    {
        $consulta = Database::conexion()->prepare(
            'UPDATE portfolios
             SET title = ?, slug = ?, description = ?, url = ?, tech = ?, type = ?, destacado = ?, updated_at = NOW()
             WHERE id = ?'
        );
        $consulta->execute([
            $proyecto['titulo'],
            $proyecto['slug'],
            $proyecto['descripcion'],
            $proyecto['url'],
            $proyecto['tech'],
            $proyecto['type'],
            $proyecto['destacado'],
            $id,
        ]);
    }

    public function borrar(int $id): void
    {
        $consulta = Database::conexion()->prepare('DELETE FROM portfolios WHERE id = ?');
        $consulta->execute([$id]);
    }

    private function mapear(array $fila): array
    {
        $fila['id'] = (int) $fila['id'];
        $fila['type'] = (int) $fila['type'];
        $fila['destacado'] = (int) $fila['destacado'];
        $fila['imagen'] = url('/img/portfolio/' . $fila['image'] . '_1.jpg');
        unset($fila['image']);

        return $fila;
    }
}
