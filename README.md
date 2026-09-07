# Antonio LF

Reconstrucción desde cero de **https://www.antoniolf.es**: web personal de un desarrollador web con **portfolio, blog, contacto y una intranet de administración**. El objetivo era modernizar el sitio anterior (CodeIgniter → Laravel) descargándolo de frameworks: **todo el MVC está escrito a mano en PHP vanilla**.

## Contenido real

### Web pública (frontend)

- **`/`** — Portada: presentación, **últimos artículos del blog** y **proyectos destacados**.
- **`/blog`** — Listado de artículos publicados con **filtro por categoría**.
- **`/blog/{slug}`** — Artículo completo.
- **`/portafolio`** — Proyectos con **filtro por tipo** (web / app / vintage).
- **`/portafolio/{slug}`** — Detalle de proyecto con sus tecnologías.
- **`/contacto`** — Formulario de contacto protegido con **reCAPTCHA v3** y envío de email vía **Mailjet**.

### Intranet (backend)

- **`/intranet`** — Login/logout (**se conservan los bcrypt de Laravel** para mantener los accesos existentes; requiere `role` admin y usuario activo).
- **`/intranet/dashboard`** — Panel principal.
- **CRUD completo de portafolio** (con subida de imagen).
- **CRUD completo de blog** (editor **TinyMCE** con subida de imagen).
- **CRUD de categorías del blog**.

## Stack

- **PHP 8.5 vanilla con MVC propio** (`app/Core`: Router, Controller, View, Database, helpers). **Sin framework y sin Composer**.
- **MariaDB** (PDO, prepared statements reales, utf8mb4).
- **Bootstrap 5.3** (CDN) + Bootstrap Icons.
- **jQuery** para eventos extra (filtros, confirmaciones, reCAPTCHA, menú móvil).
- **TinyMCE** como editor WYSIWYG del blog.
- Fuente **Rajdhani** (Google Fonts).
- **`.env` para los secretos** (BBDD, Mailjet, reCAPTCHA).

## Estructura

```
index.php                  # Front controller: define todas las rutas
config/config.php          # Constantes del sitio y carga del .env
app/
  Controllers/             # 9 controladores (público + intranet)
  Core/                    # Router, Controller, View, Database (PDO), helpers
  Models/                  # Blog, BlogCategoria, Contacto, Portfolio, Tech, Usuario
  Services/                # ImagenBlog, ImagenPortfolio (subidas), Mailjet, Recaptcha
  Views/                   # home, blog, portafolio, contacto, intranet, layout, errors
assets/                    # css/estilos.css, js/main.js, js/tinymce, img (logo)
img/                       # imágenes estáticas: blog/, portfolio/, tech/
uploads/                   # imágenes nuevas subidas desde la intranet (blog/)
```

## Base de datos

- BD en MariaDB, creada desde el dump legacy Laravel.
- Cambios en la migración: **slugs asignados a todos los portfolios**, nueva columna **`destacado`** y URLs absolutas de `blogs.text` reescritas a rutas relativas.

## Tema

- **Dark theme con azul como color destacado**, similar al sitio anterior. Bootstrap en modo `data-bs-theme="dark"`, estilos propios en `assets/css/estilos.css`.

## Referencias externas

- Sitio original del que se migra: **https://www.antoniolf.es**
- https://alftools.netlify.app
