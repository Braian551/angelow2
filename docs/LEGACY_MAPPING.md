# Angelow Legacy → Laravel Modules Mapping

Esta tabla resume cómo los directorios y scripts del proyecto `angelow/` se traducirán en módulos del nuevo monolito modular `angelow2/`.

> **Nota:** Los prefijos `admin/`, `ajax/`, `css/`, `docs/`, etc., provienen del proyecto legacy. Cada módulo nuevo apunta a integrar gradualmente dichos componentes.

## Tabla general

| Módulo Laravel | Áreas Legacy | Comentarios |
| --- | --- | --- |
| **Catalog** | `tienda/`, `producto/`, `admin/products.php`, `admin/categoria/`, `admin/colecciones/`, `ajax/productsearch.php`, `ajax/get_product_details.php`, `css/productos.css`, `css/subproducto.css` | Productos, variantes, filtros, colecciones, imágenes y catálogos públicos |
| **Sales** | `tienda/cart/`, `ajax/cart/`, `admin/orders.php`, `admin/order/`, `admin/descuento/`, `pagos/`, `docs/CORRECCIONES_ALERTAS_WISHLIST.md`, `docs/RESUMEN_EJECUTIVO_ANUNCIOS.md` | Carrito, checkout, pagos, descuentos, órdenes y reportes financieros |
| **Customers** | `auth/`, `users/`, `layouts/`, `tests/users.php`, `ajax/wishlist/`, `css/dashboarduser2.css`, `css/wishlist.css` | Autenticación, perfiles, direcciones, wishlists, panel del cliente |
| **Backoffice** | `admin/dashboardadmin.php`, `admin/inventario/`, `admin/modals/`, `admin/api/`, `docs/IMPLEMENTACION_ROLES.md`, `docs/GUIA_ANUNCIOS_SIMPLIFICADA.md` | Consola administrativa, inventario, métricas, gestión de roles y anuncios |
| **Notifications** | `alertas/`, `docs/NOTIFICACIONES_*`, `phpmailer`, `tcpdf`, `tests/voice/` | Emails, alertas de wishlist, PDFs, colas y plantillas |
| **Content** | `admin/announcements/`, `docs/GUIA_RAPIDA_ANUNCIOS.md`, `layouts/`, `images/`, `css/notificaciones/`, `css/announcements.css` | CMS ligero, banners, sliders, contenidos promocionales |
| **Support** | `conexionmongo.php`, `docker-compose.poc.yml`, `execute_fix.php`, `database/`, `docs/DOCKER_SETUP.md`, `docs/MICROSERVICES_PLAN.md` | Integraciones externas, scripts de mantenimiento, fixes y operaciones |
| **Shared** | `config.php`, `check_db.php`, `check_admins.php`, `docs/DIAGRAMA_CLASES_EXPLICACION.md`, `layouts/components` | Kernel compartido: helpers, config, entidades base, middlewares y recursos comunes |

## Assets legacy → ubicaciones nuevas

| Tipo | Legacy | Nuevo destino |
| --- | --- | --- |
| CSS | `css/` (subcarpetas admin, tienda, user) | `resources/css/modules/<Modulo>/` + `resources/css/shared/` |
| JS | `js/`, `ajax/**/*.js`, scripts inline | `resources/js/modules/<Modulo>/` (Vue/Alpine opcional) y `resources/js/lib/` |
| Imágenes | `images/`, `uploads/` | `resources/images/` para assets compilados, `storage/app/public/uploads/` para archivos de usuario |
| AJAX endpoints | `ajax/*.php` | Controladores HTTP en `app/Modules/*/UI/Http/Controllers` con rutas dedicadas |
| Tests | `tests/` legacy | `tests/Feature/<Modulo>/`, `tests/Unit/<Modulo>/` |
| Documentación | `docs/*.md` | `angelow2/docs/modules/<Modulo>.md` |

Este documento se actualizará conforme avancemos en la migración para mantener alineadas las responsabilidades de cada módulo.
