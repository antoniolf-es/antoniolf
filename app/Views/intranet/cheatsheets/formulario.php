<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <nav aria-label="migas de pan">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?= url('/intranet/cheatsheets') ?>">Cheatsheets</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($titulo) ?></li>
                </ol>
            </nav>

            <h1 class="alf-titulo-seccion mb-4"><?= e($titulo) ?></h1>

            <form method="post" action="<?= url($accion) ?>">
                <?= csrf_campo() ?>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card alf-tarjeta p-4">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" class="form-control<?= isset($errores['titulo']) ? ' is-invalid' : '' ?>" id="titulo" name="titulo" maxlength="255" value="<?= e($valores['titulo']) ?>" required>
                                <?php if (isset($errores['titulo'])): ?><div class="text-danger small mt-1"><?= e($errores['titulo']) ?></div><?php endif; ?>
                            </div>

                            <div class="mb-0">
                                <label for="texto" class="form-label">Contenido <span class="text-danger">*</span></label>
                                <textarea class="form-control<?= isset($errores['texto']) ? ' is-invalid' : '' ?>" id="texto" name="texto" rows="16"><?= e($valores['texto']) ?></textarea>
                                <div class="form-text">Usa el desplegable de bloques para títulos (Encabezado 2) y Preformateado para los comandos.</div>
                                <?php if (isset($errores['texto'])): ?><div class="text-danger small mt-1"><?= e($errores['texto']) ?></div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card alf-tarjeta p-4 mb-4">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control<?= isset($errores['slug']) ? ' is-invalid' : '' ?>" id="slug" name="slug" maxlength="255" value="<?= e($valores['slug']) ?>" placeholder="docker">
                            <div class="form-text">Se genera desde el título si lo dejas vacío. Letras minúsculas, números y guiones.</div>
                            <?php if (isset($errores['slug'])): ?><div class="text-danger small mt-1"><?= e($errores['slug']) ?></div><?php endif; ?>
                        </div>

                        <div class="card alf-tarjeta p-4 mb-4">
                            <label for="imagen" class="form-label">Icono <span class="text-danger">*</span></label>

                            <?php if ($valores['imagen'] !== ''): ?>
                            <img src="<?= url('/img/tech/' . e($valores['imagen'])) ?>" alt="Icono seleccionado" id="icono-preview" class="alf-icono-formulario rounded mb-3" width="64" height="64">
                            <?php endif; ?>

                            <select class="form-select<?= isset($errores['imagen']) ? ' is-invalid' : '' ?>" id="imagen" name="imagen" required>
                                <?php foreach ($iconos as $icono): ?>
                                <option value="<?= e($icono) ?>"<?= $valores['imagen'] === $icono ? ' selected' : '' ?>><?= e(pathinfo($icono, PATHINFO_FILENAME)) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Iconos disponibles en <code>img/tech</code>.</div>
                            <?php if (isset($errores['imagen'])): ?><div class="text-danger small mt-1"><?= e($errores['imagen']) ?></div><?php endif; ?>
                        </div>

                        <div class="card alf-tarjeta p-4">
                            <label for="orden" class="form-label">Orden</label>
                            <input type="number" class="form-control<?= isset($errores['orden']) ? ' is-invalid' : '' ?>" id="orden" name="orden" min="0" max="9999" value="<?= e($valores['orden']) ?>">
                            <div class="form-text">Posición en el menú de la web pública (menor antes).</div>
                            <?php if (isset($errores['orden'])): ?><div class="text-danger small mt-1"><?= e($errores['orden']) ?></div><?php endif; ?>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                            <a class="btn btn-outline-secondary" href="<?= url('/intranet/cheatsheets') ?>">
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
    plugins: 'lists link table code',
    toolbar: 'undo redo | blocks | bold italic codigo | bullist numlist | blockquote hr table | code',
    convert_urls: false,
    height: 450,
    resize: true,
    setup: function (editor) {
        editor.ui.registry.addToggleButton('codigo', {
            icon: 'sourcecode',
            tooltip: 'Código en línea',
            onAction: function () {
                editor.formatter.toggle('code');
            },
            onSetup: function (api) {
                var desactivar = editor.formatter.formatChanged('code', function (activo) {
                    api.setActive(activo);
                });

                return desactivar;
            }
        });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var select = document.getElementById('imagen');
    var preview = document.getElementById('icono-preview');
    var base = <?= json_encode(url('/img/tech/')) ?>;

    if (select !== null && preview !== null) {
        select.addEventListener('change', function () {
            preview.src = base + select.value;
        });
    }
});
</script>
