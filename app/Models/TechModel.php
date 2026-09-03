<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class TechModel
{
    public function todos(): array
    {
        $filas = Database::conexion()
            ->query('SELECT id, name AS nombre FROM techs ORDER BY id')
            ->fetchAll();

        return array_map(
            fn (array $fila): array => ['id' => (int) $fila['id'], 'nombre' => (string) $fila['nombre']],
            $filas
        );
    }

    public function porIds(string $idsSeparadosPorComas): array
    {
        $ids = array_values(array_filter(
            array_map('intval', explode(',', $idsSeparadosPorComas)),
            static fn (int $id): bool => $id > 0
        ));

        if ($ids === []) {
            return [];
        }

        $marcadores = implode(',', array_fill(0, count($ids), '?'));
        $consulta = Database::conexion()->prepare('SELECT id, name AS nombre FROM techs WHERE id IN (' . $marcadores . ') ORDER BY id');
        $consulta->execute($ids);

        return array_map(
            fn (array $fila): array => ['id' => (int) $fila['id'], 'nombre' => (string) $fila['nombre']],
            $consulta->fetchAll()
        );
    }
}
