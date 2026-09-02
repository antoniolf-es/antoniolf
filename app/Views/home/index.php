<section class="alf-hero text-center">
    <div class="container">
        <p class="alf-saludo text-uppercase fw-bold mb-2">Desarrollador web · Portfolio · Blog</p>
        <p class="lead text-secondary mx-auto mb-4" style="max-width: 640px;">
            Desarrollador web que aún disfruta escribiendo PHP a mano.<br>
            Aquí encontrarás mi portfolio, artículos de programación y contenidos relacionados con el desarrollo web.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap mb-4">
            <a class="btn btn-primary btn-lg alf-btn px-4" href="<?= url('/portafolio') ?>">
                <i class="bi bi-collection me-2"></i>Ver portafolio
            </a>
            <a class="btn btn-outline-secondary btn-lg px-4" href="<?= url('/contacto') ?>">
                <i class="bi bi-envelope me-2"></i>Contactar
            </a>
        </div>
        <ul class="alf-chips list-inline mb-0">
            <li class="list-inline-item">PHP</li>
            <li class="list-inline-item">MariaDB</li>
            <li class="list-inline-item">jQuery</li>
            <li class="list-inline-item">Laravel</li>
            <li class="list-inline-item">Python</li>
            <li class="list-inline-item">React</li>
        </ul>
    </div>
</section>

<section class="container py-5">
    <div class="d-flex align-items-end justify-content-between mb-4">
        <h2 class="alf-titulo-seccion mb-0">Últimos artículos</h2>
        <a class="alf-ver-todo small" href="<?= url('/blog') ?>">
            Ver todo el blog <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="row g-4">
        <?php foreach ($posts as $post): ?>
        <div class="col-md-6 col-lg-4">
            <?php include __DIR__ . '/../blog/_tarjeta.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="container pb-5">
    <div class="d-flex align-items-end justify-content-between mb-4">
        <h2 class="alf-titulo-seccion mb-0">Proyectos destacados</h2>
        <a class="alf-ver-todo small" href="<?= url('/portafolio') ?>">
            Ver todo el portafolio <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="row g-4">
        <?php foreach ($proyectos as $proyecto): ?>
        <div class="col-md-6 col-lg-4">
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
                    <h3 class="card-title mb-2">
                        <a class="alf-enlace-tarjeta" href="<?= url('/portafolio/' . e($proyecto['slug'])) ?>"><?= e($proyecto['titulo']) ?></a>
                    </h3>
                    <p class="card-text text-secondary mb-3 flex-grow-1"><?= e($proyecto['descripcion']) ?></p>
                    <div class="mb-0">
                        <?php foreach ($proyecto['techs'] as $tech): ?>
                        <span class="alf-tech me-1 mb-1"><?= e($tech['nombre']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
        </div>
        <?php endforeach; ?>
    </div>
</section>
