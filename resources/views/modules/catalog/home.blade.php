@extends('layouts.app')

@section('title', 'Angelow - Ropa Infantil Premium')

@php
    $catalogViteReady = file_exists(public_path('build/manifest.json'));
@endphp

@push('styles')
    @php
        // Vite is configured with a single catalog entry `index.css` — use it to avoid manifest errors
        $catalogCss = ['resources/css/modules/catalog/index.css'];
    @endphp
    @if ($catalogViteReady)
        @vite($catalogCss)
    @endif
@endpush

@push('scripts')
    @if ($catalogViteReady)
        @vite(['resources/js/modules/catalog/index.js'])
    @endif
    @php
        $catalogRuntimeConfig = [
            'isAuthenticated' => auth()->check(),
            'wishlist' => [
                'add' => url('/api/wishlist/add'),
                'remove' => url('/api/wishlist/remove'),
                'list' => url('/api/wishlist'),
            ],
            'fallbackImage' => asset('images/default-product.jpg'),
        ];

        echo '<script>window.AngelowCatalog = ' . json_encode($catalogRuntimeConfig, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ';</script>';
    @endphp
@endpush

@section('content')
    @if ($topBarAnnouncement)
        <div class="announcement-bar">
            <p>
                @if (!empty($topBarAnnouncement->icon))
                    <i class="fas {{ $topBarAnnouncement->icon }}"></i>
                @endif
                {{ $topBarAnnouncement->message }}
            </p>
        </div>
    @endif

    <section class="hero-banner" data-slider>
        <div class="hero-slider">
            @forelse ($sliders as $index => $slider)
                <div class="hero-slide {{ $loop->first ? 'active' : '' }}" data-slide="{{ $index }}">
                    <img src="{{ asset($slider->image_path ?? 'images/hero-default.jpg') }}" alt="{{ $slider->title ?? 'Slide' }}">
                    <div class="hero-content">
                        <h1>{{ $slider->title ?? 'Descubre la nueva colección' }}</h1>
                        @if (!empty($slider->subtitle))
                            <p>{{ $slider->subtitle }}</p>
                        @endif
                        @if (!empty($slider->button_link) && !empty($slider->button_text))
                            <a href="{{ $slider->button_link }}" class="btn">{{ $slider->button_text }}</a>
                        @elseif (!empty($slider->link))
                            {{-- If legacy `link` exists in DB but no button text, show default CTA text --}}
                            <a href="{{ $slider->link }}" class="btn">Ver más</a>
                        @else
                            {{-- Default fallback to tienda page for missing slider CTAs --}}
                            <a href="{{ url('/tienda') }}" class="btn">Ver más</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="hero-slide active">
                    <img src="{{ asset('images/legacy/default-hero.jpg') }}" alt="Colección Angelow">
                    <div class="hero-content">
                        <h1>Moda infantil premium</h1>
                        <p>Prendas cómodas y seguras para los más pequeños</p>
                        <a href="{{ url('/tienda') }}" class="btn">Ver más</a>
                    </div>
                </div>
            @endforelse
        </div>
        @if (count($sliders) > 1)
            <div class="hero-dots">
                @foreach ($sliders as $index => $slider)
                    <span class="dot {{ $loop->first ? 'active' : '' }}" data-slide="{{ $index }}"></span>
                @endforeach
            </div>
            <button class="hero-prev" aria-label="Anterior">❮</button>
            <button class="hero-next" aria-label="Siguiente">❯</button>
        @endif
    </section>

    <section class="featured-categories">
        <h2 class="section-title">Explora nuestras categorías</h2>
        <div class="categories-grid">
            @forelse ($categories as $category)
                <a href="{{ url('/tienda/productos?category=' . $category->id) }}" class="category-card">
                    @if (!empty($category->image_path))
                        <img src="{{ asset($category->image_path) }}" alt="{{ $category->name }}">
                    @else
                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $category->name }}">
                    @endif
                    <h3>{{ $category->name }}</h3>
                </a>
            @empty
                <a href="#" class="category-card">
                    <img src="{{ asset('images/default-product.jpg') }}" alt="Vestidos">
                    <h3>Vestidos</h3>
                </a>
                <a href="#" class="category-card">
                    <img src="{{ asset('images/default-product.jpg') }}" alt="Conjuntos">
                    <h3>Conjuntos</h3>
                </a>
            @endforelse
        </div>
    </section>

    <section class="featured-products">
        <div class="section-header">
            <h2 class="section-title">Productos destacados</h2>
            <a href="{{ url('/tienda/productos') }}" class="view-all">Ver todos</a>
        </div>

        <div class="products-grid">
            @forelse ($products as $product)
                @php
                    $hasDiscount = $product->compare_price && $product->compare_price > $product->price;
                    $discount = $hasDiscount
                        ? round((($product->compare_price - $product->price) / $product->compare_price) * 100)
                        : null;
                @endphp
                <article class="product-card" data-product-id="{{ $product->id }}">
                    @if ($product->is_featured)
                        <div class="product-badge">Destacado</div>
                    @elseif ($hasDiscount && $discount)
                        <div class="product-badge sale">{{ $discount }}% OFF</div>
                    @endif

                    <button class="wishlist-btn" aria-label="Añadir a favoritos" data-product-id="{{ $product->id }}">
                        <i class="far fa-heart"></i>
                    </button>

                    <a href="{{ url('/producto/' . $product->slug) }}" class="product-image loading">
                        <img src="{{ asset($product->main_image ?? 'images/default-product.jpg') }}" alt="{{ $product->name }}">
                    </a>

                    <div class="product-info">
                        <span class="product-category">{{ $product->category_name ?? 'Sin categoría' }}</span>
                        <h3 class="product-title">
                            <a href="{{ url('/producto/' . $product->slug) }}">{{ $product->name }}</a>
                        </h3>

                        <div class="product-rating">
                            <div class="stars">
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="rating-count">(0)</span>
                        </div>

                        <div class="product-price">
                            <span class="current-price">${{ number_format($product->price, 0, ',', '.') }}</span>
                            @if ($hasDiscount)
                                <span class="original-price">${{ number_format($product->compare_price, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <a href="{{ url('/producto/' . $product->slug) }}" class="view-product-btn">
                            <i class="fas fa-eye"></i> Ver producto
                        </a>
                    </div>
                </article>
            @empty
                @for ($i = 0; $i < 6; $i++)
                    <div class="product-card shimmer">
                        <div class="shimmer-wishlist"></div>
                        <div class="shimmer-image"></div>
                        <div class="shimmer-info">
                            <div class="shimmer-category"></div>
                            <div class="shimmer-title"></div>
                            <div class="shimmer-title"></div>
                            <div class="shimmer-rating"></div>
                            <div class="shimmer-price"></div>
                            <div class="shimmer-button"></div>
                        </div>
                    </div>
                @endfor
            @endforelse
        </div>
    </section>

    @if ($promoBanner)
        <!-- Use only solid color for promo banner (no background-image) -->
        <section class="promo-banner">
                <div class="promo-content">
                @if (!empty($promoBanner->icon))
                    <i class="fas {{ $promoBanner->icon }} fa-3x"></i>
                @endif
                <h2>{{ $promoBanner->title }}</h2>
                @if (!empty($promoBanner->subtitle))
                    <p>{{ $promoBanner->subtitle }}</p>
                @endif
                @if (!empty($promoBanner->button_text) && !empty($promoBanner->button_link))
                    <a href="{{ $promoBanner->button_link }}" class="btn">{{ $promoBanner->button_text }}</a>
                @endif
            </div>
        </section>
    @endif

    <section class="featured-collections">
        <h2 class="section-title">Nuestras colecciones</h2>
        <div class="collections-grid">
            @forelse ($collections as $collection)
                <a href="{{ url('/tienda/productos?collection=' . $collection->id) }}" class="collection-card">
                    @if (!empty($collection->image_path))
                        <img src="{{ asset($collection->image_path) }}" alt="{{ $collection->name }}">
                    @else
                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $collection->name }}">
                    @endif
                    <div class="collection-overlay">
                        <h3>{{ $collection->name }}</h3>
                        @if (!empty($collection->description))
                            <p>{{ \Illuminate\Support\Str::limit($collection->description, 120) }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <a href="#" class="collection-card">
                    <img src="{{ asset('images/default-product.jpg') }}" alt="Colección Playa">
                    <div class="collection-overlay">
                        <h3>Colección Playa</h3>
                        <p>Prendas frescas para los días soleados.</p>
                    </div>
                </a>
            @endforelse
        </div>
    </section>
@endsection
