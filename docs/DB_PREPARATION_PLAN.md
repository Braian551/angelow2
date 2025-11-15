# Base de datos para angelow2

## Objetivos
- Reutilizar el dump `angelow (32).sql` como fuente de verdad sin perder datos existentes.
- Disponer de migraciones Laravel que puedan recrear el mismo esquema cuando se levante un entorno limpio.
- Agregar tablas "core" que Laravel necesita (jobs, failed_jobs, job_batches, sessions, personal_access_tokens) porque no existen en la base heredada.
- Normalizar la capa de autenticación para que el módulo **Customers** funcione con IDs tipo `varchar(20)` y campos adicionales (documentos, bloqueo, tokens).

## Tablas críticas y notas
| Tabla | Estado actual en dump | Acción en Laravel |
| --- | --- | --- |
| `users` | PK `varchar(20)`, roles `customer/admin`, campos identificación, `is_blocked`, `remember_token`, `token_expiry` | Actualizar migración `0000_create_users_table` para reflejar la estructura exacta y usar `string('id', 20)->primary()` en lugar de `id()` autoincremental. |
| `password_resets` | Tabla propia con PK numérica, token, expiración y bandera `is_used`. | Reemplazar tabla por esta versión (Laravel usa `password_reset_tokens`). Se ajustará la migración y el broker apuntará a `password_resets`. |
| `access_tokens` | Controla sesiones/API tokens (token, ip, UA, expiración). | Crear nueva migración dedicada para esta tabla (no existe en el esqueleto Laravel). |
| `user_addresses` | Direcciones con datos GPS, alias, flags `is_default`, `is_active`. | Crear migración tipo blueprint reflejando cada campo (importante para checkout y dashboard). |
| `user_applied_discounts` | Relación usuario–descuentos usados. | Crear migración para mantener historial de cupones. |
| `login_attempts` | Control de intentos fallidos para bloqueo automático. | Incluido en migración de soporte auth; usado por los nuevos controladores para bloquear tras 5 intentos en 1h. |
| `audit_users` + triggers | Ya existe, pero no lo manipulará Laravel directamente. Se mantendrá por dump y se documentará como dependencia externa. |

## Tablas core de Laravel que faltan en el dump
1. `jobs`
2. `job_batches`
3. `failed_jobs`
4. `personal_access_tokens`
5. `sessions` (solo si se desea manejar sesiones en DB)

Se crearán con migraciones individuales para no mezclar responsabilidades con el dump.

## Estrategia de migraciones
1. **Customizar migración base** `0001_01_01_000000_create_users_table.php`:
   - `Schema::create('users', ...)` con claves string y columnas extras.
   - Cambiar `password_reset_tokens` ➜ `password_resets` siguiendo el esquema heredado.
   - Crear `sessions` solo si no existen; Laravel se beneficiará al manejar sesiones en DB cuando se escale.
2. **Agregar migración `2025_11_14_XXXX_create_auth_support_tables.php`** para `access_tokens`, `user_addresses`, `user_applied_discounts`.
3. **Ejecutar `php artisan queue:table`, `queue:failed-table`, `queue:batches-table`, `sanctum:install`** como referencia y copiar los blueprints dentro de migraciones versionadas para mantener control.
4. Documentar dependencias en `.env` (`DB_*`) y en `docs/DB_PREPARATION_PLAN.md` (este archivo).

## Consideraciones adicionales
- Los IDs `varchar(20)` se generarán con `Str::ulid()` desde los form requests (controladores Customers) para mantener compatibilidad.
- No se tocarán triggers ni stored procedures desde Laravel; se asume que continúan viviendo en el dump.
- Cuando corra `php artisan migrate` sobre una base ya creada desde el dump, será necesario marcar migraciones como ejecutadas (ej. `php artisan migrate --pretend` + insertar registros en `migrations`). Para entornos nuevos basta con ejecutar migraciones antes de importar datos.
- Próximo paso tras ajustar migraciones: construir controladores Auth (`LoginController`, `RegisterController`, `LogoutController`) usando los modelos que apuntan al esquema actualizado.
