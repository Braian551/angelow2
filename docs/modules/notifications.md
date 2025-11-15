# Módulo Notifications

- **Origen legacy:** `alertas/`, `docs/NOTIFICACIONES_*`, integraciones con PHPMailer/TCPDF.
- **Responsabilidades:** correos transaccionales, alertas de wishlist, plantillas PDF/HTML, colas.
- **Dependencias:** tablas `notifications`, `wishlist_alerts`, `email_templates`, colas Redis/DB.
- **Assets:** `resources/css/modules/notifications`, `resources/js/modules/notifications`, vistas en `resources/views/modules/notifications`.
- **Rutas:** `routes/notifications.php`.
- **Pruebas:** `tests/Feature/Modules/Notifications`, `tests/Unit/Modules/Notifications`.

Incluye planes para migrar PHPMailer a `Illuminate\Mail` y documentación de plantillas.
