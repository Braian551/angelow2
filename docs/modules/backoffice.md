# Módulo Backoffice

- **Origen legacy:** `admin/` (dashboard, inventario, modals, api), `docs/IMPLEMENTACION_ROLES.md`.
- **Responsabilidades:** panel administrativo, reporting, gestión de inventario, anuncios internos.
- **Dependencias:** tablas `inventory_movements`, `bulk_discount_rules`, `audit_orders`, `audit_categories`.
- **Assets:** `resources/css/modules/backoffice`, `resources/js/modules/backoffice`.
- **Rutas:** `routes/backoffice.php` (middleware `auth`).
- **Pruebas:** `tests/Feature/Modules/Backoffice`, `tests/Unit/Modules/Backoffice`.

Describe aquí políticas de autorización, flujos de UI (Livewire/Inertia) y requisitos de observabilidad.
