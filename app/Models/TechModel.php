<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class TechModel
{
    private const COLUMNAS = 'id, name AS nombre, image';

    public function todos(): array
    {
        $filas = Database::conexion()
            ->query('SELECT ' . self::COLUMNAS . ' FROM techs ORDER BY id')
            ->fetchAll();

        return array_map(
            fn (array $fila): array => $this->mapear($fila),
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
        $consulta = Database::conexion()->prepare('SELECT ' . self::COLUMNAS . ' FROM techs WHERE id IN (' . $marcadores . ') ORDER BY id');
        $consulta->execute($ids);

        return array_map(
            fn (array $fila): array => $this->mapear($fila),
            $consulta->fetchAll()
        );
    }

    private function mapear(array $fila): array
    {
        return [
            'id' => (int) $fila['id'],
            'nombre' => (string) $fila['nombre'],
            'imagen' => url('/img/tech/' . $fila['image']),
        ];
    }
}
