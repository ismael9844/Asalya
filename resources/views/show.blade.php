<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $property->title }} - Asalya Investment</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        body.dark-mode {
            background-color: #0f172a;
            color: #f1f5f9;
        }

        body.dark-mode .bg-white {
            background-color: #1e293b !important;
        }

        body.dark-mode .bg-gray-50 {
            background-color: #1a2332 !important;
        }

        body.dark-mode .text-gray-800 {
            color: #e2e8f0 !important;
        }

        body.dark-mode .text-gray-600 {
            color: #94a3b8 !important;
        }

        body.dark-mode .border-gray-200,
        body.dark-mode .border-gray-100 {
            border-color: #334155 !important;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        body.dark-mode .glass-effect {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #property-map {
            height: 400px;
            width: 100%;
            border-radius: 16px;
            z-index: 1;
        }

        /* Image Gallery */
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

        /* Gallery Navigation Arrows */
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

        .gallery-arrow:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .gallery-arrow.left {
            left: 16px;
        }

        .gallery-arrow.right {
            right: 16px;
        }

        /* Sticky Contact Card */
        .sticky-card {
            position: sticky;
            top: 100px;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">

    <!-- Header -->
    <header class="glass-effect fixed top-0 left-0 right-0 z-50 transition-all duration-300 mx-4 mt-4 rounded-2xl" id="header">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="/" class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Asalya Investment Logo" class="h-12 w-auto">
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-600 dark:text-gray-300 hover:text-sky-500 font-medium transition-colors">
                        <span data-en="Home" data-tr="Ana Sayfa">Home</span>
                    </a>
                    <a href="/#properties" class="text-gray-600 dark:text-gray-300 hover:text-sky-500 font-medium transition-colors">
                        <span data-en="Properties" data-tr="Mülkler">Properties</span>
                    </a>
                    <a href="/about" class="text-gray-600 dark:text-gray-300 hover:text-sky-500 font-medium transition-colors">
                        <span data-en="About" data-tr="Hakkımızda">About</span>
                    </a>
                    <a href="/contact" class="text-gray-600 dark:text-gray-300 hover:text-sky-500 font-medium transition-colors">
                        <span data-en="Contact" data-tr="İletişim">Contact</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button id="dark-mode-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-moon text-gray-600 dark:text-gray-300"></i>
                    </button>
                    
                    <button id="lang-toggle" class="flex items-center space-x-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        <span id="current-lang" class="font-semibold text-gray-800 dark:text-white">🇬🇧 EN</span>
                    </button>
                    
                    <button id="menu-toggle" class="md:hidden text-gray-600 dark:text-gray-300 hover:text-sky-500">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="pt-32 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            
            <!-- Breadcrumb -->
            <nav class="mb-8 text-sm fade-in">
                <ol class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                    <li><a href="/" class="hover:text-sky-500" data-en="Home" data-tr="Ana Sayfa">Home</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li><a href="/#properties" class="hover:text-sky-500" data-en="Properties" data-tr="Mülkler">Properties</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li class="text-gray-800 dark:text-white font-medium">{{ $property->title }}</li>
                </ol>
            </nav>

            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Image Gallery -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden fade-in">
                        @php
                            $images = [];
                            if ($property->image) {
                                $images[] = asset('storage/' . $property->image);
                            }
                            // Si vous avez un champ images JSON, décommentez:
                            // if ($property->images) {
                            //     $additionalImages = json_decode($property->images, true);
                            //     if (is_array($additionalImages)) {
                            //         $images = array_merge($images, $additionalImages);
                            //     }
                            // }
                        @endphp

                        <div class="gallery-main">
                            @if(count($images) > 0)
                                <img src="{{ $images[0] }}" alt="{{ $property->title }}" id="main-image">
                                
                                @if(count($images) > 1)
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

                        @if(count($images) > 1)
                        <div class="p-4">
                            <div class="gallery-thumbs">
                                @foreach($images as $index => $img)
                                <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" onclick="changeImage({{ $index }})">
                                    <img src="{{ $img }}" alt="Thumbnail {{ $index + 1 }}">
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

                        <!-- Property Features -->
                        @if($property->bedrooms || $property->bathrooms || $property->area)
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

                            @if($property->area)
                            <div class="text-center">
                                <i class="fas fa-ruler-combined text-3xl text-sky-500 mb-2"></i>
                                <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($property->area) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">m²</div>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Description -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4" data-en="Description" data-tr="Açıklama">Description</h2>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ $property->description ?? 'No description available.' }}
                            </p>
                        </div>

                        <!-- Amenities (si vous avez ce champ) -->
                        @if(isset($property->amenities) && !empty($property->amenities))
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4" data-en="Amenities" data-tr="Olanaklar">Amenities</h2>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach(json_decode($property->amenities, true) as $amenity)
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $amenity }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Map Section -->
                    @if($property->latitude && $property->longitude)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 fade-in">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6" data-en="Location" data-tr="Konum">Location</h2>
                        <div id="property-map" class="mb-4"></div>
                        
                        <!-- Google Maps Directions Button -->
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $property->latitude }},{{ $property->longitude }}" 
                           target="_blank"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all">
                            <i class="fas fa-directions mr-2"></i>
                            <span data-en="Get Directions on Google Maps" data-tr="Google Maps'te Yol Tarifi Al">Get Directions on Google Maps</span>
                        </a>
                    </div>
                    @endif

                </div>

                <!-- Right Column - Contact Card (Sticky) -->
                <div class="lg:col-span-1">
                    <div class="sticky-card">
                        
                        <!-- Contact Card -->
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

                            <!-- WhatsApp Button -->
                            <a href="https://wa.me/1234567890?text=Merhaba,%20{{ urlencode($property->title) }}%20hakkında%20bilgi%20almak%20istiyorum.%20Fiyat:%20€{{ number_format($property->price, 0, ',', '.') }}" 
                               target="_blank"
                               class="w-full flex items-center justify-center px-6 py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold text-lg mb-3 transition-all hover:shadow-lg">
                                <i class="fab fa-whatsapp text-2xl mr-3"></i>
                                <span data-en="Contact on WhatsApp" data-tr="WhatsApp'tan İletişime Geç">Contact on WhatsApp</span>
                            </a>

                            <!-- Call Button -->
                            <a href="tel:+1234567890" 
                               class="w-full flex items-center justify-center px-6 py-4 bg-sky-500 hover:bg-sky-600 text-white rounded-xl font-bold text-lg mb-3 transition-all hover:shadow-lg">
                                <i class="fas fa-phone text-xl mr-3"></i>
                                <span data-en="Call Now" data-tr="Şimdi Ara">Call Now</span>
                            </a>

                            <!-- Email Button -->
                            <a href="mailto:info@asalya.com?subject={{ urlencode('Inquiry: ' . $property->title) }}&body={{ urlencode('I am interested in this property: ' . url()->current()) }}" 
                               class="w-full flex items-center justify-center px-6 py-4 border-2 border-sky-500 text-sky-500 hover:bg-sky-50 dark:hover:bg-sky-900/20 rounded-xl font-bold text-lg transition-all">
                                <i class="fas fa-envelope text-xl mr-3"></i>
                                <span data-en="Send Email" data-tr="E-posta Gönder">Send Email</span>
                            </a>
                        </div>

                        <!-- Share Card -->
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
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 dark:bg-black text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Asalya Investment Logo" class="h-10 w-auto">
                    </div>
                    <p class="text-gray-400 mb-4" data-en="Your trusted partner in finding the perfect property." data-tr="Mükemmel mülkü bulmak için güvenilir ortağınız.">Your trusted partner in finding the perfect property.</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4" data-en="Quick Links" data-tr="Hızlı Bağlantılar">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="/" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Home" data-tr="Ana Sayfa">Home</a></li>
                        <li><a href="/#properties" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Properties" data-tr="Mülkler">Properties</a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="About Us" data-tr="Hakkımızda">About Us</a></li>
                        <li><a href="/contact" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Contact" data-tr="İletişim">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4" data-en="Property Types" data-tr="Mülk Tipleri">Property Types</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Apartments" data-tr="Daireler">Apartments</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Houses" data-tr="Evler">Houses</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Villas" data-tr="Villalar">Villas</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Land" data-tr="Arsa">Land</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-4" data-en="Contact Us" data-tr="İletişim">Contact Us</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-sky-500"></i>
                            <span>Mersin, Turkey</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-sky-500"></i>
                            <a href="mailto:info@asalya.com" class="hover:text-sky-400 transition-colors">info@asalya.com</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-sky-500"></i>
                            <a href="tel:+1234567890" class="hover:text-sky-400 transition-colors">+1 234 567 890</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">© 2025 Asalya Investment. <span data-en="All rights reserved." data-tr="Tüm hakları saklıdır.">All rights reserved.</span></p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-sky-400 text-sm transition-colors" data-en="Privacy Policy" data-tr="Gizlilik Politikası">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-sky-400 text-sm transition-colors" data-en="Terms of Service" data-tr="Hizmet Şartları">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Images Gallery
        const images = @json($images ?? []);
        let currentImageIndex = 0;

        function changeImage(index) {
            currentImageIndex = index;
            document.getElementById('main-image').src = images[index];
            
            // Update active thumb
            document.querySelectorAll('.gallery-thumb').forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            changeImage(currentImageIndex);
        }

        function previousImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            changeImage(currentImageIndex);
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') previousImage();
        });

        // Dark Mode
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const body = document.body;
        const darkModeIcon = darkModeToggle.querySelector('i');
        
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            darkModeIcon.classList.replace('fa-moon', 'fa-sun');
        }
        
        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            
            if (body.classList.contains('dark-mode')) {
                darkModeIcon.classList.replace('fa-moon', 'fa-sun');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                darkModeIcon.classList.replace('fa-sun', 'fa-moon');
                localStorage.setItem('darkMode', 'disabled');
            }
        });

        // Language Toggle
        let currentLang = 'en';
        const langToggle = document.getElementById('lang-toggle');
        const currentLangSpan = document.getElementById('current-lang');
        
        function translatePage() {
            document.querySelectorAll('[data-en][data-tr]').forEach(el => {
                el.textContent = el.getAttribute(`data-${currentLang}`);
            });
        }
        
        langToggle.addEventListener('click', () => {
            currentLang = currentLang === 'en' ? 'tr' : 'en';
            currentLangSpan.textContent = currentLang === 'en' ? '🇬🇧 EN' : '🇹🇷 TR';
            translatePage();
        });

        // Copy Link Function
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert(currentLang === 'en' ? 'Link copied to clipboard!' : 'Bağlantı panoya kopyalandı!');
            });
        }

        @if($property->latitude && $property->longitude)
        // Initialize Map
        const propertyMap = L.map('property-map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(propertyMap);

        // Custom marker
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
</body>
</html>