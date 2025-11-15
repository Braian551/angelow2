# Guía de Assets Modulares

Esta guía explica cómo se organizan los assets (CSS, JS, imágenes y archivos subidos por usuarios) en el nuevo monolito Laravel.

## 1. CSS

```
resources/css/
 ├── app.css              # estilos base del shell
 ├── shared/              # tokens, utilidades, layouts genéricos
 │    └── base.css
 └── modules/
      ├── catalog/index.css
      ├── sales/index.css
      ├── customers/index.css
      ├── backoffice/index.css
      ├── notifications/index.css
      ├── content/index.css
      └── support/index.css
```

Cada módulo define su propio punto de entrada para Vite. Evita mezclar estilos entre módulos; comparte sólo a través de `shared/`.

## 2. JavaScript

```
resources/js/
 ├── app.js
 ├── bootstrap.js
 ├── shared/utils.js      # helpers globales
 ├── lib/                 # librerías vanilla o wrappers
 └── modules/
      ├── catalog/index.js
      └── ... (uno por módulo)
```

Los scripts que antes vivían en `ajax/*.php` deberán exponerse como controladores + endpoints REST/JSON dentro de `app/Modules/*/UI/Http/Controllers/Api`. La parte de front que los consume residirá en los módulos correspondientes de JS.

## 3. Imágenes

```
resources/images/
 ├── shared/              # íconos o fondos reutilizables
 └── <module>/            # assets empaquetados por Vite
```

Los assets compilados se publicarán con `php artisan vendor:publish` (si aplica) o `npm run build`.

## 4. Uploads de usuario

```
storage/app/public/uploads/
 ├── products/
 ├── users/
 ├── documents/
 └── banners/
```

Ejecuta `php artisan storage:link` para exponerlos vía `public/storage`. Cada módulo debe usar `Storage::disk('public')` con sub-carpetas propias (`/uploads/products`).

## 5. Rutas y controladores AJAX

- `routes/<module>.php` registra endpoints para cada dominio.
- Controladores HTTP viven en `app/Modules/*/UI/Http/Controllers`.
- Las variantes API/AJAX se alojan en `.../Controllers/Api`.

## 6. Pruebas

Los tests se agrupan en `tests/Feature/Modules/<Module>` y `tests/Unit/Modules/<Module>`. Añade factories o seeders por módulo según sea necesario.

Mantén este documento actualizado a medida que se agreguen nuevos módulos o tipos de assets.
