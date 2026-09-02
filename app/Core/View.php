<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $vista, array $datos = []): string
    {
        $archivo = __DIR__ . '/../Views/' . $vista . '.php';

        if (!is_file($archivo)) {
            throw new \RuntimeException(sprintf('La vista "%s" no existe', $vista));
        }

        extract($datos, EXTR_SKIP);

        ob_start();
        include $archivo;
        $contenido = ob_get_clean();

        ob_start();
        include __DIR__ . '/../Views/layout/principal.php';

        return ob_get_clean();
    }
}
