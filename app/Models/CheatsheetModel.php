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
