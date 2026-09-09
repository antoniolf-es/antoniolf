<?php

declare(strict_types=1);

namespace App\Services;

final class RedactorIA
{
    private const URL_CHAT = 'https://api.openai.com/v1/chat/completions';
    private const TIMEOUT = 55;

    public const MAX_CARACTERES = 30000;

    private static ?string $error = null;

    public static function mejorar(string $titulo, string $texto): ?string
    {
        self::$error = null;

        if ((string) OPENAI_API_KEY === '') {
            return self::fallo('Falta la clave de OpenAI (OPENAI_API_KEY).');
        }

        set_time_limit(60);

        $datos = [
            'model' => (string) OPENAI_MODEL,
            'messages' => [
                ['role' => 'system', 'content' => self::instrucciones()],
                ['role' => 'user', 'content' => self::encargo($titulo, $texto)],
            ],
        ];

        if (str_starts_with((string) OPENAI_MODEL, 'gpt-5')) {
            $datos['reasoning_effort'] = 'low';
        }

        $respuesta = self::solicitar($datos);

        if ($respuesta === null) {
            return null;
        }

        $contenido = $respuesta['choices'][0]['message']['content'] ?? null;

        if (!is_string($contenido) || trim($contenido) === '') {
            return self::fallo('La respuesta de OpenAI no ha devuelto texto.');
        }

        return self::limpiar($contenido);
    }

    public static function error(): ?string
    {
        return self::$error;
    }

    private static function solicitar(array $datos): ?array
    {
        $curl = curl_init(self::URL_CHAT);

        if ($curl === false) {
            return self::fallo('No se ha podido iniciar la petición a OpenAI.');
        }

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($datos, JSON_THROW_ON_ERROR),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . (string) OPENAI_API_KEY,
            ],
            CURLOPT_TIMEOUT => self::TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);

        $cuerpo = curl_exec($curl);

        $codigo = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $fallo = curl_error($curl);

        if (!is_string($cuerpo) || $codigo !== 200) {
            return self::fallo(self::mensajeApi($codigo, $fallo, is_string($cuerpo) ? $cuerpo : ''));
        }

        $descodificado = json_decode($cuerpo, true);

        if (!is_array($descodificado)) {
            return self::fallo('La respuesta de OpenAI no es válida.');
        }

        return $descodificado;
    }

    private static function mensajeApi(int $codigo, string $fallo, string $cuerpo): string
    {
        $detalle = '';

        if ($cuerpo !== '') {
            $json = json_decode($cuerpo, true);
            $mensaje = $json['error']['message'] ?? null;
            $detalle = is_string($mensaje) ? mb_substr($mensaje, 0, 200) : '';
        }

        error_log(sprintf('OpenAI: fallo al revisar el artículo (HTTP %d%s%s)', $codigo, $fallo !== '' ? ' - ' . $fallo : '', $detalle !== '' ? ' - ' . $detalle : ''));

        if ($detalle !== '') {
            return 'OpenAI: ' . $detalle;
        }

        return 'No se ha podido contactar con OpenAI (HTTP ' . $codigo . ').';
    }

    private static function instrucciones(): string
    {
        return 'Eres un redactor y corrector profesional de blogs de tecnología en español de España. '
            . "Recibes el borrador de un artículo (en HTML) escrito por un desarrollador web y devuelves una versión mejorada del mismo artículo.\n\n"
            . "Reglas:\n"
            . "1. Devuelve únicamente el HTML mejorado del artículo, sin explicaciones, sin comentarios y sin vallas de código.\n"
            . "2. Conserva la estructura y el sentido originales: mismos apartados, mismo orden y la misma información.\n"
            . "3. Usa solo etiquetas HTML simples (p, h2, h3, h4, strong, em, b, i, u, ul, ol, li, a, blockquote, pre, code, img, iframe, figure, figcaption, table). No añadas scripts ni atributos class o style; conserva los atributos ya existentes.\n"
            . "4. No inventes datos, cifras, fechas, nombres, tecnologías, enlaces ni imágenes que no aparezcan en el original.\n"
            . "5. Corrige ortografía, gramática y puntuación, y mejora la redacción: frases claras y directas, párrafos coherentes y transiciones naturales.\n"
            . "6. Mantén el tono profesional y cercano del autor y no cambies el idioma del texto.\n"
            . '7. Si el borrador ya está bien, devuelve el texto con correcciones mínimas.';
    }

    private static function encargo(string $titulo, string $texto): string
    {
        $encargo = '';

        if ($titulo !== '') {
            $encargo .= 'Título del artículo: ' . $titulo . "\n\n";
        }

        return $encargo . "Borrador en HTML:\n" . $texto;
    }

    private static function limpiar(string $texto): string
    {
        $texto = trim($texto);

        if (preg_match('/^```[a-z]*\s*(.+?)\s*```$/is', $texto, $coincidencia) === 1) {
            $texto = trim($coincidencia[1]);
        }

        return $texto;
    }

    private static function fallo(string $mensaje): null
    {
        self::$error = $mensaje;

        return null;
    }
}
