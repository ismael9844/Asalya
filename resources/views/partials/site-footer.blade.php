<footer class="bg-gray-900 dark:bg-black text-white py-12" id="footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Asalya Investment Logo" class="h-10 w-auto">
                </div>
                <p class="text-gray-400 mb-4" data-en="Your trusted partner in finding the perfect property." data-tr="Mükemmel mülkü bulmak için güvenilir ortağınız.">Your trusted partner in finding the perfect property.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4" data-en="Quick Links" data-tr="Hızlı Bağlantılar">Quick Links</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('landing') }}" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Home" data-tr="Ana Sayfa">Home</a></li>
                    <li><a href="{{ route('landing') }}#properties" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Properties" data-tr="Mülkler">Properties</a></li>
                    <li><a href="{{ route('landing') }}#why-choose" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="About Us" data-tr="Hakkımızda">About Us</a></li>
                    <li><a href="{{ route('landing') }}#footer" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Contact" data-tr="İletişim">Contact</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4" data-en="Property Types" data-tr="Mülk Tipleri">Property Types</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('landing') }}#properties" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Apartments" data-tr="Daireler">Apartments</a></li>
                    <li><a href="{{ route('landing') }}#properties" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Houses" data-tr="Evler">Houses</a></li>
                    <li><a href="{{ route('landing') }}#properties" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Villas" data-tr="Villalar">Villas</a></li>
                    <li><a href="{{ route('landing') }}#properties" class="text-gray-400 hover:text-sky-400 transition-colors" data-en="Land" data-tr="Arsa">Land</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4" data-en="Contact Us" data-tr="İletişim">Contact Us</h3>
                <ul class="space-y-3 text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-sky-500"></i>
                        <span>Lapta, Cyprus</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-sky-500"></i>
                        <a href="mailto:info@asalya.com" class="hover:text-sky-400 transition-colors">info@asalya.com</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone mr-3 text-sky-500"></i>
                        <a href="tel:+905338301901" class="hover:text-sky-400 transition-colors">+90 533 830 19 01</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm">© {{ date('Y') }} Asalya Investment. <span data-en="All rights reserved." data-tr="Tüm hakları saklıdır.">All rights reserved.</span></p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="text-gray-400 hover:text-sky-400 text-sm transition-colors" data-en="Privacy Policy" data-tr="Gizlilik Politikası">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-sky-400 text-sm transition-colors" data-en="Terms of Service" data-tr="Hizmet Şartları">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<button id="scroll-top" class="fixed bottom-8 right-8 w-12 h-12 bg-sky-500 hover:bg-sky-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all opacity-0 pointer-events-none z-50">
    <i class="fas fa-arrow-up"></i>
</button>
