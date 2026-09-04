<?php

declare(strict_types=1);

namespace App\Services;

final class Mailjet
{
    private const URL_ENVIO = 'https://api.mailjet.com/v3.1/send';

    public static function enviar(string $nombre, string $email, string $asunto, string $mensaje): bool
    {
        if ((string) MAILJET_API === '' || (string) MAILJET_SECRET === '') {
            return false;
        }

        $datos = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => (string) MAILJET_REMITE,
                        'Name' => 'Web AntonioLF',
                    ],
                    'ReplyTo' => [
                        'Email' => $email,
                        'Name' => $nombre,
                    ],
                    'To' => [
                        [
                            'Email' => (string) MAILJET_DESTINO,
                            'Name' => 'Antonio LF',
                        ],
                    ],
                    'Subject' => 'Formulario de contacto: ' . $asunto,
                    'TextPart' => self::texto($nombre, $email, $asunto, $mensaje),
                    'HTMLPart' => self::html($nombre, $email, $asunto, $mensaje),
                ],
            ],
        ];

        return self::solicitar($datos);
    }

    private static function solicitar(array $datos): bool
    {
        $curl = curl_init(self::URL_ENVIO);

        if ($curl === false) {
            return false;
        }

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($datos, JSON_THROW_ON_ERROR),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode((string) MAILJET_API . ':' . (string) MAILJET_SECRET),
            ],
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);

        curl_exec($curl);

        $codigo = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($codigo !== 200) {
            error_log(sprintf('Mailjet: fallo al enviar (HTTP %d%s)', $codigo, $error !== '' ? ' - ' . $error : ''));

            return false;
        }

        return true;
    }

    private static function texto(string $nombre, string $email, string $asunto, string $mensaje): string
    {
        return "Has recibido un mensaje desde el formulario de contacto:\n\n"
            . 'Nombre: ' . $nombre . "\n"
            . 'Email: ' . $email . "\n"
            . 'Asunto: ' . $asunto . "\n\n"
            . $mensaje . "\n";
    }

    private static function html(string $nombre, string $email, string $asunto, string $mensaje): string
    {
        $lineas = '';

        foreach (preg_split('/\R/u', $mensaje) ?: [] as $linea) {
            $lineas .= '<p>' . nl2br(e($linea)) . '</p>';
        }

        return '<h2>Nuevo mensaje de contacto</h2>'
            . '<p><strong>Nombre:</strong> ' . e($nombre) . '<br>'
            . '<strong>Email:</strong> ' . e($email) . '<br>'
            . '<strong>Asunto:</strong> ' . e($asunto) . '</p>'
            . $lineas;
    }
}
