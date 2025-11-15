@php
    $user = auth()->user();
@endphp

<header class="main-header">
    <div class="header-container">
        <div class="content-logo2">
            <a href="{{ route('catalog.home') }}">
                <img src="{{ asset('images/legacy/logo2.png') }}" alt="Angelow - Ropa Infantil" width="100">
            </a>
        </div>

        <div class="search-bar">
            <form action="{{ url('/tienda/productos') }}" method="get" class="search-form">
                <input type="text" name="search" id="header-search" placeholder="Buscar productos..." autocomplete="off" value="{{ $headerSearchTerm ?? '' }}">
                <button type="submit" aria-label="Buscar">
                    <i class="fas fa-search"></i>
                </button>
                <div class="search-results" id="search-results"></div>
            </form>
        </div>

        <div class="header-icons">
            <a href="{{ auth()->check() ? route('customers.dashboard') : route('customers.login') }}" aria-label="Mi cuenta">
                <i class="fas fa-user"></i>
            </a>
            @if (auth()->check())
                <a href="{{ url('/users/wishlist') }}" aria-label="Favoritos">
                    <i class="fas fa-heart"></i>
                </a>
            @else
                <a href="{{ route('customers.login', ['redirect' => url()->current()]) }}" aria-label="Favoritos">
                    <i class="fas fa-heart"></i>
                </a>
            @endif
            <a href="{{ url('/tienda/pagos/cart') }}" aria-label="Carrito" class="cart-link">
                <i class="fas fa-shopping-cart"></i>
                @if (($headerCartCount ?? 0) > 0)
                    <span class="cart-count">{{ $headerCartCount }}</span>
                @endif
            </a>
        </div>
    </div>

    <nav class="main-nav">
        <ul>
            <li><a href="{{ route('catalog.home') }}">Inicio</a></li>
            <li class="mega-menu">
                <a href="{{ url('/ninas') }}">Niñas</a>
                <div class="mega-menu-content">
                    <div class="mega-menu-column">
                        <h4>Por categoría</h4>
                        <ul>
                            <li><a href="{{ url('/ninas-vestidos') }}">Vestidos</a></li>
                            <li><a href="{{ url('/ninas-conjuntos') }}">Conjuntos</a></li>
                            <li><a href="{{ url('/ninas-pijamas') }}">Pijamas</a></li>
                            <li><a href="{{ url('/ninas-zapatos') }}">Zapatos</a></li>
                        </ul>
                    </div>
                    <div class="mega-menu-column">
                        <h4>Por edad</h4>
                        <ul>
                            <li><a href="{{ url('/ninas-0-12m') }}">0-12 meses</a></li>
                            <li><a href="{{ url('/ninas-1-3a') }}">1-3 años</a></li>
                            <li><a href="{{ url('/ninas-4-6a') }}">4-6 años</a></li>
                            <li><a href="{{ url('/ninas-7-10a') }}">7-10 años</a></li>
                        </ul>
                    </div>
                    <div class="mega-menu-column">
                        <img src="{{ asset('images/legacy/ropa.png') }}" alt="Colección niñas">
                    </div>
                </div>
            </li>
            <li class="mega-menu"><a href="{{ url('/ninos') }}">Niños</a></li>
            <li><a href="{{ url('/bebes') }}">Bebés</a></li>
            <li><a href="{{ url('/novedades') }}">Novedades</a></li>
            <li><a href="{{ url('/ofertas') }}">Ofertas</a></li>
            <li><a href="{{ url('/colecciones') }}">Colecciones</a></li>
        </ul>
    </nav>
</header>
