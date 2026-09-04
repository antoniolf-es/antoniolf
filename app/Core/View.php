<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $vista, array $datos = [], string $layout = 'principal'): string
    {
        $archivo = __DIR__ . '/../Views/' . $vista . '.php';

        if (!is_file($archivo)) {
            throw new \RuntimeException(sprintf('La vista "%s" no existe', $vista));
        }

        $plantilla = __DIR__ . '/../Views/layout/' . $layout . '.php';

        if (!is_file($plantilla)) {
            throw new \RuntimeException(sprintf('El layout "%s" no existe', $layout));
        }

        extract($datos, EXTR_SKIP);

        ob_start();
        include $archivo;
        $contenido = ob_get_clean();

        ob_start();
        include $plantilla;

        return ob_get_clean();
    }
}
