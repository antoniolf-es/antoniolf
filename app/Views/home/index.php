<section class="alf-hero text-center">
    <div class="container">
        <p class="alf-saludo text-uppercase fw-bold mb-2">Desarrollador web · Portfolio · Blog</p>
        <ul class="alf-chips list-inline mb-0">
            <li class="list-inline-item"><img src="<?= url('/img/tech/php.svg') ?>" alt="">PHP</li>
            <li class="list-inline-item"><img src="<?= url('/img/tech/mysql.svg') ?>" alt="">Mysql</li>
            <li class="list-inline-item"><img src="<?= url('/img/tech/jquery.svg') ?>" alt="">jQuery</li>
            <li class="list-inline-item"><img src="<?= url('/img/tech/bootstrap.svg') ?>" alt="">Bootstrap</li>
            <li class="list-inline-item"><img src="<?= url('/img/tech/python.svg') ?>" alt="">Python</li>
            <li class="list-inline-item"><img src="<?= url('/img/tech/react.svg') ?>" alt="">React</li>
        </ul>
    </div>
</section>

<section class="container py-4 alf-seccion-ancho">
    <div class="d-flex align-items-end justify-content-between mb-4">
        <h3 class="alf-titulo-seccion mb-0">Últimos artículos</h3>
        <a class="alf-ver-todo small" href="<?= url('/blog') ?>">
            Ver todo el blog <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="row g-2">
        <?php foreach ($posts as $post): ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <?php include __DIR__ . '/../blog/_tarjeta.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="container pb-4 alf-seccion-ancho">
    <div class="d-flex align-items-end justify-content-between mb-4">
        <h3 class="alf-titulo-seccion mb-0">Proyectos destacados</h3>
        <a class="alf-ver-todo small" href="<?= url('/portafolio') ?>">
            Ver todo el portafolio <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="row g-2">
        <?php foreach ($proyectos as $proyecto): ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <article class="card alf-tarjeta h-100">
                <a href="<?= url('/portafolio/' . e($proyecto['slug'])) ?>">
                    <img src="<?= e($proyecto['imagen']) ?>" class="card-img-top" alt="<?= e($proyecto['titulo']) ?>" loading="lazy">
                </a>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge alf-badge<?= clase_badge_tipo($proyecto['type']) ?>"><?= e($proyecto['tipo']) ?></span>
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
                        <span class="alf-tech me-1 mb-1"><img src="<?= e($tech['imagen']) ?>" alt=""><?= e($tech['nombre']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
        </div>
        <?php endforeach; ?>
    </div>
</section>
