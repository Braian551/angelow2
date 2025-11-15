![Angelow 2](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

# Angelow 2 — Monolito Modular en Laravel 12

Este repositorio alojará la re-implementación de **Angelow** (tienda online de ropa infantil) sobre Laravel 12 con una arquitectura modular dentro de un monolito. La meta es migrar progresivamente el código PHP legado que vive en `../angelow` hacia un stack mantenible, con capas bien definidas y módulos aislados.

## 1. Radiografía del proyecto legado

| Dominio funcional | Código heredado | Observaciones |
| --- | --- | --- |
| **Catálogo y buscador** | `tienda/`, `producto/`, `ajax/productsearch.php`, `ajax/get_product_details.php` | Catálogo, variaciones, filtros y AJAX personalizados |
| **Carrito y pedidos** | `tienda/cart`, `ajax/cart/`, `orders.php`, `tests/cart/` | Lógica mezclada entre PHP procedimental y JS |
| **Usuarios y auth** | `auth/`, `users/`, `layouts/` | Autenticación propia + Google OAuth, panel cliente |
| **Administración** | `admin/` (productos, inventario, órdenes, anuncios) | Código acoplado por vistas, poca reutilización |
| **Notificaciones/alertas** | `alertas/`, `docs/NOTIFICACIONES_*`, `ajax/wishlist/` | Alertas de wishlist, correos y modales |
| **Pagos y documentos** | `pagos/`, `docs/RESUMEN_EJECUTIVO_ANUNCIOS.md`, `tests/admin/` | Integración básica con transferencias y manejo de facturas PDF |
| **Infraestructura** | `database/`, `docs/`, `docker-compose.poc.yml`, `tests/` | Amplia documentación, scripts SQL y procedimientos almacenados |

Esta fotografía sirve como input para definir los módulos Laravel.

## 2. Arquitectura modular propuesta

La aplicación seguirá siendo un monolito, pero organizado como **módulos autocontenidos** ubicados en `app/Modules/<NombreModulo>`. Cada módulo tendrá `Domain`, `Application`, `Infrastructure` y `UI` (HTTP/API) según la complejidad.

```
app/
	Modules/
		Catalog/
		Sales/
		Customers/
		Backoffice/
		Content/
		Notifications/
		Support/
	Shared/          # Cross-cutting (DDD shared kernel)
bootstrap/
routes/
config/modules.php
database/
```

### Módulos sugeridos

1. **Catalog** — Productos, variantes, categorías, colecciones, búsqueda y assets. Origen: `tienda/`, `producto/`, `admin/categoria`, `admin/colecciones`, `css/productos.css`, `docs/GUIA_RAPIDA_DIAGRAMA.md`.
2. **Sales** — Carrito, órdenes, pagos, descuentos, facturación. Origen: `tienda/cart`, `orders.php`, `pagos/`, `admin/order`, `admin/discount`, `docs/CORRECCIONES_ALERTAS_WISHLIST.md`.
3. **Customers** — Usuarios, direcciones, autenticación, wishlist, perfiles. Origen: `auth/`, `users/`, `ajax/wishlist`, `css/dashboarduser2.css`.
4. **Backoffice** — Dashboard admin, gestión inventario, anuncios, reportes. Origen: `admin/`, `admin/api`, `docs/IMPLEMENTACION_ROLES.md`, `docs/GUIA_ANUNCIOS_SIMPLIFICADA.md`.
5. **Notifications** — Emails, alertas wishlist, notificaciones en tiempo real. Origen: `alertas/`, `docs/NOTIFICACIONES_*`, `phpmailer`, `tcpdf`.
6. **Content** — Gestión de páginas marketing, banners, sliders, anuncios públicos. Origen: `admin/announcements`, `docs/RESUMEN_EJECUTIVO_ANUNCIOS.md`.
7. **Support** — Integraciones externas (Mongo, microservicio delivery), auditoría, jobs. Origen: `conexionmongo.php`, `docs/DELIVERY_SEPARADO.md`, `execute_fix.php`.
8. **Shared** — Entidades base, value objects, servicios comunes (logging, tenancy, config). Origen: `config.php`, `layouts/`, helpers.

Cada módulo define su propio namespace (`Angelow\Modules\Catalog`), archivos de rutas (`routes/catalog.php`), providers y migraciones dedicadas (`database/migrations/catalog`).

## 3. Convenciones y herramientas

- **Laravel Modules Light**: carpeta `app/Modules` gestionada via Service Providers manuales, sin paquetes externos pesados.
- **Routing**: `routes/web.php` y `routes/api.php` solamente montan los archivos de cada módulo (`require base_path('routes/catalog.php');`).
- **Testing**: `tests/Feature/Catalog`, `tests/Unit/Sales`. Migrar progresivamente los tests actuales (`tests/` del legado) y reescribirlos.
- **Migrations/Seeders**: cada módulo aporta migraciones propias con prefijo (`2025_11_15_000000_create_catalog_products_table.php`).
- **DTOs/Actions**: usar `app/Shared/Application/Commands`, `app/Shared/Infrastructure/Bus` para orquestar casos de uso.
- **Integración temporal**: mientras se migra, exponer APIs en Laravel que consuman las tablas existentes (`angelow` DB) para no duplicar datos.

## 4. Fases de migración sugeridas

1. **Infraestructura base**
	 - Configurar `.env` con la base de datos actual.
	 - Registrar módulos vacíos con rutas dummy.
	 - Preparar pipelines de CI/CD (Pint, PHPUnit, Laravel Pint, PHPStan opcional).

2. **Dominio Catalog + Shared**
	 - Mapear tablas `products`, `categories`, `product_color_variants`, etc.
	 - Crear seeders que lean datos actuales para entornos locales (solo lectura al inicio).
	 - Exponer catálogo (read-only) en Laravel para front.

3. **Usuarios y Auth**
	 - Migrar entidades `users`, roles y tokens.
	 - Portar middleware actuales (`auth_middleware.php`, `role_redirect.php`).
	 - Implementar Passport/Sanctum o session guard equivalente.

4. **Carrito y Pedidos**
	 - Reescribir carrito y checkout usando `Sales`.
	 - Conectar con `payment_transactions`, `discount_codes`.
	 - Añadir pruebas end-to-end.

5. **Backoffice**
	 - Migrar dashboards, CRUDs y reportes.
	 - Integrar `Livewire` o Inertia para UI moderna según preferencia.

6. **Notificaciones/Alertas**
	 - Portar alertas wishlist y correos a Notifications + Laravel Mailables.
	 - Configurar colas (Redis / database) para los envíos.

7. **Desacoplar servicios legacy**
	 - Remover dependencias a scripts PHP sueltos.
	 - Consolidar cronjobs en `app/Console/Kernel.php`.

## 5. Plan de carpetas recomendado

```
angelow2/
	app/
		Modules/
			Catalog/
				Domain/
				Application/
				Infrastructure/
				UI/Http/
			Sales/
			Customers/
			Backoffice/
			Notifications/
			Content/
			Support/
		Shared/
			Domain/
			Application/
			Infrastructure/
			Support/Helpers
	bootstrap/
	config/
		modules.php
	database/
		migrations/
		seeders/
	routes/
		catalog.php
		sales.php
		customers.php
		backoffice.php
		notifications.php
	tests/
		Feature/
		Unit/
	README.md (este archivo)
```

### Service Providers

Crear un provider por módulo (`php artisan make:provider CatalogServiceProvider`) para registrar:
- bindings de servicios
- rutas (`$this->loadRoutesFrom()`)
- migraciones (`$this->loadMigrationsFrom()`)
- vistas (`$this->loadViewsFrom()` si aplica)

Registrar cada provider en `config/app.php` o automáticamente via `ModulesServiceProvider` centralizado.

## 6. Roadmap operativo

- [ ] Instalar PHPStan/Larastan y definir reglas de calidad.
- [ ] Definir estrategia de CI (GitHub Actions) para tests + Pint.
- [ ] Escribir documentación de cada módulo en `docs/modules/<module>.md`.
- [ ] Crear scripts de sincronización temporal (lectura) desde la DB legada.
- [ ] Migrar gradualmente front-end: empezar con endpoints API y conectar los front actuales vía AJAX.

## 7. Comandos útiles

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
php artisan test
```

> ⚠️ Nota: durante la creación del proyecto (`composer create-project`) la migración SQLite falló por falta del driver. No es crítico; ajusta `.env` para usar la base de datos MySQL existente antes de correr más migraciones.

## 8. Próximos pasos inmediatos

1. Configurar `.env` apuntando a la misma base `angelow` para obtener datos reales de lectura.
2. Crear el módulo `Catalog` con entidades `Product`, `Variant`, `Category` y controladores read-only.
3. Documentar los límites de contexto y definir contratos de integración con el sistema legacy mientras dure la migración.

---

Este README funcionará como mapa para la transición del stack actual a Laravel 12 manteniendo un solo repositorio y desplegable monolítico pero con módulos independientes.
