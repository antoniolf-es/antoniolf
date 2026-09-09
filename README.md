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

## Despliegue (CI/CD)

Al pushear a `main`, GitHub Actions despliega automáticamente a **https://www.antoniolf.es** por **FTPS** con [git-ftp](https://github.com/git-ftp/git-ftp) (`.github/workflows/deploy.yml`):

- El último commit desplegado queda anotado en `.git-ftp.log` en el servidor: cada push sube **solo el diff** del commit y borra lo eliminado. La primera ejecución (`--auto-init`) sube todo lo trackeado y marca el estado.
- Solo se sincronizan ficheros trackeados: `img/`, `uploads/` y el `.env` de producción **no se tocan nunca** (viven únicamente en el servidor).
- Los dotfiles (`.env`, `.git-ftp.log`) devuelven **403** por el bloque `FilesMatch` del `.htaccess`.
- Los deploys se encolan (`concurrency`), nunca se solapan dos subidas.

### Secrets requeridos (Settings → Secrets and variables → Actions)

| Secret | Valor |
|---|---|
| `FTP_URL` | `ftpes://host:21/public_html` — **incluir siempre la ruta a la carpeta del sitio** (la que aparece como "Remote site" en FileZilla). Sin ruta, git-ftp sube a la raíz del FTP, donde vive el `.env` de producción (un nivel por encima del docroot). TLS explícito; si el hosting usa implícito: `ftps://host:990` |
| `FTP_USER` | Usuario FTP del hosting |
| `FTP_PASS` | Contraseña FTP |

Variable opcional (pestaña *Variables* del mismo menú): `FTP_DISABLE_EPSV=1` si el hosting rechaza el modo pasivo extendido.

### Modos manuales (Actions → Deploy → Run workflow)

- **push** — despliegue normal (el que corre en cada push a `main`).
- **dry-run** — muestra qué subiría, sin tocar nada (ideal para depurar la conexión FTPS).
- **catchup** — marca el commit actual como desplegado sin subir nada (para reparar el estado tras cambios manuales en el servidor).

### Base de datos

El despliegue **no toca la BD**: los cambios de esquema se aplican a mano en producción (phpMyAdmin o script SQL).