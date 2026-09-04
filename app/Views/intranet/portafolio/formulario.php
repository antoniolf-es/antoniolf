<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <nav aria-label="migas de pan">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?= url('/intranet/portafolio') ?>">Portafolio</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($titulo) ?></li>
                </ol>
            </nav>

            <h1 class="alf-titulo-seccion mb-4"><?= e($titulo) ?></h1>

            <form method="post" action="<?= url($accion) ?>" enctype="multipart/form-data">
                <?= csrf_campo() ?>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card alf-tarjeta p-4">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" class="form-control<?= isset($errores['titulo']) ? ' is-invalid' : '' ?>" id="titulo" name="titulo" maxlength="255" value="<?= e($valores['titulo']) ?>" required>
                                <?php if (isset($errores['titulo'])): ?><div class="text-danger small mt-1"><?= e($errores['titulo']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control<?= isset($errores['slug']) ? ' is-invalid' : '' ?>" id="slug" name="slug" maxlength="255" value="<?= e($valores['slug']) ?>" placeholder="alf-tools">
                                <div class="form-text">Se genera desde el título si lo dejas vacío. Letras minúsculas, números y guiones.</div>
                                <?php if (isset($errores['slug'])): ?><div class="text-danger small mt-1"><?= e($errores['slug']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                                <textarea class="form-control<?= isset($errores['descripcion']) ? ' is-invalid' : '' ?>" id="descripcion" name="descripcion" rows="3" maxlength="255" required><?= e($valores['descripcion']) ?></textarea>
                                <?php if (isset($errores['descripcion'])): ?><div class="text-danger small mt-1"><?= e($errores['descripcion']) ?></div><?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="url" class="form-label">URL</label>
                                    <input type="url" class="form-control<?= isset($errores['url']) ? ' is-invalid' : '' ?>" id="url" name="url" maxlength="255" value="<?= e($valores['url']) ?>" placeholder="https://...">
                                    <?php if (isset($errores['url'])): ?><div class="text-danger small mt-1"><?= e($errores['url']) ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-select<?= isset($errores['type']) ? ' is-invalid' : '' ?>" id="type" name="type" required>
                                        <?php foreach ($tipos as $idTipo => $nombre): ?>
                                        <option value="<?= $idTipo ?>"<?= $valores['type'] === $idTipo ? ' selected' : '' ?>><?= e($nombre) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errores['type'])): ?><div class="text-danger small mt-1"><?= e($errores['type']) ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label d-block">Tecnologías <span class="text-danger">*</span></label>
                                <div class="row g-1">
                                    <?php foreach ($techs as $tech): ?>
                                    <div class="col-auto">
                                        <label class="alf-tech alf-tech-opcion">
                                            <input type="checkbox" name="tech[]" value="<?= $tech['id'] ?>"<?= in_array($tech['id'], $valores['tech'], true) ? ' checked' : '' ?>>
                                            <img src="<?= e($tech['imagen']) ?>" alt=""><?= e($tech['nombre']) ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (isset($errores['tech'])): ?><div class="text-danger small mt-1"><?= e($errores['tech']) ?></div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card alf-tarjeta p-4 mb-4">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="destacado" name="destacado"<?= $valores['destacado'] === 1 ? ' checked' : '' ?>>
                                <label class="form-check-label" for="destacado">
                                    <i class="bi bi-star-fill alf-destacado me-1"></i>Destacado en portada
                                </label>
                            </div>
                        </div>

                        <div class="card alf-tarjeta p-4">
                            <label for="imagen" class="form-label">Imagen <?= $esAlta ? '<span class="text-danger">*</span>' : '' ?></label>

                            <?php if (!$esAlta && $proyecto !== null): ?>
                            <img src="<?= url('/img/portfolio/' . e($proyecto['image']) . '_1.jpg') ?>" alt="Imagen actual" class="alf-imagen-formulario rounded mb-3">
                            <?php endif; ?>

                            <input type="file" class="form-control<?= isset($errores['imagen']) ? ' is-invalid' : '' ?>" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp"<?= $esAlta ? ' required' : '' ?>>
                            <div class="form-text">JPG, PNG o WebP, máximo 8 MB.<?php if (!$esAlta): ?> Déjalo vacío para conservar la actual.<?php endif; ?></div>
                            <?php if (isset($errores['imagen'])): ?><div class="text-danger small mt-1"><?= e($errores['imagen']) ?></div><?php endif; ?>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                            <a class="btn btn-outline-secondary" href="<?= url('/intranet/portafolio') ?>">
                                <i class="bi bi-x-lg me-2"></i>Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
