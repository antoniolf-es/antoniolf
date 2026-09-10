<header class="alf-cabecera container pt-5">
    <h1 class="alf-titulo-seccion">Cheatsheets</h1>
    <p class="text-secondary mb-3">Chuletas de comandos y sintaxis que uso a diario a primer vistazo.</p>
    <div class="d-flex align-items-center gap-2">
        <img src="<?= e($cheatsheet['imagen']) ?>" alt="" width="30" height="30">
        <h2 class="alf-titulo-cheats mb-0"><?= e($cheatsheet['titulo']) ?></h2>
    </div>
</header>

<section class="container py-5">
    <div class="row g-4">
        <aside class="col-lg-3">
            <nav class="alf-menu-cheats" aria-label="Lista de cheatsheets">
                <?php foreach ($cheatsheets as $item): ?>
                <a class="alf-enlace-cheats<?= $item['slug'] === $cheatsheet['slug'] ? ' active' : '' ?>" href="<?= url('/cheatsheets/' . e($item['slug'])) ?>"<?= $item['slug'] === $cheatsheet['slug'] ? ' aria-current="page"' : '' ?>>
                    <img src="<?= e($item['imagen']) ?>" alt=""><span><?= e($item['titulo']) ?></span>
                </a>
                <?php endforeach; ?>
            </nav>
        </aside>
        <div class="col-lg-9">
            <article class="alf-cheatsheet">
                <?= $cheatsheet['texto'] ?>
            </article>
        </div>
    </div>
</section>
