<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Angelow')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Font Awesome is bundled via Vite; remove CDN to avoid integrity mismatch in dev. --}}
    @php
        $baseEntries = [
            'resources/css/app.css',
            'resources/css/shared/base.css',
            'resources/js/app.js',
            'resources/js/shared/utils.js',
        ];
        $viteManifestExists = file_exists(public_path('build/manifest.json'));
    @endphp

    @if ($viteManifestExists)
        @vite($baseEntries)
    @endif
    @php
        $appRuntimeConfig = [
            'baseUrl' => url('/'),
            'csrfToken' => csrf_token(),
            'isAuthenticated' => auth()->check(),
        ];
    @endphp
    <script>
        window.App = <?php echo json_encode($appRuntimeConfig); ?>;
    </script>
    @stack('styles')
</head>
<body class="bg-white text-slate-900">
    @include('layouts.partials.header')
    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
