<!doctype html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portfolio y blog de Antonio LF, desarrollador web full stack.">
    <meta name="author" content="Antonio LF">
    <title><?= e($titulo ?? SITE_NAME) ?> · <?= SITE_NAME ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= url('/assets/img/logo.svg') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= url('/assets/css/estilos.css') ?>">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg alf-nav sticky-top">
    <div class="container">
        <a class="navbar-brand alf-marca d-flex align-items-center gap-2" href="<?= url('/') ?>">
            <img src="<?= url('/assets/img/logo.svg') ?>" alt="Logotipo de AntonioLF" width="34" height="34">
            <span>ANTONIO<span class="alf-azul">LF</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-semibold<?= enlace_activo('/') ?>" href="<?= url('/') ?>">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold<?= enlace_activo('/blog', '/blog') ?>" href="<?= url('/blog') ?>">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold<?= enlace_activo('/portafolio', '/portafolio') ?>" href="<?= url('/portafolio') ?>">Portafolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold<?= enlace_activo('/contacto', '/contacto') ?>" href="<?= url('/contacto') ?>">Contacto</a>
                </li>
            </ul>
            <ul class="navbar-nav flex-row gap-1 align-items-center">
                <li class="nav-item">
                    <a class="nav-link alf-icono" href="<?= GITHUB_URL ?>" target="_blank" rel="noopener" aria-label="GitHub" title="GitHub">
                        <i class="bi bi-github fs-5"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link alf-icono" href="<?= LINKEDIN_URL ?>" target="_blank" rel="noopener" aria-label="LinkedIn" title="LinkedIn">
                        <i class="bi bi-linkedin fs-5"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1">
