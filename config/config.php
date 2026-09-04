<?php

declare(strict_types=1);

$ficheroEnv = dirname(__DIR__, 2) . '/.env';

if (is_file($ficheroEnv)) {
    $pares = parse_ini_file($ficheroEnv, false, INI_SCANNER_RAW) ?: [];

    foreach ($pares as $clave => $valor) {
        $_ENV[(string) $clave] = $valor;
    }
}

function env(string $clave, ?string $defecto = null): ?string
{
    return isset($_ENV[$clave]) ? (string) $_ENV[$clave] : $defecto;
}

$esquema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$servidor = $_SERVER['HTTP_HOST'] ?? 'localhost';
$rutaBase = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');

// WEB
define('SITE_NAME', 'AntonioLF');
define('BASE_URL', $esquema . '://' . $servidor . $rutaBase);
define('RUTA_BASE', $rutaBase);
define('GITHUB_URL', 'https://github.com/antoniolf-es');
define('LINKEDIN_URL', 'http://es.linkedin.com/pub/antonio-lopez-fernandez/49/956/39a');

// RECAPTCHA
define('RECAPTCHA_V3_SITE_KEY', env('RECAPTCHA_V3_SITE_KEY'));
define('RECAPTCHA_V3_SECRET_KEY', env('RECAPTCHA_V3_SECRET_KEY'));

// MAILJET
define('MAILJET_API', env('MAILJET_API'));
define('MAILJET_SECRET', env('MAILJET_SECRET'));
define('MAILJET_REMITE', env('MAILJET_REMITE'));
define('MAILJET_DESTINO', env('MAILJET_DESTINO'));