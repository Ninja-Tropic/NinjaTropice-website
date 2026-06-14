# NinjaTropice WordPress — Docker

WordPress dockerizado con MySQL, phpMyAdmin y WP-CLI. El tema activo es **NinjaTheme** con soporte SCSS y BrowserSync.

---

## Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Node.js + npm (para desarrollo del tema)

---

## Configuración inicial

1. Copia el archivo de variables de entorno:
   ```bash
   cp .env.example .env
   ```
2. Edita `.env` y cambia las contraseñas por defecto.

---

## Levantar el entorno

```bash
docker compose up -d --build
```

| Servicio     | URL                        |
|--------------|----------------------------|
| WordPress    | http://localhost:8080       |
| phpMyAdmin   | http://localhost:8081       |

### Auto-importación de la base de datos

Al ejecutar `docker compose up` por primera vez (volumen vacío), MySQL importa automáticamente el archivo `db/dump.sql.gz` si existe.

> Este proceso solo ocurre cuando el volumen `db_data` no tiene datos. Si el contenedor ya fue inicializado, el dump no se vuelve a importar.

Para forzar una reimportación desde cero:

```bash
docker compose down -v          # elimina volúmenes
docker compose up -d --build    # levanta e importa dump.sql.gz automaticamente
```

Después de reimportar, actualiza las URLs:

```bash
docker exec ninjatropice_wp wp search-replace 'http://localhost:8888/ninja26' 'http://localhost:8080' --all-tables --allow-root
```

---

## Detener el entorno

```bash
# Detener sin eliminar datos
docker compose down

# Detener y eliminar todos los datos (volúmenes)
docker compose down -v
```

---

## Backup de la base de datos

Genera un dump comprimido con fecha en la carpeta `db/`:

```bash
docker exec ninjatropice_db mysqldump -u root -p"$(grep MYSQL_ROOT_PASSWORD .env | cut -d= -f2)" wordpress | gzip > db/backup-$(date +%Y%m%d-%H%M).sql.gz
```

El archivo se guardará como `db/backup-YYYYMMDD-HHMM.sql.gz`.

> Los archivos `.sql` planos están excluidos de git. Usa siempre `.sql.gz` para evitar que GitHub detecte credenciales en el dump.

Para restaurar un backup manualmente:

```bash
# 1. Copia el backup como dump.sql.gz
cp db/backup-YYYYMMDD-HHMM.sql.gz db/dump.sql.gz

# 2. Baja el entorno eliminando volúmenes y vuelve a levantar
docker compose down -v
docker compose up -d --build
```

---

## Desarrollo del tema (NinjaTheme)

```bash
cd themes/NinjaTheme
npm install       # solo la primera vez
npm run dev       # compila SCSS + BrowserSync en http://localhost:3000
```

BrowserSync proxea WordPress (`localhost:8080`) y recarga el navegador al guardar cambios en PHP, SCSS o JS.

Para compilar para producción:

```bash
npm run build:prod
```

---

## Shortcodes

### `[projects_grid]`

Muestra el grid de proyectos con filtros interactivos (Industry, Animation Style, Art Style, Training Topic).

| Atributo | Default | Descripción |
|----------|---------|-------------|
| `per_page` | `12` | Proyectos por página |
| `orderby` | `menu_order` | Campo de ordenamiento (`menu_order`, `date`, `title`) |
| `order` | `ASC` | Dirección (`ASC` / `DESC`) |
| `category` | — | Slug de categoría para pre-filtrar (separar varios con coma) |

**Ejemplos:**
```
[projects_grid]
[projects_grid per_page="6" order="DESC"]
[projects_grid category="motion-graphics"]
[projects_grid category="motion-graphics,explainer-video"]
```

---

### `[blog_grid]`

Muestra el grid de artículos del blog con filtros por Category y Topic (tag).

| Atributo | Default | Descripción |
|----------|---------|-------------|
| `per_page` | `9` | Artículos por página |
| `orderby` | `date` | Campo de ordenamiento |
| `order` | `DESC` | Dirección (`ASC` / `DESC`) |
| `category` | — | Slug de categoría para pre-filtrar (separar varios con coma) |
| `tag` | — | Slug de tag para pre-filtrar (separar varios con coma) |

**Ejemplos:**
```
[blog_grid]
[blog_grid per_page="6"]
[blog_grid category="tutorials"]
[blog_grid category="tutorials" tag="motion-graphics"]
```

---

### `[case_studies_grid]`

Muestra el grid de case studies con botón "Load more".

| Atributo | Default | Descripción |
|----------|---------|-------------|
| `per_page` | `9` | Case studies por página |
| `orderby` | `date` | Campo de ordenamiento |
| `order` | `DESC` | Dirección (`ASC` / `DESC`) |
| `category` | — | Slug de categoría para pre-filtrar (separar varios con coma) |

**Ejemplos:**
```
[case_studies_grid]
[case_studies_grid per_page="3"]
[case_studies_grid category="saas"]
[case_studies_grid category="saas,fintech"]
```

---

### Cómo encontrar el slug de una categoría

En el WP Admin ve a **Posts → Categories**. El slug aparece en la columna *Slug* de cada categoría. Siempre es minúsculas con guiones: `motion-graphics`, `saas`, `training-videos`.

---

## Estructura del proyecto

```
NinjaTropice-website/
├── db/                  # dump.sql.gz se auto-importa al levantar por primera vez
├── php-config/
│   └── uploads.ini      # limites de subida (128MB)
├── plugins/             # plugins locales (montados en el contenedor)
├── themes/
│   └── NinjaTheme/      # tema activo
├── uploads/             # archivos subidos (no se sube a git)
├── Dockerfile           # WordPress + WP-CLI
├── docker-compose.yml
├── .env                 # credenciales locales (no se sube a git)
└── .env.example         # plantilla de credenciales
```
