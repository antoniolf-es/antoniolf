<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PortfolioModel;
use App\Models\TechModel;
use App\Services\ImagenPortfolio;

final class IntranetPortafolioController extends Controller
{
    public function __construct()
    {
        requerir_login();
    }

    public function index(): void
    {
        $portfolio = new PortfolioModel();
        $tech = new TechModel();

        $proyectos = array_map(
            fn (array $proyecto): array => $proyecto + [
                'tipo' => PortfolioModel::TIPOS[$proyecto['type']],
                'techs' => $tech->porIds($proyecto['tech']),
            ],
            $portfolio->todos()
        );

        $this->render('intranet/portafolio/index', [
            'titulo' => 'Portafolio',
            'proyectos' => $proyectos,
            'exito' => flash_obtener('exito'),
            'error' => flash_obtener('error'),
        ], 'intranet');
    }

    public function nuevo(): void
    {
        $this->pintarFormulario(true, null, $this->valoresPorDefecto(), []);
    }

    public function crear(): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/portafolio/nuevo');
        }

        $valores = $this->recoger();
        $errores = $this->validar($valores, null);
        $imagen = new ImagenPortfolio();

        if ($errores === [] && !$imagen->guardar($_FILES['imagen'] ?? [], $valores['slug'])) {
            $errores['imagen'] = $imagen->error() ?? 'La imagen no es válida.';
        }

        if ($errores !== []) {
            $this->pintarFormulario(true, null, $valores, $errores);
        }

        (new PortfolioModel())->crear([
            'titulo' => $valores['titulo'],
            'slug' => $valores['slug'],
            'descripcion' => $valores['descripcion'],
            'url' => $valores['url'],
            'image' => $valores['slug'],
            'tech' => implode(',', $valores['tech']),
            'type' => $valores['type'],
            'destacado' => $valores['destacado'],
        ]);

        flash('exito', 'Proyecto creado correctamente.');
        $this->redirigir('/intranet/portafolio');
    }

    public function editar(string $id): void
    {
        $proyecto = (new PortfolioModel())->porId((int) $id);

        if ($proyecto === null) {
            $this->mostrar404();
        }

        $valores = [
            'titulo' => $proyecto['titulo'],
            'slug' => $proyecto['slug'],
            'descripcion' => $proyecto['descripcion'],
            'url' => $proyecto['url'],
            'type' => $proyecto['type'],
            'tech' => array_map('intval', explode(',', $proyecto['tech'])),
            'destacado' => $proyecto['destacado'],
        ];

        $this->pintarFormulario(false, $proyecto, $valores, []);
    }

    public function guardar(string $id): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/portafolio/' . $id . '/editar');
        }

        $portfolio = new PortfolioModel();
        $proyecto = $portfolio->porId((int) $id);

        if ($proyecto === null) {
            $this->mostrar404();
        }

        $valores = $this->recoger();
        $errores = $this->validar($valores, $proyecto);
        $imagen = new ImagenPortfolio();
        $hayFichero = ($_FILES['imagen']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

        if ($errores === [] && $hayFichero && !$imagen->guardar($_FILES['imagen'], $proyecto['image'])) {
            $errores['imagen'] = $imagen->error() ?? 'La imagen no es válida.';
        }

        if ($errores !== []) {
            $this->pintarFormulario(false, $proyecto, $valores, $errores);
        }

        $portfolio->actualizar((int) $id, [
            'titulo' => $valores['titulo'],
            'slug' => $valores['slug'],
            'descripcion' => $valores['descripcion'],
            'url' => $valores['url'],
            'tech' => implode(',', $valores['tech']),
            'type' => $valores['type'],
            'destacado' => $valores['destacado'],
        ]);

        flash('exito', 'Proyecto actualizado correctamente.');
        $this->redirigir('/intranet/portafolio');
    }

    public function borrar(string $id): void
    {
        if (!csrf_valido()) {
            flash('error', 'Tu sesión ha caducado, vuelve a intentarlo.');
            $this->redirigir('/intranet/portafolio');
        }

        $portfolio = new PortfolioModel();
        $proyecto = $portfolio->porId((int) $id);

        if ($proyecto === null) {
            flash('error', 'El proyecto no existe.');
            $this->redirigir('/intranet/portafolio');
        }

        $portfolio->borrar((int) $id);
        ImagenPortfolio::borrar($proyecto['image']);

        flash('exito', 'Proyecto eliminado correctamente.');
        $this->redirigir('/intranet/portafolio');
    }

    private function pintarFormulario(bool $esAlta, null|array $proyecto, array $valores, array $errores): void
    {
        $this->render('intranet/portafolio/formulario', [
            'titulo' => $esAlta ? 'Nuevo proyecto' : 'Editar proyecto',
            'accion' => $esAlta ? '/intranet/portafolio/nuevo' : '/intranet/portafolio/' . $proyecto['id'] . '/editar',
            'esAlta' => $esAlta,
            'proyecto' => $proyecto,
            'valores' => $valores,
            'errores' => $errores,
            'tipos' => PortfolioModel::TIPOS,
            'techs' => (new TechModel())->todos(),
        ], 'intranet');

        exit;
    }

    private function valoresPorDefecto(): array
    {
        return [
            'titulo' => '',
            'slug' => '',
            'descripcion' => '',
            'url' => '',
            'type' => 0,
            'tech' => [],
            'destacado' => 0,
        ];
    }

    private function recoger(): array
    {
        $tech = $_POST['tech'] ?? [];
        $tech = is_array($tech) ? array_map('intval', $tech) : [];
        $slug = dato_post('slug');

        return [
            'titulo' => dato_post('titulo'),
            'slug' => slugificar($slug !== '' ? $slug : dato_post('titulo')),
            'descripcion' => dato_post('descripcion'),
            'url' => dato_post('url'),
            'type' => (int) dato_post('type'),
            'tech' => array_values(array_unique(array_filter($tech, static fn (int $id): bool => $id > 0))),
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
        ];
    }

    private function validar(array $valores, null|array $actual): array
    {
        $errores = [];

        if ($valores['titulo'] === '') {
            $errores['titulo'] = 'El título es obligatorio.';
        } elseif (mb_strlen($valores['titulo']) > 255) {
            $errores['titulo'] = 'El título no puede superar los 255 caracteres.';
        }

        if ($valores['slug'] === '') {
            $errores['slug'] = 'No se ha podido generar un slug a partir del título.';
        } elseif (!preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $valores['slug'])) {
            $errores['slug'] = 'El slug solo puede tener letras minúsculas, números y guiones.';
        } elseif ((new PortfolioModel())->slugExiste($valores['slug'], $actual['id'] ?? null)) {
            $errores['slug'] = 'Ya existe un proyecto con ese slug.';
        }

        if ($valores['descripcion'] === '') {
            $errores['descripcion'] = 'La descripción es obligatoria.';
        } elseif (mb_strlen($valores['descripcion']) > 255) {
            $errores['descripcion'] = 'La descripción no puede superar los 255 caracteres.';
        }

        if ($valores['url'] !== '' && !preg_match('#^https?://#i', $valores['url'])) {
            $errores['url'] = 'La URL debe empezar por http:// o https://.';
        } elseif (mb_strlen($valores['url']) > 255) {
            $errores['url'] = 'La URL no puede superar los 255 caracteres.';
        }

        if (!array_key_exists($valores['type'], PortfolioModel::TIPOS)) {
            $errores['type'] = 'El tipo seleccionado no es válido.';
        }

        if ($valores['tech'] === []) {
            $errores['tech'] = 'Selecciona al menos una tecnología.';
        }

        return $errores;
    }
}
