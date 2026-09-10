<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class CheatsheetModel
{
    public function todos(): array
    {
        $filas = Database::conexion()
            ->query('SELECT id, slug, title AS titulo, image, orden FROM cheatsheets ORDER BY orden, id')
            ->fetchAll();

        return array_map(
            fn (array $fila): array => $this->mapear($fila),
            $filas
        );
    }

    public function porSlug(string $slug): ?array
    {
        $consulta = Database::conexion()->prepare(
            'SELECT id, slug, title AS titulo, image, text AS texto, orden FROM cheatsheets WHERE slug = ?'
        );
        $consulta->execute([$slug]);

        $fila = $consulta->fetch();

        return $fila === false ? null : $this->mapear($fila);
    }

    public function todosAdmin(): array
    {
        $filas = Database::conexion()
            ->query('SELECT id, title AS titulo, slug, image, orden, created_at AS fecha FROM cheatsheets ORDER BY orden, id')
            ->fetchAll();

        return array_map(
            fn (array $fila): array => $this->mapearAdmin($fila),
            $filas
        );
    }

    public function porId(int $id): ?array
    {
        $consulta = Database::conexion()->prepare(
            'SELECT id, title AS titulo, slug, image, text AS texto, orden, created_at AS fecha FROM cheatsheets WHERE id = ?'
        );
        $consulta->execute([$id]);

        $fila = $consulta->fetch();

        return $fila === false ? null : $this->mapearAdmin($fila);
    }

    public function slugExiste(string $slug, null|int $ignorarId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM cheatsheets WHERE slug = ?';
        $parametros = [$slug];

        if ($ignorarId !== null) {
            $sql .= ' AND id <> ?';
            $parametros[] = $ignorarId;
        }

        $consulta = Database::conexion()->prepare($sql);
        $consulta->execute($parametros);

        return (int) $consulta->fetchColumn() > 0;
    }

    public function crear(array $cheatsheet): int
    {
        $consulta = Database::conexion()->prepare(
            'INSERT INTO cheatsheets (title, slug, image, text, orden, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())'
        );
        $consulta->execute([
            $cheatsheet['titulo'],
            $cheatsheet['slug'],
            $cheatsheet['image'],
            $cheatsheet['texto'],
            $cheatsheet['orden'],
        ]);

        return (int) Database::conexion()->lastInsertId();
    }

    public function actualizar(int $id, array $cheatsheet): void
    {
        $consulta = Database::conexion()->prepare(
            'UPDATE cheatsheets
             SET title = ?, slug = ?, image = ?, text = ?, orden = ?, updated_at = NOW()
             WHERE id = ?'
        );
        $consulta->execute([
            $cheatsheet['titulo'],
            $cheatsheet['slug'],
            $cheatsheet['image'],
            $cheatsheet['texto'],
            $cheatsheet['orden'],
            $id,
        ]);
    }

    public function borrar(int $id): void
    {
        $consulta = Database::conexion()->prepare('DELETE FROM cheatsheets WHERE id = ?');
        $consulta->execute([$id]);
    }

    public function iconosDisponibles(): array
    {
        $directorio = dirname(__DIR__, 2) . '/img/tech';
        $ficheros = is_dir($directorio) ? scandir($directorio) : false;

        return $ficheros === false ? [] : array_values(array_filter(
            $ficheros,
            static fn (string $fichero): bool => str_ends_with($fichero, '.svg')
        ));
    }

    public function siguienteOrden(): int
    {
        return (int) Database::conexion()
            ->query('SELECT COALESCE(MAX(orden), 0) + 1 FROM cheatsheets')
            ->fetchColumn();
    }

    private function mapearAdmin(array $fila): array
    {
        return [
            'id' => (int) $fila['id'],
            'titulo' => (string) $fila['titulo'],
            'slug' => (string) $fila['slug'],
            'image' => (string) $fila['image'],
            'imagen' => url('/img/tech/' . $fila['image']),
            'orden' => (int) $fila['orden'],
            'fecha' => $fila['fecha'] ?? null,
            'texto' => isset($fila['texto']) ? (string) $fila['texto'] : null,
        ];
    }

    private function mapear(array $fila): array
    {
        return [
            'id' => (int) $fila['id'],
            'titulo' => (string) $fila['titulo'],
            'slug' => (string) $fila['slug'],
            'imagen' => url('/img/tech/' . $fila['image']),
            'orden' => (int) $fila['orden'],
            'texto' => (string) ($fila['texto'] ?? ''),
        ];
    }
}
