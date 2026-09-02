<header class="alf-cabecera container pt-5">
    <h1 class="alf-titulo-seccion">Portafolio</h1>
    <p class="text-secondary mb-4">Webs, aplicaciones y proyectos vintage en los que he trabajado estos años.</p>

    <div class="btn-group flex-wrap mb-2" role="group" aria-label="Filtrar proyectos por tipo">
        <button type="button" class="btn alf-btn-filtro active" data-filtro="todos">Todos</button>
        <?php foreach ($tipos as $idTipo => $nombre): ?>
        <button type="button" class="btn alf-btn-filtro" data-filtro="<?= $idTipo ?>"><?= e($nombre) ?></button>
        <?php endforeach; ?>
    </div>
</header>

<section class="container py-5">
    <div class="row g-4">
        <?php foreach ($proyectos as $proyecto): ?>
        <div class="col-md-6 col-lg-4 tarjeta-proyecto" data-tipo="<?= $proyecto['type'] ?>">
            <article class="card alf-tarjeta h-100">
                <a href="<?= url('/portafolio/' . e($proyecto['slug'])) ?>">
                    <img src="<?= e($proyecto['imagen']) ?>" class="card-img-top" alt="<?= e($proyecto['titulo']) ?>" loading="lazy">
                </a>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge alf-badge"><?= e($proyecto['tipo']) ?></span>
                        <?php if ($proyecto['url'] !== ''): ?>
                        <a href="<?= e($proyecto['url']) ?>" target="_blank" rel="noopener" class="alf-enlace-ext small" aria-label="Sitio externo de <?= e($proyecto['titulo']) ?>" title="Visitar sitio">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <h2 class="card-title h5 mb-2">
                        <a class="alf-enlace-tarjeta" href="<?= url('/portafolio/' . e($proyecto['slug'])) ?>"><?= e($proyecto['titulo']) ?></a>
                    </h2>
                    <p class="card-text text-secondary mb-3 flex-grow-1"><?= e($proyecto['descripcion']) ?></p>
                    <div class="mb-3">
                        <?php foreach ($proyecto['techs'] as $tech): ?>
                        <span class="alf-tech me-1 mb-1"><?= e($tech['nombre']) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <a class="btn btn-outline-primary btn-sm alf-btn-detalle mt-auto" href="<?= url('/portafolio/' . e($proyecto['slug'])) ?>">
                        Ver detalle<i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </article>
        </div>
        <?php endforeach; ?>
    </div>
    <p class="alf-sin-resultados text-secondary text-center mt-4 mb-0 d-none">
        No hay proyectos de este tipo por ahora.
    </p>
</section>
