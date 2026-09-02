<section class="container py-5">
    <div class="row justify-content-center g-5">
        <div class="col-lg-5">
            <h1 class="alf-titulo-seccion">Contacto</h1>
            <p class="text-secondary">
                ¿Un proyecto, una duda o simplemente saludar? Escríbeme y te contesto lo antes posible.
            </p>
            <ul class="list-unstyled alf-datos-contacto mt-4">
                <li class="mb-3">
                    <i class="bi bi-envelope-fill alf-azul me-2"></i>contacto@antoniolf.es
                </li>
                <li class="mb-3">
                    <i class="bi bi-github alf-azul me-2"></i>
                    <a href="<?= GITHUB_URL ?>" target="_blank" rel="noopener">github.com/brolin79</a>
                </li>
                <li class="mb-3">
                    <i class="bi bi-linkedin alf-azul me-2"></i>
                    <a href="<?= LINKEDIN_URL ?>" target="_blank" rel="noopener">LinkedIn</a>
                </li>
            </ul>
        </div>

        <div class="col-lg-6">
            <form class="card alf-tarjeta p-4" method="post" action="<?= url('/contacto') ?>" novalidate>
                <?= csrf_campo() ?>

                <?php if (($errores['_formulario'] ?? null) !== null): ?>
                <div class="alert alert-danger py-2 small">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= e($errores['_formulario']) ?>
                </div>
                <?php elseif ($errores !== []): ?>
                <div class="alert alert-danger py-2 small">
                    <i class="bi bi-exclamation-triangle me-2"></i>Revisa los campos marcados.
                </div>
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control<?= isset($errores['nombre']) ? ' is-invalid' : '' ?>" id="nombre" name="nombre" value="<?= e($antiguos['nombre'] ?? '') ?>" required>
                        <?php if (isset($errores['nombre'])): ?>
                        <div class="invalid-feedback"><?= e($errores['nombre']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control<?= isset($errores['email']) ? ' is-invalid' : '' ?>" id="email" name="email" value="<?= e($antiguos['email'] ?? '') ?>" required>
                        <?php if (isset($errores['email'])): ?>
                        <div class="invalid-feedback"><?= e($errores['email']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" class="form-control<?= isset($errores['asunto']) ? ' is-invalid' : '' ?>" id="asunto" name="asunto" value="<?= e($antiguos['asunto'] ?? '') ?>" required>
                        <?php if (isset($errores['asunto'])): ?>
                        <div class="invalid-feedback"><?= e($errores['asunto']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control<?= isset($errores['mensaje']) ? ' is-invalid' : '' ?>" id="mensaje" name="mensaje" rows="5" required><?= e($antiguos['mensaje'] ?? '') ?></textarea>
                        <?php if (isset($errores['mensaje'])): ?>
                        <div class="invalid-feedback"><?= e($errores['mensaje']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-2"></i>Enviar mensaje
                        </button>
                        <p class="text-secondary small mt-3 mb-0">
                            Maqueta: el envío aún no se guarda en la base de datos.
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
