<section class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="alf-titulo-seccion mb-1">Cheatsheets</h1>
            <p class="text-secondary mb-0">Gestiona las chuletas que se muestran en la web pública.</p>
        </div>
        <a class="btn btn-primary" href="<?= url('/intranet/cheatsheets/nuevo') ?>">
            <i class="bi bi-plus-lg me-2"></i>Nueva cheatsheet
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
                        <th scope="col" class="ps-4" style="width: 50%;">Cheatsheet</th>
                        <th scope="col" style="width: 10%;">Icono</th>
                        <th scope="col" style="width: 10%;">Orden</th>
                        <th scope="col" style="width: 15%;">Fecha</th>
                        <th scope="col" class="text-end pe-4" style="width: 15%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cheatsheets as $cheatsheet): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="alf-celda-titulo">
                                <div class="fw-semibold text-truncate"><?= e($cheatsheet['titulo']) ?></div>
                                <div class="text-secondary small text-truncate">/cheatsheets/<?= e($cheatsheet['slug']) ?></div>
                            </div>
                        </td>
                        <td>
                            <img src="<?= e($cheatsheet['imagen']) ?>" alt="" width="24" height="24">
                        </td>
                        <td>
                            <span class="badge alf-badge"><?= $cheatsheet['orden'] ?></span>
                        </td>
                        <td class="text-secondary small">
                            <?= $cheatsheet['fecha'] !== null && $cheatsheet['fecha'] !== '' ? fecha_es($cheatsheet['fecha']) : '—' ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a class="btn btn-sm btn-outline-secondary" href="<?= url('/cheatsheets/' . e($cheatsheet['slug'])) ?>" target="_blank" rel="noopener" aria-label="Ver cheatsheet pública" title="Ver cheatsheet pública">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-outline-primary" href="<?= url('/intranet/cheatsheets/' . $cheatsheet['id'] . '/editar') ?>" aria-label="Editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= url('/intranet/cheatsheets/' . $cheatsheet['id'] . '/borrar') ?>" class="d-inline" data-confirmar="¿Eliminar «<?= e($cheatsheet['titulo']) ?>»?">
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
