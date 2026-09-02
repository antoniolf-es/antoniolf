<?php

declare(strict_types=1);

namespace App\Models;

final class BlogModel
{
    private const CATEGORIAS = [
        ['id' => 1, 'nombre' => 'Miscelanea'],
        ['id' => 2, 'nombre' => 'Programas'],
        ['id' => 3, 'nombre' => 'Código Fuente'],
        ['id' => 4, 'nombre' => 'Trucos'],
        ['id' => 5, 'nombre' => 'Programación'],
    ];

    private const POSTS = [
        [
            'id' => 1,
            'slug' => 'mejora-visual-shortcuts',
            'titulo' => 'Mejora visual shortcuts',
            'resumen' => 'Quitar la molesta flecha que Windows añade a los accesos directos, sin instalar nada.',
            'texto' => <<<'HTML'
<p>Si hay algo que desluce el escritorio de Windows son las flechas que se añaden a todos los accesos directos. Afortunadamente se pueden quitar con un par de pasos y sin instalar ningún programa.</p>
<h3>Ocultar la flecha</h3>
<p>Basta con eliminar el valor <code>IsShortcut</code> del registro de Windows y reiniciar el explorador:</p>
<pre><code>reg delete "HKCR\lnkfile" /v IsShortcut /f</code></pre>
<p>Tras reiniciar, los accesos directos quedan limpios, sin la flecha en la esquina inferior. Si algún día quieres recuperarlos, vuelve a crear el valor y listo.</p>
HTML,
            'imagen' => 'https://picsum.photos/seed/mejora-visual-shortcuts/800/450',
            'fecha' => '2026-01-07',
            'category_id' => 4,
            'status' => 1,
            'destacado' => true,
        ],
        [
            'id' => 2,
            'slug' => 'dadroit-json-viewer',
            'titulo' => 'Dadroit JSON Viewer',
            'resumen' => 'Un gran programa para visualizar archivos JSON enormes de forma rápida y sencilla.',
            'texto' => <<<'HTML'
<p>Dadroit JSON Viewer es un visor de archivos JSON pensado para manejar ficheros enormes: es capaz de abrir cientos de megas en segundos, algo impensable en un editor normal.</p>
<h3>Lo que más me gusta</h3>
<ul>
<li>Árbol navegable con búsqueda instantánea</li>
<li>Resaltado de sintaxis y vista en modo grafo</li>
<li>Modo solo lectura: no toca tus datos</li>
</ul>
<p>Ideal cuando un log o una respuesta de API es demasiado grande para el editor de siempre.</p>
HTML,
            'imagen' => 'https://picsum.photos/seed/dadroit-json-viewer/800/450',
            'fecha' => '2025-09-05',
            'category_id' => 2,
            'status' => 1,
            'destacado' => true,
        ],
        [
            'id' => 3,
            'slug' => 'enums-en-php',
            'titulo' => 'Enums en PHP 8',
            'resumen' => 'Cómo los enums backed ayudan a modelar estados sin constantes sueltas por todo el código.',
            'texto' => <<<'HTML'
<p>Desde PHP 8.1 existen los enums nativos y encajan de maravilla para reemplazar esas constantes de clase sueltas que acababan duplicadas por todo el proyecto.</p>
<pre><code>enum Estado: string {
    case Borrador = 'borrador';
    case Publicado = 'publicado';
}

$estado = Estado::from('publicado');</code></pre>
<p>En la próxima versión de esta web los usaré para el campo <code>status</code> del blog y el <code>role</code> de usuarios, en lugar de comparar números mágicos.</p>
HTML,
            'imagen' => 'https://picsum.photos/seed/enums-en-php/800/450',
            'fecha' => '2025-06-14',
            'category_id' => 5,
            'status' => 1,
            'destacado' => true,
        ],
        [
            'id' => 4,
            'slug' => 'abrir-directorios-rapido',
            'titulo' => 'Abrir directorios + rápido',
            'resumen' => 'Un truco genial para abrir rápidamente el contenido de los directorios en Windows.',
            'texto' => <<<'HTML'
<p>Un truco rápido para abrir cualquier directorio sin andar navegando por el explorador: pulsa <code>Win + R</code>, pega la ruta y acepta.</p>
<p>También funciona escribiendo o pegando la ruta directamente en la barra de direcciones del Explorador de archivos. Si la carpeta está en el PATH, incluso puedes escribir solo su nombre.</p>
<p>Parece una tontería, pero cuando pruebas muchas rutas al día se agradece cada segundo ahorrado.</p>
HTML,
            'imagen' => 'https://picsum.photos/seed/abrir-directorios-rapido/800/450',
            'fecha' => '2025-01-12',
            'category_id' => 4,
            'status' => 1,
            'destacado' => false,
        ],
        [
            'id' => 5,
            'slug' => 'bulk-rename-utility',
            'titulo' => 'Bulk Rename Utility',
            'resumen' => 'La navaja suiza para renombrar archivos en masa y alguna cosa más.',
            'texto' => <<<'HTML'
<p>Bulk Rename Utility es, con diferencia, la herramienta más completa para renombrar archivos en masa en Windows. Su interfaz asusta al principio, pero cuando le pillas el truco no la sueltas.</p>
<h3>Algunos de sus superpoderes</h3>
<ul>
<li>Renombrado por patrones, numeración, fechas y etiquetas EXIF</li>
<li>Vista previa de los cambios antes de aplicarlos</li>
<li>Sustituciones con expresiones regulares</li>
</ul>
<p>Perfecta para ordenar colecciones de fotos, vídeos o cualquier montaña de archivos caótica.</p>
HTML,
            'imagen' => 'https://picsum.photos/seed/bulk-rename-utility/800/450',
            'fecha' => '2024-10-27',
            'category_id' => 2,
            'status' => 1,
            'destacado' => false,
        ],
        [
            'id' => 6,
            'slug' => 'recuva',
            'titulo' => 'Recuva',
            'resumen' => 'Tu aliado cuando necesites recuperar esos archivos que eliminaste por error.',
            'texto' => <<<'HTML'
<p>A todos nos ha pasado: borrar algo y darse cuenta justo después de vaciar la papelera. Recuva es la herramienta de Piriform para intentar rescatar esos archivos.</p>
<p>Funciona mejor si lo ejecutas cuanto antes y evitas escribir en el disco mientras tanto. Su asistente guía en tres pasos y permite filtrar por tipo de archivo.</p>
<p>No hace milagros con unidades muy machacadas, pero en la mayoría de casos rescata lo que parece perdido.</p>
HTML,
            'imagen' => 'https://picsum.photos/seed/recuva/800/450',
            'fecha' => '2024-08-04',
            'category_id' => 2,
            'status' => 1,
            'destacado' => false,
        ],
        [
            'id' => 7,
            'slug' => 'qttabbar',
            'titulo' => 'QTTabBar',
            'resumen' => 'Sistema de pestañas para el explorador de archivos en Windows 10.',
            'texto' => <<<'HTML'
<p>QTTabBar añade pestañas al Explorador de Windows, algo que los navegadores nos enseñaron hace décadas y que Microsoft se negó a incorporar durante años.</p>
<p>Además de las pestañas, permite grupos de pestañas (por ejemplo, una por proyecto), vista previa de imágenes al pasar el ratón y botones de navegación extra.</p>
<p>Windows 11 ya incluye pestañas nativas, pero en Windows 10 sigue siendo un imprescindible.</p>
HTML,
            'imagen' => 'https://picsum.photos/seed/qttabbar/800/450',
            'fecha' => '2024-07-25',
            'category_id' => 4,
            'status' => 1,
            'destacado' => false,
        ],
        [
            'id' => 8,
            'slug' => 'pdf24',
            'titulo' => 'PDF24',
            'resumen' => 'Probablemente la mejor herramienta para editar tus PDFs desde tu equipo.',
            'texto' => <<<'HTML'
<p>PDF24 Creator es una suite de PDFs gratuita que cubre prácticamente todo lo que se puede pedir: unir, dividir, comprimir, convertir, rotar, extraer páginas, OCR…</p>
<p>Su punto fuerte frente a las alternativas online es que todo se procesa en local, sin subir documentos sensibles a ningún servidor.</p>
<p>Incluye además una impresora virtual PDF y un visor ligero. De las primeras herramientas que instalo en cualquier equipo.</p>
HTML,
            'imagen' => 'https://picsum.photos/seed/pdf24/800/450',
            'fecha' => '2022-11-27',
            'category_id' => 2,
            'status' => 1,
            'destacado' => false,
        ],
    ];

    public function categorias(): array
    {
        return self::CATEGORIAS;
    }

    public function categoria(int $id): ?array
    {
        foreach (self::CATEGORIAS as $categoria) {
            if ($categoria['id'] === $id) {
                return $categoria;
            }
        }

        return null;
    }

    public function todos(null|int $categoria = null, string $buscar = ''): array
    {
        $posts = array_values(array_filter(self::POSTS, static fn (array $post): bool => $post['status'] === 1));

        if ($categoria !== null) {
            $posts = array_values(array_filter($posts, static fn (array $post): bool => $post['category_id'] === $categoria));
        }

        if ($buscar !== '') {
            $posts = array_values(array_filter($posts, static fn (array $post): bool => stripos($post['titulo'], $buscar) !== false || stripos($post['resumen'], $buscar) !== false));
        }

        return $posts;
    }

    public function ultimos(int $limite): array
    {
        return array_slice($this->todos(), 0, $limite);
    }

    public function porSlug(string $slug): ?array
    {
        foreach (self::POSTS as $post) {
            if ($post['slug'] === $slug && $post['status'] === 1) {
                return $post;
            }
        }

        return null;
    }

    public function vecinos(int $id): array
    {
        $posts = $this->todos();
        $ids = array_column($posts, 'id');
        $posicion = array_search($id, $ids, true);

        if ($posicion === false) {
            return ['anterior' => null, 'siguiente' => null];
        }

        return [
            'siguiente' => $posicion > 0 ? $posts[$posicion - 1] : null,
            'anterior' => $posicion + 1 < count($posts) ? $posts[$posicion + 1] : null,
        ];
    }
}
