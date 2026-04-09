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

Al ejecutar `docker compose up` por primera vez (volumen vacío), MySQL importa automáticamente el archivo `db/dump.sql` si existe.

> Este proceso solo ocurre cuando el volumen `db_data` no tiene datos. Si el contenedor ya fue inicializado, el dump no se vuelve a importar.

Para forzar una reimportación desde cero:

```bash
docker compose down -v          # elimina volúmenes
docker compose up -d --build    # levanta e importa dump.sql automaticamente
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

Genera un dump con fecha en la carpeta `db/`:

```bash
docker exec ninjatropice_db mysqldump -u root -p"$(grep MYSQL_ROOT_PASSWORD .env | cut -d= -f2)" wordpress > db/backup-$(date +%Y%m%d-%H%M).sql
```

El archivo se guardará como `db/backup-YYYYMMDD-HHMM.sql`.

Para restaurar un backup manualmente:

```bash
# 1. Copia el backup como dump.sql
cp db/backup-YYYYMMDD-HHMM.sql db/dump.sql

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

## Estructura del proyecto

```
NinjaTropice-website/
├── db/                  # dump.sql se auto-importa al levantar por primera vez
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
