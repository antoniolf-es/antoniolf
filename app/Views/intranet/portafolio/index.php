<section class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="alf-titulo-seccion mb-1">Portafolio</h1>
            <p class="text-secondary mb-0">Gestiona los proyectos que se muestran en la web pública.</p>
        </div>
        <a class="btn btn-primary" href="<?= url('/intranet/portafolio/nuevo') ?>">
            <i class="bi bi-plus-lg me-2"></i>Nuevo proyecto
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
                        <th scope="col" class="ps-4">Proyecto</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Tecnologías</th>
                        <th scope="col">Destacado</th>
                        <th scope="col" class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proyectos as $proyecto): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= e($proyecto['imagen']) ?>" alt="" width="64" height="48" class="alf-thumb rounded">
                                <div>
                                    <div class="fw-semibold"><?= e($proyecto['titulo']) ?></div>
                                    <div class="text-secondary small">
                                        /portafolio/<?= e($proyecto['slug']) ?>
                                        <?php if ($proyecto['url'] !== ''): ?>
                                        · <a class="alf-enlace-ext" href="<?= e($proyecto['url']) ?>" target="_blank" rel="noopener" aria-label="Sitio externo" title="Visitar sitio"><i class="bi bi-box-arrow-up-right"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge alf-badge"><?= e($proyecto['tipo']) ?></span></td>
                        <td class="text-secondary small alf-celda-techs">
                            <?php foreach ($proyecto['techs'] as $tech): ?>
                            <span class="alf-tech me-1 mb-1"><img src="<?= e($tech['imagen']) ?>" alt=""><?= e($tech['nombre']) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php if ($proyecto['destacado'] === 1): ?>
                            <i class="bi bi-star-fill alf-destacado" aria-label="Destacado" title="Destacado"></i>
                            <?php else: ?>
                            <i class="bi bi-star text-secondary" aria-label="No destacado" title="No destacado"></i>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a class="btn btn-sm btn-outline-secondary" href="<?= url('/portafolio/' . e($proyecto['slug'])) ?>" target="_blank" rel="noopener" aria-label="Ver ficha pública" title="Ver ficha pública">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-primary" href="<?= url('/intranet/portafolio/' . $proyecto['id'] . '/editar') ?>" aria-label="Editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= url('/intranet/portafolio/' . $proyecto['id'] . '/borrar') ?>" class="d-inline" data-confirmar="¿Eliminar «<?= e($proyecto['titulo']) ?>»? Se borrarán también sus imágenes.">
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
