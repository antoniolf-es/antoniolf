<?php

declare(strict_types=1);

namespace App\Services;

final class Recaptcha
{
    private const URL_VERIFICACION = 'https://www.google.com/recaptcha/api/siteverify';
    private const ACCION = 'contacto';
    private const UMBRAL_MINIMO = 0.5;

    public static function verificar(string $token): bool
    {
        if ($token === '' || (string) RECAPTCHA_V3_SECRET_KEY === '') {
            return false;
        }

        $respuesta = self::solicitar([
            'secret' => (string) RECAPTCHA_V3_SECRET_KEY,
            'response' => $token,
        ]);

        if ($respuesta === null) {
            return false;
        }

        return ($respuesta['success'] ?? false) === true
            && ($respuesta['action'] ?? '') === self::ACCION
            && (float) ($respuesta['score'] ?? 0.0) >= self::UMBRAL_MINIMO;
    }

    private static function solicitar(array $datos): ?array
    {
        $curl = curl_init(self::URL_VERIFICACION);

        if ($curl === false) {
            return null;
        }

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($datos),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);

        $cuerpo = curl_exec($curl);
        $codigo = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if (!is_string($cuerpo) || $cuerpo === '' || $codigo !== 200) {
            return null;
        }

        $descodificado = json_decode($cuerpo, true);

        return is_array($descodificado) ? $descodificado : null;
    }
}
