<article class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="migas de pan">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?= url('/') ?>">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/blog') ?>">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($post['titulo']) ?></li>
                </ol>
            </nav>

            <h1 class="alf-titulo-detalle mb-3"><?= e($post['titulo']) ?></h1>
            <p class="text-secondary small mb-4">
                <i class="bi bi-calendar3 me-1"></i><?= fecha_es($post['fecha']) ?>
                <a class="badge alf-badge ms-3 text-decoration-none" href="<?= url('/blog?categoria=' . $post['category_id']) ?>">
                    <?= e($post['categoria']['nombre'] ?? '') ?>
                </a>
            </p>

            <img src="<?= e($post['imagen']) ?>" class="alf-imagen-detalle rounded mb-4" alt="<?= e($post['titulo']) ?>">

            <div class="alf-texto">
                <?= $post['texto'] ?>
            </div>

            <hr class="alf-separador my-5">

            <div class="d-flex justify-content-between gap-3 flex-wrap">
                <?php if ($anterior !== null): ?>
                <a class="btn btn-outline-secondary text-truncate alf-btn-navegacion" href="<?= url('/blog/' . e($anterior['slug'])) ?>">
                    <i class="bi bi-arrow-left me-2"></i><?= e($anterior['titulo']) ?>
                </a>
                <?php else: ?>
                <span></span>
                <?php endif; ?>
                <?php if ($siguiente !== null): ?>
                <a class="btn btn-outline-secondary text-truncate alf-btn-navegacion" href="<?= url('/blog/' . e($siguiente['slug'])) ?>">
                    <?= e($siguiente['titulo']) ?><i class="bi bi-arrow-right ms-2"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>
