<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Iniciar Sesión - Angelow')</title>
    
    <!-- CDN Externos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- CSS de Componentes -->
    <link rel="stylesheet" href="{{ asset('css/formlogin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alerta.css') }}">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    
    @stack('styles')
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html>