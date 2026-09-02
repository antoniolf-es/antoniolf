<article class="card alf-tarjeta h-100">
    <a href="<?= url('/blog/' . e($post['slug'])) ?>">
        <img src="<?= e($post['imagen']) ?>" class="card-img-top" alt="<?= e($post['titulo']) ?>" loading="lazy">
    </a>
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-center mb-2 small">
            <span class="text-secondary"><i class="bi bi-calendar3 me-1"></i><?= fecha_es($post['fecha']) ?></span>
            <span class="badge alf-badge"><?= e($post['categoria']['nombre'] ?? '') ?></span>
        </div>
        <h3 class="card-title mb-2">
            <a class="alf-enlace-tarjeta" href="<?= url('/blog/' . e($post['slug'])) ?>"><?= e($post['titulo']) ?></a>
        </h3>
        <p class="card-text text-secondary mb-0"><?= e($post['resumen']) ?></p>
    </div>
</article>
