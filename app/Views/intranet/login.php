<section class="alf-auth py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5 col-xl-4">
                <div class="card alf-tarjeta alf-tarjeta-auth p-4 text-center">
                    <img src="<?= url('/assets/img/logo.svg') ?>" alt="Logotipo de AntonioLF" width="56" height="56" class="mb-2">
                    <h1 class="h4 fw-bold mb-1">Intranet</h1>
                    <p class="text-secondary small mb-4">Acceso restringido a administración</p>

                    <?php if ($error !== null): ?>
                    <div class="alert alert-danger py-2 small text-start">
                        <i class="bi bi-exclamation-triangle me-2"></i><?= e($error) ?>
                    </div>
                    <?php endif; ?>

                    <form method="post" action="<?= url('/intranet') ?>" class="text-start">
                        <?= csrf_campo() ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" autocomplete="username" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                        </button>
                    </form>

                    <a class="alf-volver small d-block mt-4" href="<?= url('/') ?>">
                        <i class="bi bi-arrow-left me-1"></i>Volver a la web
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
