@extends('layouts.public')

@section('title', 'All Properties - Asalya Investment')

@section('content')
<div class="pt-32 pb-16">
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">All Properties</h1>

        @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('properties.create') }}"
               class="inline-flex items-center bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-3 rounded-lg shadow-md transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Property
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if($properties->isEmpty())
        <div class="text-center mt-20">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-xl font-medium mt-4">No properties available yet.</p>
            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('properties.create') }}" class="inline-block mt-6 bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                    Create your first property
                </a>
            @endif
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($properties as $property)
                @php
                    $images = is_array($property->images) ? $property->images : (json_decode($property->images, true) ?? []);
                    $mainImage = $images[0] ?? null;
                    $imageCount = is_array($images) ? count($images) : 0;
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 transform hover:-translate-y-1">

                    <div class="relative h-56 bg-gray-200 dark:bg-gray-700 overflow-hidden group">
                        @if($mainImage)
                            @if(filter_var($mainImage, FILTER_VALIDATE_URL))
                                <img src="{{ $mainImage }}"
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <img src="{{ asset('storage/' . $mainImage) }}"
                                     alt="{{ $property->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @endif

                            @if($imageCount > 1)
                                <div class="absolute top-3 right-3 bg-black/70 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $imageCount }}
                                </div>
                            @endif

                            <div class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold text-white backdrop-blur-sm
                                {{ $property->status === 'available' ? 'bg-green-500/90' : '' }}
                                {{ $property->status === 'sold' ? 'bg-red-500/90' : '' }}
                                {{ $property->status === 'rented' ? 'bg-blue-500/90' : '' }}">
                                {{ ucfirst($property->status ?? 'available') }}
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-sky-400 to-blue-600">
                                <svg class="w-20 h-20 text-white/70" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <div class="p-6">
                        @if($property->type)
                            <span class="inline-block px-3 py-1 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 text-xs font-semibold rounded-full mb-3">
                                {{ $property->type }}
                            </span>
                        @endif

                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2 line-clamp-1">
                            {{ $property->title }}
                        </h2>

                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                            {{ $property->description ? Str::limit($property->description, 100, '...') : 'No description available' }}
                        </p>

                        @if($property->bedrooms || $property->bathrooms || $property->surface)
                            <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                @if($property->bedrooms)
                                    <div class="flex items-center">
                                        <i class="fas fa-bed mr-1 text-sky-500"></i>
                                        {{ $property->bedrooms }} bd
                                    </div>
                                @endif

                                @if($property->bathrooms)
                                    <div class="flex items-center">
                                        <i class="fas fa-bath mr-1 text-sky-500"></i>
                                        {{ $property->bathrooms }} ba
                                    </div>
                                @endif

                                @if($property->surface)
                                    <div class="flex items-center">
                                        <i class="fas fa-ruler-combined mr-1 text-sky-500"></i>
                                        {{ number_format($property->surface, 0) }} m²
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="flex items-center text-gray-600 dark:text-gray-400 text-sm mb-4">
                            <i class="fas fa-map-marker-alt mr-2 flex-shrink-0 text-sky-500"></i>
                            <span class="line-clamp-1">{{ $property->address ?? 'Address not specified' }}</span>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Price</p>
                                <p class="text-2xl font-bold text-sky-600 dark:text-sky-400">
                                    {{ number_format($property->price, 0, ',', ' ') }} €
                                </p>
                            </div>

                            <a href="{{ route('properties.show', $property) }}"
                               class="inline-flex items-center bg-sky-500 hover:bg-sky-600 text-white font-semibold px-4 py-2 rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                View
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('properties.edit', $property) }}"
                                   class="flex-1 text-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg transition">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>

                                <form action="{{ route('properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Delete this property and all its images?')" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition">
                                        <i class="fas fa-trash mr-1"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($properties->hasPages())
        <div class="mt-12">
            {{ $properties->links() }}
        </div>
        @endif
    @endif
</div>
</div>
@endsection
