<?php

declare(strict_types=1);

namespace App\Models;

final class TechModel
{
    private const TECHS = [
        ['id' => 1, 'nombre' => 'PHP'],
        ['id' => 2, 'nombre' => 'MySQL'],
        ['id' => 3, 'nombre' => 'JavaScript'],
        ['id' => 4, 'nombre' => 'jQuery'],
        ['id' => 5, 'nombre' => 'Bootstrap'],
        ['id' => 6, 'nombre' => 'HTML5'],
        ['id' => 7, 'nombre' => 'CSS3'],
        ['id' => 8, 'nombre' => 'Laravel'],
        ['id' => 9, 'nombre' => 'MariaDB'],
        ['id' => 10, 'nombre' => 'Flash'],
        ['id' => 11, 'nombre' => 'HTML'],
        ['id' => 12, 'nombre' => 'CSS'],
    ];

    public function todos(): array
    {
        return self::TECHS;
    }

    public function porIds(string $idsSeparadosPorComas): array
    {
        $ids = array_filter(array_map('intval', explode(',', $idsSeparadosPorComas)), static fn (int $id): bool => $id > 0);

        return array_values(array_filter(self::TECHS, static fn (array $tech): bool => in_array($tech['id'], $ids, true)));
    }
}
