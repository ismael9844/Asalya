<header class="glass-effect fixed top-0 left-0 right-0 z-50 transition-all duration-300 mx-4 mt-4 rounded-2xl" id="header">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Asalya Investment Logo" class="h-12 w-auto rounded-xl">
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}" class="text-gray-700 dark:text-gray-300 hover:text-sky-500 font-medium transition-colors">
                    <span data-en="Home" data-tr="Ana Sayfa">Home</span>
                </a>
                <a href="{{ route('landing') }}#properties" class="text-gray-700 dark:text-gray-300 hover:text-sky-500 font-medium transition-colors">
                    <span data-en="Properties" data-tr="Mülkler">Properties</span>
                </a>
                <a href="{{ route('landing') }}#why-choose" class="text-gray-700 dark:text-gray-300 hover:text-sky-500 font-medium transition-colors">
                    <span data-en="About" data-tr="Hakkımızda">About</span>
                </a>
                <a href="{{ route('landing') }}#footer" class="text-gray-700 dark:text-gray-300 hover:text-sky-500 font-medium transition-colors">
                    <span data-en="Contact" data-tr="İletişim">Contact</span>
                </a>
            </div>

            <div class="flex items-center space-x-3">
                <button id="lang-toggle" class="flex items-center space-x-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                    <span id="current-lang" class="font-semibold text-gray-800 dark:text-white">🇬🇧 EN</span>
                </button>

                @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('properties.create') }}" class="hidden sm:inline-flex items-center bg-sky-500 hover:bg-sky-600 text-white px-5 py-2 rounded-lg font-semibold transition-all hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    <span data-en="Add Property" data-tr="Emlak Ekle">Add Property</span>
                </a>
                @endif

                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Logout">
                        <i class="fas fa-right-from-bracket text-gray-600 dark:text-gray-300"></i>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Login">
                    <i class="fas fa-right-to-bracket text-gray-600 dark:text-gray-300"></i>
                </a>
                @endauth

                <button id="menu-toggle" class="md:hidden text-gray-600 dark:text-gray-300 hover:text-sky-500">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden mt-4 space-y-3 pb-4">
            <a href="{{ route('landing') }}" class="block text-gray-600 dark:text-gray-300 hover:text-sky-500 font-medium py-2" data-en="Home" data-tr="Ana Sayfa">Home</a>
            <a href="{{ route('landing') }}#properties" class="block text-gray-600 dark:text-gray-300 hover:text-sky-500 font-medium py-2" data-en="Properties" data-tr="Mülkler">Properties</a>
            <a href="{{ route('landing') }}#why-choose" class="block text-gray-600 dark:text-gray-300 hover:text-sky-500 font-medium py-2" data-en="About" data-tr="Hakkımızda">About</a>
            <a href="{{ route('landing') }}#footer" class="block text-gray-600 dark:text-gray-300 hover:text-sky-500 font-medium py-2" data-en="Contact" data-tr="İletişim">Contact</a>
            @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('properties.create') }}" class="block text-sky-600 dark:text-sky-400 font-semibold py-2">
                <i class="fas fa-plus mr-2"></i><span data-en="Add Property" data-tr="Emlak Ekle">Add Property</span>
            </a>
            @endif
        </div>
    </nav>
</header>
