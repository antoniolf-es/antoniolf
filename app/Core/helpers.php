<?php

declare(strict_types=1);

function e(null|string|int|float $texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function url(string $ruta = ''): string
{
    return BASE_URL . $ruta;
}

function ruta_actual(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    if (RUTA_BASE !== '' && str_starts_with($uri, RUTA_BASE)) {
        $uri = substr($uri, strlen(RUTA_BASE));
    }

    if ($uri === '' || $uri === '/index.php') {
        return '/';
    }

    return '/' . trim($uri, '/');
}

function enlace_activo(string $ruta, string $prefijo = ''): string
{
    $actual = ruta_actual();

    if ($prefijo !== '') {
        return str_starts_with($actual, $prefijo) ? ' active' : '';
    }

    return $actual === $ruta ? ' active' : '';
}

function fecha_es(null|string|int $fecha): string
{
    $marca = strtotime((string) $fecha);

    if ($marca === false) {
        return '';
    }

    $meses = [
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre',
    ];

    return sprintf('%d de %s de %d', (int) date('j', $marca), $meses[(int) date('n', $marca)], (int) date('Y', $marca));
}

function flash(string $clave, mixed $valor): void
{
    $_SESSION['_flash'][$clave] = $valor;
}

function flash_obtener(string $clave): mixed
{
    if (!array_key_exists($clave, $_SESSION['_flash'] ?? [])) {
        return null;
    }

    $valor = $_SESSION['_flash'][$clave];
    unset($_SESSION['_flash'][$clave]);

    return $valor;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_valido(): bool
{
    $token = $_POST['_token'] ?? '';

    return is_string($token) && $token !== '' && hash_equals(csrf_token(), $token);
}

function dato_post(string $clave): string
{
    $valor = $_POST[$clave] ?? '';

    return is_string($valor) ? trim($valor) : '';
}

function autenticado(): bool
{
    return isset($_SESSION['usuario']);
}

function usuario(): ?array
{
    return autenticado() ? $_SESSION['usuario'] : null;
}

function requerir_login(): void
{
    if (!autenticado()) {
        flash('error', 'Debes iniciar sesión para acceder a la intranet.');
        header('Location: ' . url('/intranet'));
        exit;
    }
}

function slugificar(string $texto): string
{
    $tabla = [
        'Á' => 'A', 'á' => 'a', 'À' => 'A', 'à' => 'a', 'Â' => 'A', 'â' => 'a', 'Ã' => 'A', 'ã' => 'a',
        'Ä' => 'A', 'ä' => 'a', 'Å' => 'A', 'å' => 'a', 'Ç' => 'C', 'ç' => 'c',
        'É' => 'E', 'é' => 'e', 'Ê' => 'E', 'ê' => 'e', 'Ë' => 'E', 'ë' => 'e',
        'Í' => 'I', 'í' => 'i', 'Ì' => 'I', 'ì' => 'i', 'Î' => 'I', 'î' => 'i', 'Ï' => 'I', 'ï' => 'i',
        'Ñ' => 'N', 'ñ' => 'n',
        'Ó' => 'O', 'ó' => 'o', 'Ò' => 'O', 'ò' => 'o', 'Ô' => 'O', 'ô' => 'o', 'Õ' => 'O', 'õ' => 'o',
        'Ö' => 'O', 'ö' => 'o',
        'Ú' => 'U', 'ú' => 'u', 'Ù' => 'U', 'ù' => 'u', 'Û' => 'U', 'û' => 'u', 'Ü' => 'U', 'ü' => 'u',
        'Ý' => 'Y', 'ý' => 'y', 'ß' => 'ss',
    ];
    $texto = strtr($texto, $tabla);
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto) ?? '';

    return trim($texto, '-');
}
