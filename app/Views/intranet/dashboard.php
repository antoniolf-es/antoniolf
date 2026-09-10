<section class="container py-5">
    <div class="mb-4">
        <h1 class="alf-titulo-seccion mb-1">Dashboard</h1>
        <p class="text-secondary mb-0">Hola, <?= e(usuario()['nombre']) ?>. Esto es lo último de tu web.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card alf-tarjeta h-100">
                <div class="d-flex justify-content-between align-items-center gap-2 px-4 py-3 border-bottom" style="background-color: var(--alf-verde); !important;">
                    <h2 class="h6 fw-semibold mb-0">
                        <i class="bi bi-journal-text me-2"></i>Últimos artículos
                    </h2>
                    <a class="alf-enlace-ext small text-nowrap" href="<?= url('/intranet/blog') ?>">
                        Ver todo<i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <?php if ($posts === []): ?>
                <div class="px-4 py-5 text-center text-secondary">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    Todavía no hay artículos.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover alf-tabla align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-4" style="width: 42%;">Artículo</th>
                                <th scope="col" class="text-end" style="width: 36%;">Etiquetas</th>
                                <th scope="col" class="text-end pe-4" style="width: 22%;">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="alf-celda-titulo">
                                        <a class="fw-semibold text-truncate d-block text-decoration-none" href="<?= url('/intranet/blog/' . $post['id'] . '/editar') ?>"><?= e($post['titulo']) ?></a>
                                        <div class="text-secondary small text-truncate">/blog/<?= e($post['slug']) ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1 justify-content-end">
                                        <?php if ($post['categoria'] !== null): ?>
                                        <span class="badge alf-badge"><?= e($post['categoria']) ?></span>
                                        <?php endif; ?>
                                        <?php if ($post['status'] === 1): ?>
                                        <span class="badge text-bg-success">Publicado</span>
                                        <?php else: ?>
                                        <span class="badge text-bg-secondary">Borrador</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-secondary small text-end text-nowrap pe-4">
                                    <?= $post['fecha'] !== null && $post['fecha'] !== '' ? date('d/m/Y', strtotime((string) $post['fecha'])) : '—' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card alf-tarjeta h-100">
                <div class="d-flex justify-content-between align-items-center gap-2 px-4 py-3 border-bottom" style="background-color: var(--alf-morado) !important;">
                    <h2 class="h6 fw-semibold mb-0">
                        <i class="bi bi-collection me-2"></i>Últimos proyectos
                    </h2>
                    <a class="alf-enlace-ext small text-nowrap" href="<?= url('/intranet/portafolio') ?>">
                        Ver todo<i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <?php if ($proyectos === []): ?>
                <div class="px-4 py-5 text-center text-secondary">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    Todavía no hay proyectos.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover alf-tabla align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-4" style="width: 58%;">Proyecto</th>
                                <th scope="col" class="text-end" style="width: 20%;">Tipo</th>
                                <th scope="col" class="text-end pe-4" style="width: 22%;">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyectos as $proyecto): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="alf-celda-titulo">
                                        <a class="fw-semibold text-truncate d-block text-decoration-none" href="<?= url('/intranet/portafolio/' . $proyecto['id'] . '/editar') ?>"><?= e($proyecto['titulo']) ?></a>
                                        <div class="text-secondary small text-truncate">
                                            /portafolio/<?= e($proyecto['slug']) ?>
                                            <?php if ($proyecto['destacado'] === 1): ?>
                                            · <i class="bi bi-star-fill alf-destacado" aria-label="Destacado" title="Destacado"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge alf-badge<?= clase_badge_tipo($proyecto['type']) ?>"><?= e($proyecto['tipo']) ?></span>
                                </td>
                                <td class="text-secondary small text-end text-nowrap pe-4">
                                    <?= $proyecto['fecha'] !== null && $proyecto['fecha'] !== '' ? date('d/m/Y', strtotime((string) $proyecto['fecha'])) : '—' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card alf-tarjeta h-100">
                <div class="d-flex justify-content-between align-items-center gap-2 px-4 py-3 border-bottom" style="background-color: var(--alf-azul) !important;">
                    <h2 class="h6 fw-semibold mb-0">
                        <i class="bi bi-journal-code me-2"></i>Cheatsheets
                    </h2>
                    <a class="alf-enlace-ext small text-nowrap" href="<?= url('/intranet/cheatsheets') ?>">
                        Ver todo<i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <?php if ($cheatsheets === []): ?>
                <div class="px-4 py-5 text-center text-secondary">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    Todavía no hay cheatsheets.
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover alf-tabla align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-4" style="width: 60%;">Cheatsheet</th>
                                <th scope="col" class="text-end" style="width: 20%;">Orden</th>
                                <th scope="col" class="text-end pe-4" style="width: 20%;">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cheatsheets as $cheatsheet): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2 alf-celda-titulo">
                                        <img src="<?= e($cheatsheet['imagen']) ?>" alt="" width="20" height="20" class="flex-shrink-0">
                                        <div class="text-truncate">
                                            <a class="fw-semibold text-decoration-none d-block text-truncate" href="<?= url('/intranet/cheatsheets/' . $cheatsheet['id'] . '/editar') ?>"><?= e($cheatsheet['titulo']) ?></a>
                                            <div class="text-secondary small text-truncate">/cheatsheets/<?= e($cheatsheet['slug']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge alf-badge"><?= $cheatsheet['orden'] ?></span>
                                </td>
                                <td class="text-secondary small text-end text-nowrap pe-4">
                                    <?= $cheatsheet['fecha'] !== null && $cheatsheet['fecha'] !== '' ? date('d/m/Y', strtotime((string) $cheatsheet['fecha'])) : '—' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
