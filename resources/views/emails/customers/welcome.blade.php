<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a Angelow</title>
    <style>
        body {font-family: 'Outfit', Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 0;}
        .container {max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);}
        .header {background: linear-gradient(135deg, #0ea5e9, #6366f1); color: #ffffff; padding: 40px 30px; text-align: center;}
        .header h1 {margin: 0; font-size: 32px; letter-spacing: -0.02em;}
        .content {padding: 40px 30px; line-height: 1.7;}
        .content h2 {color: #0f172a; margin-top: 0; font-size: 24px;}
        .content p {margin: 0 0 16px;}
        .features {list-style: none; padding: 0; margin: 24px 0;}
        .features li {margin-bottom: 12px; padding-left: 28px; position: relative;}
        .features li::before {content: '•'; color: #0ea5e9; position: absolute; left: 0; font-size: 20px; line-height: 1;}
        .button {display: inline-block; padding: 14px 32px; background: #0ea5e9; color: #ffffff!important; text-decoration: none; border-radius: 999px; font-weight: 600; margin-top: 20px;}
        .footer {padding: 24px 30px; text-align: center; font-size: 13px; color: #475569; background: #f8fafc;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido/a a Angelow!</h1>
        </div>
        <div class="content">
            <h2>Hola {{ $user->name }},</h2>
            <p>Gracias por unirte a nuestra comunidad. Desde ahora podrás disfrutar de experiencias de compra personalizadas, lanzamientos exclusivos y seguimiento en tiempo real de tus pedidos.</p>
            <p>Con tu cuenta podrás:</p>
            <ul class="features">
                <li>Acceder a colecciones y descuentos exclusivos.</li>
                <li>Guardar tus direcciones y métodos preferidos de envío.</li>
                <li>Crear listas de deseos y recibir alertas de stock.</li>
                <li>Seguir tus pedidos y recibir notificaciones en vivo.</li>
            </ul>
            <p>Si no fuiste tú quien creó esta cuenta, ignora este correo o contáctanos de inmediato.</p>
            <a href="{{ config('app.url') }}" class="button">Descubrir Angelow</a>
        </div>
        <div class="footer">
            © {{ date('Y') }} Angelow · Este email se envió automáticamente. No respondas a esta dirección.
        </div>
    </div>
</body>
</html>
