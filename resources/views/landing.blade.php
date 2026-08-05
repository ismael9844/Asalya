@extends('layouts.public')

@section('title', 'Asalya Investment - Find Your Dream Home')

@section('head')
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 16px;
        z-index: 1;
    }

    .hero-gradient {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.3) 0%, rgba(2, 132, 199, 0.3) 50%, rgba(3, 105, 161, 0.3) 100%);
        position: relative;
        overflow: hidden;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-50px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    .slide-in-left { animation: slideInLeft 0.8s ease-out forwards; }

    .property-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
    }

    body.dark-mode .property-card {
        background: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .property-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    body.dark-mode .property-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    }

    .property-card .image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .property-card:hover .image-overlay { opacity: 1; }

    .search-input {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        color: #1e293b;
    }

    .search-input:focus {
        background: rgba(255, 255, 255, 1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .search-input::placeholder { color: #64748b; }
    body.dark-mode .search-input { background: rgba(30, 41, 59, 0.9); color: white; }
    body.dark-mode .search-input::placeholder { color: rgba(255, 255, 255, 0.5); }
    .search-input option { background: white; color: #1e293b; }
    body.dark-mode .search-input option { background: #1e293b; color: white; }

    .stat-number { font-variant-numeric: tabular-nums; }

    .filter-pill { transition: all 0.3s ease; cursor: pointer; }
    .filter-pill:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3); }
    .filter-pill.active { background: #0ea5e9 !important; color: white !important; }

    @media (max-width: 768px) { #map { height: 400px; } }
</style>
@endsection

@section('content')

<!-- Hero Section -->
<section class="hero-gradient text-white pt-32 pb-20 md:pb-32 relative overflow-hidden min-h-screen flex items-center" id="hero">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/herp-backgrounddd.jpg') }}" alt="Real Estate Background" class="w-full h-full object-cover opacity-90">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center relative z-10">
        <div class="fade-in-up">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight drop-shadow-2xl">
                <span data-en="Find Your Dream Home" data-tr="Hayalinizdeki Evi Bulun">Find Your Dream Home</span>
            </h1>
            <p class="text-lg md:text-2xl mb-12 text-blue-50 max-w-3xl mx-auto drop-shadow-lg">
                <span data-en="Discover the finest properties in the best locations worldwide" data-tr="Dünya çapında en iyi konumlardaki en güzel mülkleri keşfedin">
                    Discover the finest properties in the best locations worldwide
                </span>
            </p>
        </div>

        <form id="search-form" class="max-w-5xl mx-auto glass-effect rounded-2xl p-6 mb-12 fade-in-up" style="animation-delay: 0.2s;">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                    <input type="text" name="search" id="search-location"
                           data-en-placeholder="Location, city, or keyword..."
                           data-tr-placeholder="Konum, şehir veya anahtar kelime..."
                           placeholder="Location, city, or keyword..."
                           class="w-full pl-12 pr-4 py-4 search-input border-0 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-400">
                </div>

                <div class="relative">
                    <i class="fas fa-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                    <select name="property_type" id="search-type" class="w-full pl-12 pr-4 py-4 search-input border-0 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-400 appearance-none">
                        <option value="" data-en="Property Type" data-tr="Mülk Tipi">Property Type</option>
                        <option value="flat" data-en="Apartment" data-tr="Daire">Apartment</option>
                        <option value="house" data-en="House" data-tr="Ev">House</option>
                        <option value="villa" data-en="Villa" data-tr="Villa">Villa</option>
                        <option value="land" data-en="Land" data-tr="Arsa">Land</option>
                    </select>
                </div>

                <div class="relative">
                    <i class="fas fa-dollar-sign absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                    <select name="price_range" id="search-price" class="w-full pl-12 pr-4 py-4 search-input border-0 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-400 appearance-none">
                        <option value="" data-en="Price Range" data-tr="Fiyat Aralığı">Price Range</option>
                        <option value="0-100000">€0 - €100,000</option>
                        <option value="100000-250000">€100,000 - €250,000</option>
                        <option value="250000-500000">€250,000 - €500,000</option>
                        <option value="500000-1000000">€500,000 - €1M</option>
                        <option value="1000000+">€1M+</option>
                    </select>
                </div>

                <div class="relative">
                    <i class="fas fa-bed absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none z-10"></i>
                    <select name="bedrooms" id="search-bedrooms" class="w-full pl-12 pr-4 py-4 search-input border-0 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-400 appearance-none">
                        <option value="" data-en="Bedrooms" data-tr="Yatak Odası">Bedrooms</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                        <option value="5">5+</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full md:w-auto bg-white text-sky-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-50 transition-all hover:shadow-2xl transform hover:-translate-y-1">
                <i class="fas fa-search mr-2"></i>
                <span data-en="Search Properties" data-tr="Mülk Ara">Search Properties</span>
            </button>
        </form>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto fade-in-up" style="animation-delay: 0.4s;">
            <div class="glass-effect rounded-xl px-6 py-5 text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold stat-number">{{ $properties->count() }}</div>
                <div class="text-sm text-blue-100 mt-1" data-en="Properties" data-tr="Mülk">Properties</div>
            </div>
            <div class="glass-effect rounded-xl px-6 py-5 text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold stat-number">500+</div>
                <div class="text-sm text-blue-100 mt-1" data-en="Happy Clients" data-tr="Mutlu Müşteri">Happy Clients</div>
            </div>
            <div class="glass-effect rounded-xl px-6 py-5 text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold stat-number">50+</div>
                <div class="text-sm text-blue-100 mt-1" data-en="Cities" data-tr="Şehir">Cities</div>
            </div>
            <div class="glass-effect rounded-xl px-6 py-5 text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold stat-number">15+</div>
                <div class="text-sm text-blue-100 mt-1" data-en="Years Exp." data-tr="Yıl Deneyim">Years Exp.</div>
            </div>
        </div>
    </div>
</section>

<!-- Filter Pills -->
<section class="py-8 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex flex-wrap gap-3 justify-center">
            <button class="filter-pill active px-6 py-2 bg-sky-500 text-white rounded-full font-medium" data-filter="">
                <i class="fas fa-fire mr-2"></i><span data-en="All" data-tr="Tümü">All</span>
            </button>
            <button class="filter-pill px-6 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full font-medium" data-filter="flat">
                <i class="fas fa-building mr-2"></i><span data-en="Apartments" data-tr="Daireler">Apartments</span>
            </button>
            <button class="filter-pill px-6 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full font-medium" data-filter="house">
                <i class="fas fa-home mr-2"></i><span data-en="Houses" data-tr="Evler">Houses</span>
            </button>
            <button class="filter-pill px-6 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full font-medium" data-filter="land">
                <i class="fas fa-mountain mr-2"></i><span data-en="Lands" data-tr="Arsalar">Lands</span>
            </button>
        </div>
    </div>
</section>

<!-- Properties Section -->
<section class="py-16 bg-gray-50 dark:bg-gray-900" id="properties">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-2">
                    <span data-en="Featured Properties" data-tr="Öne Çıkan Mülkler">Featured Properties</span>
                </h2>
                <p class="text-gray-600 dark:text-gray-400" data-en="Handpicked properties just for you" data-tr="Sizin için özenle seçilmiş mülkler">Handpicked properties just for you</p>
            </div>

            <div class="flex items-center space-x-3 mt-4 md:mt-0">
                <span class="text-sm text-gray-600 dark:text-gray-400" data-en="Sort by:" data-tr="Sırala:">Sort by:</span>
                <select name="sort" id="sort-select" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-400">
                    <option value="" data-en="Recommended" data-tr="Önerilen">Recommended</option>
                    <option value="price_asc" data-en="Price: Low to High" data-tr="Fiyat: Düşükten Yükseğe">Price: Low to High</option>
                    <option value="price_desc" data-en="Price: High to Low" data-tr="Fiyat: Yüksekten Düşüğe">Price: High to Low</option>
                    <option value="date_new" data-en="Newest First" data-tr="En Yeni">Newest First</option>
                </select>
            </div>
        </div>

        <div id="properties-container">
        @if($properties->isEmpty())
        <div class="text-center py-20 fade-in-up">
            <div class="w-32 h-32 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-home text-6xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2" data-en="No Properties Found" data-tr="Mülk Bulunamadı">No Properties Found</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6" data-en="Try adjusting your search or filters" data-tr="Arama veya filtreleri ayarlamayı deneyin">Try adjusting your search or filters</p>
        </div>
        @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($properties as $property)
            @php
                $images = [];
                if ($property->images) {
                    $decoded = is_string($property->images) ? json_decode($property->images, true) : $property->images;
                    if (is_array($decoded)) {
                        $images = $decoded;
                    }
                }
                $mainImage = $images[0] ?? null;
                $imageCount = count($images);
            @endphp

            <div class="property-card rounded-2xl shadow-lg overflow-hidden fade-in-up"
                 style="animation-delay: {{ $loop->index * 0.1 }}s;"
                 data-property-type="{{ strtolower($property->type ?? '') }}">

                <div class="relative h-64 overflow-hidden">
                    @if($mainImage)
                        @if(filter_var($mainImage, FILTER_VALIDATE_URL))
                            <img src="{{ $mainImage }}"
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        @else
                            <img src="{{ asset('storage/' . $mainImage) }}"
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        @endif

                        @if($imageCount > 1)
                        <div class="absolute bottom-4 right-4 bg-black/70 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center">
                            <i class="fas fa-images mr-1"></i>
                            {{ $imageCount }}
                        </div>
                        @endif
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-home text-white text-6xl opacity-50"></i>
                        </div>
                    @endif

                    <div class="image-overlay"></div>

                    <div class="absolute top-4 left-4 glass-effect px-4 py-2 rounded-full text-sm font-bold text-white backdrop-blur-md">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ ucfirst($property->status ?? 'Available') }}
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 text-xs font-semibold rounded-full">
                            {{ $property->type ?? 'Property' }}
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 line-clamp-1">
                        {{ $property->title }}
                    </h3>

                    <div class="flex items-center text-gray-500 dark:text-gray-400 mb-3">
                        <i class="fas fa-map-marker-alt mr-2 text-sky-500"></i>
                        <span class="text-sm line-clamp-1">{{ $property->address ?? 'Location not specified' }}</span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                        {{ Str::limit($property->description, 100) }}
                    </p>

                    @if($property->bedrooms || $property->bathrooms || $property->surface)
                    <div class="flex items-center justify-between py-3 border-t border-b border-gray-100 dark:border-gray-700 mb-4">
                        @if($property->bedrooms)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <i class="fas fa-bed text-sky-500 mr-2"></i>
                            <span class="text-sm font-medium">{{ $property->bedrooms }}</span>
                        </div>
                        @endif

                        @if($property->bathrooms)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <i class="fas fa-bath text-sky-500 mr-2"></i>
                            <span class="text-sm font-medium">{{ $property->bathrooms }}</span>
                        </div>
                        @endif

                        @if($property->surface)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <i class="fas fa-ruler-combined text-sky-500 mr-2"></i>
                            <span class="text-sm font-medium">{{ number_format($property->surface) }} m²</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-1" data-en="Starting from" data-tr="Başlangıç">Starting from</div>
                            <div class="text-2xl font-bold text-sky-600 dark:text-sky-400">
                                €{{ number_format($property->price, 0, ',', '.') }}
                            </div>
                        </div>
                        <a href="{{ route('properties.show', $property) }}" class="bg-sky-500 hover:bg-sky-600 text-white px-6 py-3 rounded-lg font-semibold transition-all hover:shadow-lg transform hover:-translate-y-1">
                            <span data-en="View" data-tr="Görüntüle">View</span>
                        </a>
                    </div>

                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('properties.edit', $property) }}" class="flex-1 text-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-edit mr-1"></i><span data-en="Edit" data-tr="Düzenle">Edit</span>
                        </a>
                        <form action="{{ route('properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Delete this property?')" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-trash mr-1"></i><span data-en="Delete" data-tr="Sil">Delete</span>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-16 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12 slide-in-left">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4">
                <span data-en="Explore Properties on Map" data-tr="Haritada Mülkleri Keşfedin">Explore Properties on Map</span>
            </h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" data-en="Interactive map view with all available properties. Click on markers to view details." data-tr="Tüm mevcut mülklerin interaktif harita görünümü. Detayları görmek için işaretlere tıklayın.">
                Interactive map view with all available properties. Click on markers to view details.
            </p>
        </div>

        <div class="relative">
            <div id="map" class="shadow-2xl"></div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-gray-50 dark:bg-gray-900" id="why-choose">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-4" data-en="Why Choose Asalya Investment" data-tr="Neden Asalya Investment">Why Choose Asalya Investment</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" data-en="We provide comprehensive real estate services with a commitment to excellence" data-tr="Mükemmelliğe bağlı kapsamlı gayrimenkul hizmetleri sunuyoruz">
                We provide comprehensive real estate services with a commitment to excellence
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow fade-in-up">
                <div class="w-16 h-16 bg-sky-100 dark:bg-sky-900/30 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-shield-alt text-3xl text-sky-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3" data-en="Trusted & Secure" data-tr="Güvenilir ve Güvenli">Trusted & Secure</h3>
                <p class="text-gray-600 dark:text-gray-400" data-en="All transactions are secured with bank-level encryption and verified listings." data-tr="Tüm işlemler banka düzeyinde şifreleme ve doğrulanmış listelerle güvence altındadır.">
                    All transactions are secured with bank-level encryption and verified listings.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow fade-in-up" style="animation-delay: 0.1s;">
                <div class="w-16 h-16 bg-sky-100 dark:bg-sky-900/30 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-headset text-3xl text-sky-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3" data-en="24/7 Support" data-tr="7/24 Destek">24/7 Support</h3>
                <p class="text-gray-600 dark:text-gray-400" data-en="Our dedicated team is available round the clock to assist you with any queries." data-tr="Özel ekibimiz her türlü sorunuzda size yardımcı olmak için 7/24 hizmetinizdedir.">
                    Our dedicated team is available round the clock to assist you with any queries.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow fade-in-up" style="animation-delay: 0.2s;">
                <div class="w-16 h-16 bg-sky-100 dark:bg-sky-900/30 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-3xl text-sky-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3" data-en="Best Prices" data-tr="En İyi Fiyatlar">Best Prices</h3>
                <p class="text-gray-600 dark:text-gray-400" data-en="Competitive pricing with transparent fees and no hidden charges." data-tr="Şeffaf ücretler ve gizli ücret olmadan rekabetçi fiyatlandırma.">
                    Competitive pricing with transparent fees and no hidden charges.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-16 bg-gradient-to-r from-sky-500 to-blue-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" data-en="Stay Updated" data-tr="Güncel Kalın">Stay Updated</h2>
            <p class="text-lg text-blue-100 mb-8" data-en="Subscribe to our newsletter and get the latest property listings directly in your inbox" data-tr="Bültenimize abone olun ve en son mülk ilanlarını doğrudan gelen kutunuzda alın">
                Subscribe to our newsletter and get the latest property listings directly in your inbox
            </p>

            <form class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
                <input type="email" data-en-placeholder="Enter your email address" data-tr-placeholder="E-posta adresinizi girin" placeholder="Enter your email address"
                       class="flex-1 px-6 py-4 rounded-lg text-gray-800 focus:outline-none focus:ring-4 focus:ring-white/50">
                <button type="submit" class="bg-white text-sky-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition-all hover:shadow-lg">
                    <span data-en="Subscribe" data-tr="Abone Ol">Subscribe</span>
                </button>
            </form>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
let allProperties = @json($properties);
let currentFilter = '';

// Filter Pills
document.querySelectorAll('.filter-pill').forEach(pill => {
    pill.addEventListener('click', function() {
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        currentFilter = this.getAttribute('data-filter');
        filterByType();
    });
});

function filterByType() {
    document.querySelectorAll('.property-card').forEach(card => {
        const propertyType = card.getAttribute('data-property-type');
        card.style.display = (!currentFilter || propertyType === currentFilter) ? 'block' : 'none';
    });
}

// Search Form
document.getElementById('search-form').addEventListener('submit', (e) => {
    e.preventDefault();
    filterProperties();
    document.getElementById('properties').scrollIntoView({ behavior: 'smooth', block: 'start' });
});

function filterProperties() {
    const searchTerm = document.getElementById('search-location').value.toLowerCase();
    const propertyType = document.getElementById('search-type').value;
    const priceRange = document.getElementById('search-price').value;
    const bedrooms = document.getElementById('search-bedrooms').value;

    document.querySelectorAll('.property-card').forEach(card => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        const address = card.querySelector('.fa-map-marker-alt').parentElement.textContent.toLowerCase();
        const cardType = card.getAttribute('data-property-type');

        const priceText = card.querySelector('.text-2xl.font-bold').textContent.replace(/[^0-9]/g, '');
        const propertyPrice = parseInt(priceText);

        const bedroomsElement = card.querySelector('.fa-bed');
        let propertyBedrooms = 0;
        if (bedroomsElement) {
            const bedroomsText = bedroomsElement.parentElement.textContent.match(/\d+/);
            propertyBedrooms = bedroomsText ? parseInt(bedroomsText[0]) : 0;
        }

        const matchesSearch = !searchTerm || title.includes(searchTerm) || address.includes(searchTerm);
        const matchesType = !propertyType || cardType === propertyType;

        let matchesPrice = true;
        if (priceRange) {
            if (priceRange === '1000000+') {
                matchesPrice = propertyPrice >= 1000000;
            } else {
                const [min, max] = priceRange.split('-').map(Number);
                matchesPrice = propertyPrice >= min && propertyPrice <= max;
            }
        }

        const matchesBedrooms = !bedrooms || propertyBedrooms >= parseInt(bedrooms);

        card.style.display = (matchesSearch && matchesType && matchesPrice && matchesBedrooms) ? 'block' : 'none';
    });
}

// Sort Select
document.getElementById('sort-select').addEventListener('change', function() {
    const sortValue = this.value;
    const container = document.querySelector('#properties-container .grid');
    if (!container) return;

    const cards = Array.from(container.querySelectorAll('.property-card'));

    cards.sort((a, b) => {
        const priceA = parseInt(a.querySelector('.text-2xl.font-bold').textContent.replace(/[^0-9]/g, ''));
        const priceB = parseInt(b.querySelector('.text-2xl.font-bold').textContent.replace(/[^0-9]/g, ''));

        if (sortValue === 'price_asc') return priceA - priceB;
        if (sortValue === 'price_desc') return priceB - priceA;

        if (sortValue === 'date_new') {
            const idA = parseInt(a.querySelector('a[href*="/properties/"]').getAttribute('href').split('/').pop());
            const idB = parseInt(b.querySelector('a[href*="/properties/"]').getAttribute('href').split('/').pop());
            return idB - idA;
        }

        return 0;
    });

    cards.forEach(card => container.appendChild(card));
});

// Smooth scroll for in-page anchors
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#hero' && href.length > 1) {
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
});

// Initialize Leaflet Map
const map = L.map('map').setView([36.8121, 34.6415], 12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

if (allProperties.length > 0) {
    const bounds = [];

    allProperties.forEach(property => {
        if (property.latitude && property.longitude) {
            const lat = parseFloat(property.latitude);
            const lng = parseFloat(property.longitude);
            bounds.push([lat, lng]);

            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div style="background: #0ea5e9; width: 32px; height: 32px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3); position: relative;">
                        <i class="fas fa-home" style="color: white; font-size: 14px; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%) rotate(45deg);"></i>
                       </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });

            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            let images = [];
            if (property.images) {
                try {
                    images = typeof property.images === 'string' ? JSON.parse(property.images) : property.images;
                } catch (e) {
                    images = [];
                }
            }
            const mainImage = images[0] || null;

            let imageSrc = '';
            if (mainImage) {
                imageSrc = (mainImage.startsWith('http://') || mainImage.startsWith('https://'))
                    ? mainImage
                    : `/storage/${mainImage}`;
            }

            const popupContent = `
                <div style="min-width: 250px;">
                    ${imageSrc ? `<img src="${imageSrc}" style="width: 100%; height: 140px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;">` : ''}
                    <h3 style="font-weight: bold; font-size: 16px; margin-bottom: 6px;">${property.title}</h3>
                    <p style="color: #64748b; font-size: 13px; margin-bottom: 8px;">${property.address || 'Address not specified'}</p>
                    <p style="color: #0ea5e9; font-weight: bold; font-size: 20px; margin-bottom: 10px;">€${Number(property.price).toLocaleString('de-DE')}</p>
                    <a href="/properties/${property.id}" style="display: block; text-align: center; background: #0ea5e9; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        ${window.currentLang === 'en' ? 'View Details' : 'Detayları Gör'}
                    </a>
                </div>
            `;

            marker.bindPopup(popupContent, { maxWidth: 280 });
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50] });
    }
}

// Reveal-on-scroll animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

document.querySelectorAll('.fade-in-up, .slide-in-left').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    observer.observe(el);
});
</script>
@endsection
