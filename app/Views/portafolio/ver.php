<article class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <nav aria-label="migas de pan">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?= url('/') ?>">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/portafolio') ?>">Portafolio</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($proyecto['titulo']) ?></li>
                </ol>
            </nav>

            <div class="d-flex align-items-center gap-3 mb-3">
                <h1 class="alf-titulo-detalle mb-0"><?= e($proyecto['titulo']) ?></h1>
                <span class="badge alf-badge"><?= e($proyecto['tipo']) ?></span>
            </div>

            <img src="<?= e($proyecto['imagen']) ?>" class="alf-imagen-detalle rounded mb-4" alt="<?= e($proyecto['titulo']) ?>">

            <p class="lead text-secondary"><?= e($proyecto['descripcion']) ?></p>

            <h2 class="alf-subtitulo-detalle">Tecnologías</h2>
            <div class="mb-4">
                <?php foreach ($proyecto['techs'] as $tech): ?>
                <span class="alf-tech me-1 mb-1"><img src="<?= e($tech['imagen']) ?>" alt=""><?= e($tech['nombre']) ?></span>
                <?php endforeach; ?>
            </div>

            <div class="d-flex gap-3 flex-wrap">
                <?php if ($proyecto['url'] !== ''): ?>
                <a class="btn btn-primary" href="<?= e($proyecto['url']) ?>" target="_blank" rel="noopener">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Visitar sitio
                </a>
                <?php endif; ?>
                <a class="btn btn-outline-secondary" href="<?= url('/portafolio') ?>">
                    <i class="bi bi-arrow-left me-2"></i>Volver al portafolio
                </a>
            </div>
        </div>
    </div>
</article>
