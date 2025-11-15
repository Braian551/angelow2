# Módulo Support

- **Origen legacy:** `conexionmongo.php`, `execute_fix.php`, `database/scripts/`, `docs/DOCKER_SETUP.md`, `docs/MICROSERVICES_PLAN.md`.
- **Responsabilidades:** integraciones externas (Mongo, microservicios), tareas operativas, fixes automatizados, monitoreo.
- **Dependencias:** tablas de auditoría, conexiones externas, colas programadas.
- **Assets:** `resources/js/modules/support` para paneles técnicos, vistas en `resources/views/modules/support`.
- **Rutas:** `routes/support.php`.
- **Pruebas:** `tests/Feature/Modules/Support`, `tests/Unit/Modules/Support`.

Usa este doc para describir cronjobs, pipelines DevOps y procedimientos de mantenimiento.
