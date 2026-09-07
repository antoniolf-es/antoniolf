<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <nav aria-label="migas de pan">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?= url('/intranet/blog/categorias') ?>">Categorías</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($titulo) ?></li>
                </ol>
            </nav>

            <h1 class="alf-titulo-seccion mb-4"><?= e($titulo) ?></h1>

            <form method="post" action="<?= url($accion) ?>">
                <?= csrf_campo() ?>

                <div class="card alf-tarjeta p-4">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control<?= isset($errores['nombre']) ? ' is-invalid' : '' ?>" id="nombre" name="nombre" maxlength="255" value="<?= e($valores['nombre']) ?>" required>
                        <?php if (isset($errores['nombre'])): ?><div class="text-danger small mt-1"><?= e($errores['nombre']) ?></div><?php endif; ?>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Guardar
                    </button>
                    <a class="btn btn-outline-secondary" href="<?= url('/intranet/blog/categorias') ?>">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
