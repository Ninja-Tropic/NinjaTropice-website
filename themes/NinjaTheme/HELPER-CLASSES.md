# Helper Classes

Clases reutilizables del theme. Se pueden añadir desde el editor de WordPress (campo "Clase CSS adicional" del bloque) o directamente en el HTML/PHP.

Después de modificar el SCSS, recompilar con `npm run build`.

---

## `.bg-glow` — Fondo degradado radial

Archivo: [scss/components/_bg-glow.scss](scss/components/_bg-glow.scss)

Crea un fondo de ancho completo (100vw) con un pseudo-elemento `::before` detrás del contenido. Los degradados son radiales y terminan en `transparent`, por lo que no hay cortes visibles arriba ni abajo.

Se usa combinando la clase base con una variante:

| Clases | Resultado |
| --- | --- |
| `bg-glow bg-glow--purple` | Solo morado |
| `bg-glow bg-glow--orange` | Solo naranja |
| `bg-glow bg-glow--mix` | Morado a la izquierda, naranja a la derecha (como hero-stats) |

```html
<section class="bg-glow bg-glow--mix">
    ...
</section>
```

**Variables CSS**

| Variable | Default | Descripción |
| --- | --- | --- |
| `--bg-glow-height` | `140%` | Alto del pseudo-elemento respecto a la sección. Se centra verticalmente. |

```html
<section class="bg-glow bg-glow--purple" style="--bg-glow-height: 120%">
```

**Notas**

- La sección no debe tener un color de fondo propio, o taparía el degradado.
- La clase añade `position: relative` e `isolation: isolate` a la sección.
- Para ajustar la intensidad, cambiar el alfa del primer color de cada variante en el SCSS.

---

## `.media-frame` — Marco glass para imagen/video

Archivo: [scss/components/_media-frame.scss](scss/components/_media-frame.scss)

Replica el marco de `hero-stats__media`: borde blanco translúcido, fondo semitransparente con blur, sombra suave y esquinas redondeadas.

Se aplica a un contenedor cuyo hijo directo es la imagen, video o iframe:

```html
<div class="media-frame">
    <img src="..." alt="...">
</div>
```

Elementos hijo soportados: `> img`, `> video`, `> iframe`, `> picture img`. Quedan a 100% de ancho y con el radio interno calculado (`radio - padding`).

**Variables CSS**

| Variable | Default | Móvil (≤ 820px) |
| --- | --- | --- |
| `--media-frame-padding` | `18px` | `0.6rem` |
| `--media-frame-radius` | `36px` | `1.35rem` |

```html
<div class="media-frame" style="--media-frame-padding: 12px; --media-frame-radius: 24px">
```

**Notas**

- Un bloque Imagen de WordPress genera `figure > img`; en ese caso la clase no cubre al `img` hijo del `figure`.

---

## Texto con degradado animado

Ver [GRADIENT-TEXT.md](GRADIENT-TEXT.md): shortcode `[gradient]...[/gradient]` y función PHP `ninjatheme_gradient_text()`.

---

## Combinar helpers

Fondo glow con una imagen enmarcada, como en el hero:

```html
<section class="bg-glow bg-glow--mix">
    <h1>Título</h1>
    <div class="media-frame">
        <img src="..." alt="...">
    </div>
</section>
```
