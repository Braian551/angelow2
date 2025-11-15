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
                        <h2>{{ $slider->title ?? 'Descubre la nueva colección' }}</h2>
                        @if (!empty($slider->subtitle))
                            <p>{{ $slider->subtitle }}</p>
                        @endif
                        @if (!empty($slider->button_link) && !empty($slider->button_text))
                            <a href="{{ $slider->button_link }}" class="btn">{{ $slider->button_text }}</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="hero-slide active">
                    <img src="{{ asset('images/legacy/default-hero.jpg') }}" alt="Colección Angelow">
                    <div class="hero-content">
                        <h2>Moda infantil premium</h2>
                        <p>Prendas cómodas y seguras para los más pequeños</p>
                        <a href="{{ url('/tienda/productos') }}" class="btn">Explorar colección</a>
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
                <a href="{{ url('/tienda/productos?category=' . $category->slug) }}" class="category-card">
                    <div class="category-content">
                        <h3>{{ $category->name }}</h3>
                        <p>Descubre lo mejor en {{ strtolower($category->name) }}</p>
                    </div>
                </a>
            @empty
                <a href="#" class="category-card">
                    <div class="category-content">
                        <h3>Recién nacidos</h3>
                        <p>Prendas suaves y cómodas para el primer año</p>
                    </div>
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
                <article class="product-card" data-product-id="{{ $product->id }}">
                    @if ($product->is_featured)
                        <div class="product-badge">Destacado</div>
                    @endif

                    <a href="{{ url('/producto/' . $product->slug) }}" class="product-image">
                        <img src="{{ asset($product->main_image ?? 'images/legacy/default-product.jpg') }}" alt="{{ $product->name }}" loading="lazy">
                    </a>

                    <div class="product-info">
                        <span class="product-category">{{ $product->category_name ?? 'Colección' }}</span>
                        <h3 class="product-title">
                            <a href="{{ url('/producto/' . $product->slug) }}">{{ $product->name }}</a>
                        </h3>
                        <div class="product-price">
                            <span class="current-price">${{ number_format($product->price, 0, ',', '.') }}</span>
                            @if ($product->compare_price && $product->compare_price > $product->price)
                                <span class="original-price">${{ number_format($product->compare_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="product-card shimmer"></div>
            @endforelse
        </div>
    </section>

    @if ($promoBanner)
        @php
            $promoClasses = 'promo-banner' . (!empty($promoBanner->image) ? ' has-image' : '');
            $promoStyle = !empty($promoBanner->image)
                ? '--promo-bg-image: url(' . asset($promoBanner->image) . ');'
                : null;
            $promoStyleAttr = $promoStyle ? 'style="' . e($promoStyle) . '"' : '';
        @endphp
        <section class="{{ $promoClasses }}" {!! $promoStyleAttr !!}>
            @if (!empty($promoBanner->image))
                <div class="promo-image"></div>
            @endif
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
                <article class="collection-card">
                    <div class="collection-content">
                        <h3>{{ $collection->name }}</h3>
                        @if (!empty($collection->description))
                            <p>{{ \Illuminate\Support\Str::limit($collection->description, 120) }}</p>
                        @endif
                        <a href="{{ url('/colecciones/' . $collection->slug) }}" class="btn-outline">Ver colección</a>
                    </div>
                </article>
            @empty
                <article class="collection-card">
                    <div class="collection-content">
                        <h3>Colección Primavera</h3>
                        <p>Prendas ligeras y coloridas para la nueva temporada.</p>
                        <a href="{{ url('/colecciones') }}" class="btn-outline">Ver colección</a>
                    </div>
                </article>
            @endforelse
        </div>
    </section>
@endsection
