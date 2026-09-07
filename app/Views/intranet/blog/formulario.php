<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <nav aria-label="migas de pan">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?= url('/intranet/blog') ?>">Blog</a></li>
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
                                <input type="text" class="form-control<?= isset($errores['slug']) ? ' is-invalid' : '' ?>" id="slug" name="slug" maxlength="255" value="<?= e($valores['slug']) ?>" placeholder="mi-articulo">
                                <div class="form-text">Se genera desde el título si lo dejas vacío. Letras minúsculas, números y guiones.</div>
                                <?php if (isset($errores['slug'])): ?><div class="text-danger small mt-1"><?= e($errores['slug']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="resumen" class="form-label">Resumen <span class="text-danger">*</span></label>
                                <textarea class="form-control<?= isset($errores['resumen']) ? ' is-invalid' : '' ?>" id="resumen" name="resumen" rows="2" maxlength="255" required><?= e($valores['resumen']) ?></textarea>
                                <div class="form-text">Se muestra en la portada y el listado del blog.</div>
                                <?php if (isset($errores['resumen'])): ?><div class="text-danger small mt-1"><?= e($errores['resumen']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <select class="form-select<?= isset($errores['categoria']) ? ' is-invalid' : '' ?>" id="categoria" name="categoria">
                                    <option value="">Sin categoría</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria['id'] ?>"<?= $valores['categoria'] === $categoria['id'] ? ' selected' : '' ?>><?= e($categoria['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Gestiona las categorías desde <a href="<?= url('/intranet/blog/categorias') ?>">su apartado</a>.</div>
                                <?php if (isset($errores['categoria'])): ?><div class="text-danger small mt-1"><?= e($errores['categoria']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-0">
                                <label for="texto" class="form-label d-block">Contenido <span class="text-danger">*</span></label>
                                <textarea class="form-control<?= isset($errores['texto']) ? ' is-invalid' : '' ?>" id="texto" name="texto" rows="14"><?= e($valores['texto']) ?></textarea>
                                <?php if (isset($errores['texto'])): ?><div class="text-danger small mt-1"><?= e($errores['texto']) ?></div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card alf-tarjeta p-4 mb-4">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="status" name="status"<?= $valores['status'] === 1 ? ' checked' : '' ?>>
                                <label class="form-check-label" for="status">
                                    <i class="bi bi-globe2 me-1"></i>Publicado
                                </label>
                            </div>
                            <div class="form-text mt-2">Si lo desactivas, el artículo queda como borrador y no se ve en la web.</div>
                        </div>

                        <div class="card alf-tarjeta p-4">
                            <label for="imagen" class="form-label">Imagen <?= $esAlta ? '<span class="text-danger">*</span>' : '' ?></label>

                            <?php if (!$esAlta && $post !== null && $post['image'] !== null && $post['image'] !== ''): ?>
                            <img src="<?= url('/img/blog/' . e($post['image'])) ?>" alt="Imagen actual" class="alf-imagen-formulario rounded mb-3">
                            <?php endif; ?>

                            <input type="file" class="form-control<?= isset($errores['imagen']) ? ' is-invalid' : '' ?>" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp"<?= $esAlta ? ' required' : '' ?>>
                            <div class="form-text">JPG, PNG o WebP, máximo 8 MB.<?php if (!$esAlta): ?> Déjalo vacío para conservar la actual.<?php endif; ?></div>
                            <?php if (isset($errores['imagen'])): ?><div class="text-danger small mt-1"><?= e($errores['imagen']) ?></div><?php endif; ?>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                            <a class="btn btn-outline-secondary" href="<?= url('/intranet/blog') ?>">
                                <i class="bi bi-x-lg me-2"></i>Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/tinymce@7.9.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#texto',
    language: 'es',
    language_url: '<?= url('/assets/js/tinymce/langs/es.js') ?>',
    skin: 'oxide-dark',
    content_css: 'dark',
    menubar: false,
    plugins: 'autoresize lists link code',
    toolbar: 'undo redo | blocks | bold italic | link bullist numlist | code',
    convert_urls: false,
    autoresize_bottom_margin: 24
});
</script>
