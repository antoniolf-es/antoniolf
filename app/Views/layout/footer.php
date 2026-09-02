</main>

<footer class="alf-footer">
    <div class="container py-4">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-4">
                <a class="alf-marca d-inline-flex align-items-center gap-2" href="<?= url('/') ?>">
                    <img src="<?= url('/assets/img/logo.svg') ?>" alt="Logotipo de AntonioLF" width="26" height="26">
                    <span>ANTONIO<span class="alf-azul">LF</span></span>
                </a>
            </div>
            <div class="col-lg-4">
                <ul class="list-inline mb-0 text-center small">
                    <li class="list-inline-item"><a href="<?= url('/') ?>">Inicio</a></li>
                    <li class="list-inline-item">·</li>
                    <li class="list-inline-item"><a href="<?= url('/blog') ?>">Blog</a></li>
                    <li class="list-inline-item">·</li>
                    <li class="list-inline-item"><a href="<?= url('/portafolio') ?>">Portafolio</a></li>
                    <li class="list-inline-item">·</li>
                    <li class="list-inline-item"><a href="<?= url('/contacto') ?>">Contacto</a></li>
                    <li class="list-inline-item">·</li>
                    <li class="list-inline-item"><a href="<?= url('/admin') ?>">Login</a></li>
                </ul>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a class="alf-icono-footer me-3" href="<?= GITHUB_URL ?>" target="_blank" rel="noopener" aria-label="GitHub" title="GitHub">
                    <i class="bi bi-github fs-5"></i>
                </a>
                <a class="alf-icono-footer" href="<?= LINKEDIN_URL ?>" target="_blank" rel="noopener" aria-label="LinkedIn" title="LinkedIn">
                    <i class="bi bi-linkedin fs-5"></i>
                </a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="<?= url('/assets/js/main.js') ?>"></script>
</body>
</html>
