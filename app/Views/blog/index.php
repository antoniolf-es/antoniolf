<header class="alf-cabecera container pt-5">
    <h1 class="alf-titulo-seccion">Blog</h1>
    <p class="text-secondary mb-4">Trucos, programas y notas de programación que voy recopilando por el camino.</p>

    <form class="row g-2 align-items-center" method="get" action="<?= url('/blog') ?>">
        <div class="col-sm-12 col-md-5">
            <label class="visually-hidden" for="buscar">Buscar</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input class="form-control" type="search" id="buscar" name="buscar" placeholder="Buscar artículos…" value="<?= e($buscarActual) ?>">
            </div>
        </div>
        <div class="col-sm-8 col-md-4">
            <label class="visually-hidden" for="categoria">Categoría</label>
            <select class="form-select" id="categoria" name="categoria">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id'] ?>"<?= $categoriaActual === $categoria['id'] ? ' selected' : '' ?>>
                    <?= e($categoria['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-4 col-md-auto">
            <button class="btn btn-primary w-100" type="submit">
                <i class="bi bi-funnel me-1"></i>Filtrar
            </button>
        </div>
    </form>
</header>

<section class="container py-5">
    <?php if ($posts === []): ?>
    <div class="alert alf-alerta mb-0">
        <i class="bi bi-info-circle me-2"></i>No hay artículos que coincidan con el filtro.
        <a class="alert-link" href="<?= url('/blog') ?>">Quitar filtros</a>
    </div>
    <?php else: ?>
    <?php if ($postsLimitados): ?>
    <p class="text-secondary small mb-4">
        Mostrando los <?= count($posts) ?> artículos más recientes de un total de <?= $totalPosts ?>.
        <a class="alf-ver-todo" href="<?= url('/blog?todos=1') ?>">Ver todos</a>
    </p>
    <?php else: ?>
    <p class="text-secondary small mb-4">
        <?= count($posts) ?> artículo<?= count($posts) === 1 ? '' : 's' ?>
        <?php if ($buscarActual !== ''): ?> para "<?= e($buscarActual) ?>"<?php endif; ?>
    </p>
    <?php endif; ?>
    <div class="row g-4">
        <?php foreach ($posts as $post): ?>
        <div class="col-md-6 col-lg-4">
            <?php include __DIR__ . '/_tarjeta.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>
