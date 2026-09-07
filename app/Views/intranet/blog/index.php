<section class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="alf-titulo-seccion mb-1">Blog</h1>
            <p class="text-secondary mb-0">Gestiona los artículos que se muestran en la web pública.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-primary" href="<?= url('/intranet/blog/categorias') ?>">
                <i class="bi bi-tags me-2"></i>Categorías
            </a>
            <a class="btn btn-primary" href="<?= url('/intranet/blog/nuevo') ?>">
                <i class="bi bi-plus-lg me-2"></i>Nuevo artículo
            </a>
        </div>
    </div>

    <?php if ($exito !== null): ?>
    <div class="alert alert-success py-2">
        <i class="bi bi-check-circle me-2"></i><?= e($exito) ?>
    </div>
    <?php endif; ?>

    <?php if ($error !== null): ?>
    <div class="alert alert-danger py-2">
        <i class="bi bi-exclamation-triangle me-2"></i><?= e($error) ?>
    </div>
    <?php endif; ?>

    <div class="card alf-tarjeta">
        <div class="table-responsive">
            <table class="table table-hover alf-tabla align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="ps-4" style="width: 40%;">Artículo</th>
                        <th scope="col" style="width: 15%;">Categoría</th>
                        <th scope="col" style="width: 10%;">Estado</th>
                        <th scope="col" style="width: 15%;">Fecha</th>
                        <th scope="col" class="text-end pe-4" style="width: 20%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= e($post['imagen']) ?>" alt="" width="64" height="48" class="alf-thumb rounded">
                                <div class="alf-celda-titulo">
                                    <div class="fw-semibold text-truncate"><?= e($post['titulo']) ?></div>
                                    <div class="text-secondary small text-truncate">/blog/<?= e($post['slug']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($post['categoria'] !== null): ?>
                            <span class="badge alf-badge"><?= e($post['categoria']) ?></span>
                            <?php else: ?>
                            <span class="text-secondary">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($post['status'] === 1): ?>
                            <span class="badge text-bg-success">Publicado</span>
                            <?php else: ?>
                            <span class="badge text-bg-secondary">Borrador</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-secondary small">
                            <?= $post['fecha'] !== null && $post['fecha'] !== '' ? fecha_es($post['fecha']) : '—' ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <?php if ($post['status'] === 1): ?>
                                <a class="btn btn-sm btn-outline-secondary" href="<?= url('/blog/' . e($post['slug'])) ?>" target="_blank" rel="noopener" aria-label="Ver artículo público" title="Ver artículo público">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php else: ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled aria-label="Borrador, no visible" title="Borrador, no visible en la web pública">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-outline-primary" href="<?= url('/intranet/blog/' . $post['id'] . '/editar') ?>" aria-label="Editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= url('/intranet/blog/' . $post['id'] . '/borrar') ?>" class="d-inline" data-confirmar="¿Eliminar «<?= e($post['titulo']) ?>»? Se borrará también su imagen.">
                                    <?= csrf_campo() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
