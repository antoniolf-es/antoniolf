<?php

declare(strict_types=1);

namespace App\Models;

final class PortfolioModel
{
    public const TIPOS = [
        0 => 'Web',
        1 => 'App',
        2 => 'Vintage',
    ];

    private const PROYECTOS = [
        [
            'id' => 1,
            'slug' => 'antoniolf-v3',
            'titulo' => 'AntonioLF v3',
            'descripcion' => 'Reconstrucción de mi web personal: portfolio y blog con un MVC propio en PHP vanilla, MariaDB y Bootstrap, sin frameworks de por medio. Este mismo sitio.',
            'type' => 0,
            'url' => '',
            'tech' => '1,9,5,4',
            'imagen' => 'https://picsum.photos/seed/antoniolf-v3/800/450',
            'destacado' => true,
        ],
        [
            'id' => 2,
            'slug' => 'alftools',
            'titulo' => 'AlfTools',
            'descripcion' => 'Colección de utilidades online para desarrolladores: formateadores, generadores, conversores y otras herramientas de bolsillo.',
            'type' => 0,
            'url' => 'https://alftools.netlify.app',
            'tech' => '3,6,7',
            'imagen' => 'https://picsum.photos/seed/alftools/800/450',
            'destacado' => true,
        ],
        [
            'id' => 3,
            'slug' => 'pachangas',
            'titulo' => 'Pachangas',
            'descripcion' => 'App de gestión de partidos de fútbol entre amigos: convocatorias semanales, estadísticas por jugador y ranking de goleadores.',
            'type' => 1,
            'url' => '',
            'tech' => '1,2,4',
            'imagen' => 'https://picsum.photos/seed/pachangas/800/450',
            'destacado' => true,
        ],
        [
            'id' => 4,
            'slug' => 'miswebs',
            'titulo' => 'MisWebs',
            'descripcion' => 'Directorio personal de webs de referencia: diseño, desarrollo y recursos útiles que voy recopilando con los años.',
            'type' => 0,
            'url' => 'https://miswebs.antoniolf.es',
            'tech' => '8,2,5',
            'imagen' => 'https://picsum.photos/seed/miswebs/800/450',
            'destacado' => false,
        ],
        [
            'id' => 5,
            'slug' => 'jukebox',
            'titulo' => 'Jukebox Traducciones',
            'descripcion' => 'Antiguo módulo de letras y traducciones de canciones. Uno de los primeros proyectos web que mantuve durante años.',
            'type' => 2,
            'url' => '',
            'tech' => '11,12',
            'imagen' => 'https://picsum.photos/seed/jukebox/800/450',
            'destacado' => false,
        ],
        [
            'id' => 6,
            'slug' => 'qdtu',
            'titulo' => 'QDTU',
            'descripcion' => 'Mi primera web personal (2003): descargas, tutoriales, foro y todo el espíritu de la vieja escuela. La web que empezó todo.',
            'type' => 2,
            'url' => '',
            'tech' => '11,12,10',
            'imagen' => 'https://picsum.photos/seed/qdtu/800/450',
            'destacado' => false,
        ],
    ];

    public function tipos(): array
    {
        return self::TIPOS;
    }

    public function todos(null|int $tipo = null): array
    {
        $proyectos = self::PROYECTOS;

        if ($tipo !== null) {
            $proyectos = array_values(array_filter($proyectos, static fn (array $proyecto): bool => $proyecto['type'] === $tipo));
        }

        return $proyectos;
    }

    public function destacados(int $limite): array
    {
        $destacados = array_values(array_filter(self::PROYECTOS, static fn (array $proyecto): bool => $proyecto['destacado'] === true));

        return array_slice($destacados, 0, $limite);
    }

    public function porSlug(string $slug): ?array
    {
        foreach (self::PROYECTOS as $proyecto) {
            if ($proyecto['slug'] === $slug) {
                return $proyecto;
            }
        }

        return null;
    }
}
