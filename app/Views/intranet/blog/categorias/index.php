<section class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="alf-titulo-seccion mb-1">Categorías del blog</h1>
            <p class="text-secondary mb-0">Organiza los artículos del blog por categorías.</p>
        </div>
        <a class="btn btn-primary" href="<?= url('/intranet/blog/categorias/nuevo') ?>">
            <i class="bi bi-plus-lg me-2"></i>Nueva categoría
        </a>
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
                        <th scope="col" class="ps-4" style="width: 60%;">Categoría</th>
                        <th scope="col" style="width: 20%;">Artículos</th>
                        <th scope="col" class="text-end pe-4" style="width: 20%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td class="ps-4">
                            <span class="badge alf-badge"><?= e($categoria['nombre']) ?></span>
                        </td>
                        <td class="text-secondary"><?= $categoria['posts'] ?></td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a class="btn btn-sm btn-outline-primary" href="<?= url('/intranet/blog/categorias/' . $categoria['id'] . '/editar') ?>" aria-label="Editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($categoria['posts'] > 0): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger" disabled aria-label="Categoría con artículos" title="No se puede eliminar: tiene <?= $categoria['posts'] ?> artículos asociados">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php else: ?>
                                <form method="post" action="<?= url('/intranet/blog/categorias/' . $categoria['id'] . '/borrar') ?>" class="d-inline" data-confirmar="¿Eliminar la categoría «<?= e($categoria['nombre']) ?>»?">
                                    <?= csrf_campo() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-secondary small mt-3 mb-0">
        <i class="bi bi-info-circle me-1"></i>Las categorías con artículos asignados no se pueden eliminar.
    </p>
</section>
