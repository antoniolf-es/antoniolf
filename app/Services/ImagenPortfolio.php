<?php

declare(strict_types=1);

namespace App\Services;

final class ImagenPortfolio
{
    private const TAMANO_MAXIMO = 8 * 1024 * 1024;
    private const ANCHO_MAXIMO = 1200;
    private const CALIDAD = 82;
    private const MIME_ACEPTADOS = ['image/jpeg', 'image/png', 'image/webp'];

    private ?string $error = null;

    public function error(): ?string
    {
        return $this->error;
    }

    public function guardar(array $fichero, string $base): bool
    {
        $codigo = $fichero['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($codigo === UPLOAD_ERR_NO_FILE) {
            $this->error = 'Selecciona una imagen para el proyecto.';

            return false;
        }

        if ($codigo !== UPLOAD_ERR_OK) {
            $this->error = 'La subida de la imagen ha fallado, vuelve a intentarlo.';

            return false;
        }

        if (($fichero['size'] ?? 0) > self::TAMANO_MAXIMO) {
            $this->error = 'La imagen no puede pesar más de 8 MB.';

            return false;
        }

        $mime = $this->mime($fichero['tmp_name']);

        if ($mime === null || !in_array($mime, self::MIME_ACEPTADOS, true)) {
            $this->error = 'La imagen debe ser JPG, PNG o WebP.';

            return false;
        }

        $dimensiones = @getimagesize($fichero['tmp_name']);

        if ($dimensiones === false) {
            $this->error = 'El archivo no es una imagen válida.';

            return false;
        }

        $origen = $this->crearDesde($mime, $fichero['tmp_name']);

        if ($origen === false) {
            $this->error = 'No se ha podido procesar la imagen.';

            return false;
        }

        $anchoOrig = imagesx($origen);
        $altoOrig = imagesy($origen);
        $ancho = min($anchoOrig, self::ANCHO_MAXIMO);
        $alto = (int) round($altoOrig * $ancho / $anchoOrig);

        $destino = imagecreatetruecolor($ancho, $alto);
        imagefill($destino, 0, 0, imagecolorallocate($destino, 255, 255, 255));
        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $ancho, $alto, $anchoOrig, $altoOrig);

        $ruta = dirname(__DIR__, 2) . '/img/portfolio/' . $base . '_1.jpg';
        $guardado = imagejpeg($destino, $ruta, self::CALIDAD);

        if (!$guardado) {
            $this->error = 'No se ha podido guardar la imagen.';

            return false;
        }

        return true;
    }

    public static function borrar(string $base): void
    {
        $carpeta = dirname(__DIR__, 2) . '/img/portfolio';

        foreach (glob($carpeta . '/' . $base . '_*.jpg') ?: [] as $ruta) {
            @unlink($ruta);
        }
    }

    private function mime(string $ruta): ?string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($ruta);

        return is_string($mime) ? $mime : null;
    }

    private function crearDesde(string $mime, string $ruta): \GdImage|false
    {
        return match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($ruta),
            'image/png' => @imagecreatefrompng($ruta),
            'image/webp' => @imagecreatefromwebp($ruta),
            default => false,
        };
    }
}
