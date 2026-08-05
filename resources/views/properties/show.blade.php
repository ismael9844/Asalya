@extends('layouts.public')

@section('title', $property->title . ' - Asalya Investment')

@section('head')
<style>
    #property-map {
        height: 400px;
        width: 100%;
        border-radius: 16px;
        z-index: 1;
    }

    .gallery-main {
        height: 500px;
        overflow: hidden;
        border-radius: 16px;
        position: relative;
    }

    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .gallery-thumb {
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.6;
        transition: all 0.3s ease;
        border: 3px solid transparent;
    }

    .gallery-thumb:hover,
    .gallery-thumb.active {
        opacity: 1;
        border-color: #0ea5e9;
    }

    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
    }

    .gallery-arrow:hover { background: white; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
    .gallery-arrow.left { left: 16px; }
    .gallery-arrow.right { right: 16px; }

    .sticky-card { position: sticky; top: 100px; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in { animation: fadeIn 0.6s ease-out; }
</style>
@endsection

@section('content')
<div class="pt-32 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <!-- Breadcrumb -->
        <nav class="mb-8 text-sm fade-in">
            <ol class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                <li><a href="{{ route('landing') }}" class="hover:text-sky-500" data-en="Home" data-tr="Ana Sayfa">Home</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('landing') }}#properties" class="hover:text-sky-500" data-en="Properties" data-tr="Mülkler">Properties</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-gray-800 dark:text-white font-medium">{{ $property->title }}</li>
            </ol>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">

            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Image Gallery -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden fade-in">
                    @php
                        $images = is_array($property->images) ? $property->images : (json_decode($property->images, true) ?? []);
                        $imageCount = count($images);
                    @endphp

                    <div class="gallery-main">
                        @if($imageCount > 0)
                            @if(filter_var($images[0], FILTER_VALIDATE_URL))
                                <img src="{{ $images[0] }}" alt="{{ $property->title }}" id="main-image">
                            @else
                                <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $property->title }}" id="main-image">
                            @endif

                            @if($imageCount > 1)
                                <div class="gallery-arrow left" onclick="previousImage()">
                                    <i class="fas fa-chevron-left text-gray-700"></i>
                                </div>
                                <div class="gallery-arrow right" onclick="nextImage()">
                                    <i class="fas fa-chevron-right text-gray-700"></i>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-home text-white text-8xl opacity-50"></i>
                            </div>
                        @endif
                    </div>

                    @if($imageCount > 1)
                    <div class="p-4">
                        <div class="gallery-thumbs">
                            @foreach($images as $index => $img)
                            <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" onclick="changeImage({{ $index }})">
                                @if(filter_var($img, FILTER_VALIDATE_URL))
                                    <img src="{{ $img }}" alt="Thumbnail {{ $index + 1 }}">
                                @else
                                    <img src="{{ asset('storage/' . $img) }}" alt="Thumbnail {{ $index + 1 }}">
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Property Details -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 fade-in">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="px-4 py-1.5 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 text-sm font-semibold rounded-full">
                                    {{ $property->type ?? 'Property' }}
                                </span>
                                <span class="px-4 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-sm font-semibold rounded-full">
                                    {{ ucfirst($property->status ?? 'Available') }}
                                </span>
                                @if(Auth::check() && Auth::user()->role === 'admin')
                                <a href="{{ route('properties.edit', $property) }}" class="px-4 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-full transition-colors">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @endif
                            </div>
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white">
                                {{ $property->title }}
                            </h1>
                        </div>
                    </div>

                    <div class="flex items-center text-gray-600 dark:text-gray-400 mb-6">
                        <i class="fas fa-map-marker-alt text-sky-500 mr-2"></i>
                        <span>{{ $property->address ?? 'Address not specified' }}</span>
                    </div>

                    <div class="text-4xl font-bold text-sky-600 dark:text-sky-400 mb-8">
                        €{{ number_format($property->price, 0, ',', '.') }}
                    </div>

                    @if($property->bedrooms || $property->bathrooms || $property->surface)
                    <div class="grid grid-cols-3 gap-6 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl mb-8">
                        @if($property->bedrooms)
                        <div class="text-center">
                            <i class="fas fa-bed text-3xl text-sky-500 mb-2"></i>
                            <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $property->bedrooms }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400" data-en="Bedrooms" data-tr="Yatak Odası">Bedrooms</div>
                        </div>
                        @endif

                        @if($property->bathrooms)
                        <div class="text-center">
                            <i class="fas fa-bath text-3xl text-sky-500 mb-2"></i>
                            <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $property->bathrooms }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400" data-en="Bathrooms" data-tr="Banyo">Bathrooms</div>
                        </div>
                        @endif

                        @if($property->surface)
                        <div class="text-center">
                            <i class="fas fa-ruler-combined text-3xl text-sky-500 mb-2"></i>
                            <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($property->surface) }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">m²</div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4" data-en="Description" data-tr="Açıklama">Description</h2>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ $property->description ?? 'No description available.' }}
                        </p>
                    </div>
                </div>

                <!-- Map -->
                @if($property->latitude && $property->longitude)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 fade-in">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6" data-en="Location" data-tr="Konum">Location</h2>
                    <div id="property-map" class="mb-4"></div>

                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}"
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all">
                        <i class="fas fa-directions mr-2"></i>
                        <span data-en="Get Directions on Google Maps" data-tr="Google Maps'te Yol Tarifi Al">Get Directions on Google Maps</span>
                    </a>
                </div>
                @endif

            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1">
                <div class="sticky-card">

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 fade-in">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6" data-en="Contact Agent" data-tr="Temsilci ile İletişime Geç">Contact Agent</h3>

                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-sky-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-4">
                                AI
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 dark:text-white">Asalya Investment</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400" data-en="Real Estate Agent" data-tr="Emlak Danışmanı">Real Estate Agent</div>
                            </div>
                        </div>

                        <a href="https://wa.me/905338301901?text=Merhaba,%20{{ urlencode($property->title) }}%20hakkında%20bilgi%20almak%20istiyorum.%20Fiyat:%20€{{ number_format($property->price, 0, ',', '.') }}"
                           target="_blank"
                           class="w-full flex items-center justify-center px-6 py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold text-lg mb-3 transition-all hover:shadow-lg">
                            <i class="fab fa-whatsapp text-2xl mr-3"></i>
                            <span data-en="Contact on WhatsApp" data-tr="WhatsApp'tan İletişime Geç">Contact on WhatsApp</span>
                        </a>

                        <a href="tel:+905338301901"
                           class="w-full flex items-center justify-center px-6 py-4 bg-sky-500 hover:bg-sky-600 text-white rounded-xl font-bold text-lg mb-3 transition-all hover:shadow-lg">
                            <i class="fas fa-phone text-xl mr-3"></i>
                            <span data-en="Call Now" data-tr="Şimdi Ara">Call Now</span>
                        </a>

                        <a href="mailto:info@asalya.com?subject={{ urlencode('Inquiry: ' . $property->title) }}&body={{ urlencode('I am interested in this property: ' . url()->current()) }}"
                           class="w-full flex items-center justify-center px-6 py-4 border-2 border-sky-500 text-sky-500 hover:bg-sky-50 dark:hover:bg-sky-900/20 rounded-xl font-bold text-lg transition-all">
                            <i class="fas fa-envelope text-xl mr-3"></i>
                            <span data-en="Send Email" data-tr="E-posta Gönder">Send Email</span>
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 fade-in">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4" data-en="Share Property" data-tr="Mülkü Paylaş">Share Property</h3>

                        <div class="flex space-x-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                               target="_blank"
                               class="flex-1 flex items-center justify-center py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all">
                                <i class="fab fa-facebook-f"></i>
                            </a>

                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($property->title) }}"
                               target="_blank"
                               class="flex-1 flex items-center justify-center py-3 bg-sky-400 hover:bg-sky-500 text-white rounded-lg transition-all">
                                <i class="fab fa-twitter"></i>
                            </a>

                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}"
                               target="_blank"
                               class="flex-1 flex items-center justify-center py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-all">
                                <i class="fab fa-linkedin-in"></i>
                            </a>

                            <button onclick="copyLink()"
                                    class="flex-1 flex items-center justify-center py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-all">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
const images = @json($images ?? []);
let currentImageIndex = 0;

function changeImage(index) {
    currentImageIndex = index;
    const imgElement = document.getElementById('main-image');
    imgElement.src = (images[index].startsWith('http://') || images[index].startsWith('https://'))
        ? images[index]
        : "{{ asset('storage') }}/" + images[index];

    document.querySelectorAll('.gallery-thumb').forEach((thumb, i) => {
        thumb.classList.toggle('active', i === index);
    });
}

function nextImage() {
    if (images.length > 0) {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        changeImage(currentImageIndex);
    }
}

function previousImage() {
    if (images.length > 0) {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        changeImage(currentImageIndex);
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') previousImage();
});

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert(window.currentLang === 'en' ? 'Link copied to clipboard!' : 'Bağlantı panoya kopyalandı!');
    });
}

@if($property->latitude && $property->longitude)
const propertyMap = L.map('property-map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(propertyMap);

const customIcon = L.divIcon({
    className: 'custom-marker',
    html: `<div style="background: #0ea5e9; width: 40px; height: 40px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); display: flex; align-items: center; justify-content: center; border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
            <i class="fas fa-home" style="color: white; transform: rotate(45deg); font-size: 18px;"></i>
           </div>`,
    iconSize: [40, 40],
    iconAnchor: [20, 40]
});

const marker = L.marker([{{ $property->latitude }}, {{ $property->longitude }}], { icon: customIcon }).addTo(propertyMap);

marker.bindPopup(`
    <div style="text-align: center; padding: 8px;">
        <strong style="font-size: 16px;">{{ $property->title }}</strong><br>
        <span style="color: #64748b; font-size: 13px;">{{ $property->address }}</span>
    </div>
`).openPopup();
@endif
</script>
@endsection
