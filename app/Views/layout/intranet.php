<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Intranet de administración de AntonioLF.">
    <meta name="author" content="Antonio LF">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($titulo ?? 'Intranet') ?> · Intranet · <?= SITE_NAME ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= url('/assets/img/logo.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= url('/assets/css/estilos.css') ?>">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand alf-nav sticky-top">
    <div class="container">
        <a class="navbar-brand alf-marca d-flex align-items-center gap-2" href="<?= url('/') ?>">
            <img src="<?= url('/assets/img/logo.svg') ?>" alt="Logotipo de AntonioLF" width="34" height="34">
            <span>ANTONIO<span class="alf-azul">LF</span></span>
        </a>
        <span class="badge alf-badge ms-2">Intranet</span>

        <ul class="navbar-nav ms-auto flex-row align-items-center gap-1">
            <?php if (usuario() !== null): ?>
            <li class="nav-item">
                <span class="nav-link alf-usuario">
                    <i class="bi bi-person-circle me-1"></i><?= e(usuario()['nombre']) ?>
                </span>
            </li>
            <li class="nav-item">
                <a class="nav-link alf-icono" href="<?= url('/intranet/salir') ?>" aria-label="Salir" title="Salir">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<main class="flex-grow-1">

<?= $contenido ?>

</main>

<footer class="alf-footer">
    <div class="container py-3 d-flex justify-content-between align-items-center small">
        <span>&copy; <?= date('Y') ?> AntonioLF</span>
        <a class="alf-icono-footer" href="<?= url('/') ?>">
            <i class="bi bi-arrow-left me-1"></i>Ver la web pública
        </a>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="<?= url('/assets/js/main.js') ?>"></script>
</body>
</html>
