<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Angelow - Autenticación')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Font Awesome handled by Vite bundle; CDN removed. --}}
    @vite(['resources/css/app.css', 'resources/css/shared/base.css', 'resources/js/app.js', 'resources/js/shared/utils.js'])
        @php
            $authRuntimeConfig = [
                'baseUrl' => url('/'),
                'csrfToken' => csrf_token(),
                'isAuthenticated' => auth()->check(),
            ];
        @endphp
        <script>
            window.App = <?php echo json_encode($authRuntimeConfig); ?>;
        </script>
    @stack('styles')
</head>
<body class="auth-layout">
    <main class="auth-main">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
