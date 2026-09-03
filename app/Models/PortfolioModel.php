<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class PortfolioModel
{
    public const TIPOS = [
        0 => 'Web',
        1 => 'App',
        2 => 'Vintage',
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
