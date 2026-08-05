@extends('layouts.public')

@section('title', 'Add Property - Asalya Investment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-900 pt-40 pb-12 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('properties.index') }}" 
               class="inline-flex items-center text-sky-600 hover:text-sky-800 font-semibold mb-4 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Properties
            </a>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Add New Property</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Fill in the details below to list a new property</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-sm">
                <div class="flex items-start">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Basic Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Basic Information
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Property Title *</label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title') }}"
                               placeholder="e.g., Luxury Villa with Sea View"
                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition" 
                               required>
                        @error('title')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" 
                                  rows="5"
                                  placeholder="Describe the property, its features, and what makes it special..."
                                  class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Price (€) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-lg">€</span>
                            <input type="number" 
                                   name="price" 
                                   value="{{ old('price') }}"
                                   step="0.01" 
                                   min="0"
                                   placeholder="250000"
                                   class="w-full border-2 border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition" 
                                   required>
                        </div>
                        @error('price')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition">
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                        </select>
                        @error('status')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                     <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Type</label>
                        <select name="type" class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition">
                            <option value="Flat" {{ old('status') == 'Flat' ? 'selected' : '' }}>Apartment</option>
                            <option value="House" {{ old('status') == 'House' ? 'selected' : '' }}>House</option>
                            <option value="Land" {{ old('status') == 'Land' ? 'selected' : '' }}>Land</option>
                        </select>
                        @error('type')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

<div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Property Images</label>
    
    <!-- Drop & Upload Zone -->
    <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-sky-500 transition-colors cursor-pointer bg-gray-50">
        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <p class="mt-2 text-sm text-gray-600">
            <span class="font-semibold text-sky-600">Click to upload</span> or drag and drop
        </p>
        <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP up to 5MB each</p>
        <input type="file" 
               id="images-input"
               name="images[]" 
               multiple
               accept="image/*"
               class="hidden">
    </div>
    
    <!-- Preview Grid -->
    <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
    
    <p class="text-sm text-gray-500 mt-2">
        <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        You can upload multiple images. The first image will be used as the main property image.
    </p>
    
    @error('images')
        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
    @enderror
    @error('images.*')
        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
    @enderror
</div>

<style>
    .image-preview-item {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .image-preview-item:hover {
        border-color: #0ea5e9;
        transform: scale(1.02);
    }
    
    .image-preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .image-preview-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: rgba(14, 165, 233, 0.9);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .image-preview-remove {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .image-preview-remove:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
    
    .image-preview-filename {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px;
        font-size: 0.75rem;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    #drop-zone.drag-over {
        border-color: #0ea5e9;
        background-color: #e0f2fe;
    }
</style>

<script>
    // Variables
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('images-input');
    const previewContainer = document.getElementById('preview-container');
    let selectedFiles = [];

    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    function handleFiles(files) {
        const imageFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
        
        if (imageFiles.length === 0) {
            alert('Please select only image files (PNG, JPG, WEBP)');
            return;
        }

        const validFiles = imageFiles.filter(file => {
            if (file.size > 5 * 1024 * 1024) {
                alert(`${file.name} is too large. Maximum size is 5MB.`);
                return false;
            }
            return true;
        });

        selectedFiles = [...selectedFiles, ...validFiles];
        updateFileInput();
        displayPreviews();
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        fileInput.files = dataTransfer.files;
    }

    function displayPreviews() {
        previewContainer.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'image-preview-item';
                
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}">
                    ${index === 0 ? '<div class="image-preview-badge">Main Image</div>' : ''}
                    <div class="image-preview-remove" data-index="${index}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="image-preview-filename">${file.name}</div>
                `;
                
                previewContainer.appendChild(previewItem);
            };
            
            reader.readAsDataURL(file);
        });
        
        setTimeout(() => {
            document.querySelectorAll('.image-preview-remove').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const index = parseInt(btn.dataset.index);
                    removeImage(index);
                });
            });
        }, 100);
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        displayPreviews();
    }

    document.addEventListener('dragover', (e) => {
        e.preventDefault();
    });
    
    document.addEventListener('drop', (e) => {
        e.preventDefault();
    });
</script>
                </div>
            </div>

            <!-- Property Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Property Details
                </h2>

                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bedrooms</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-xl">🛏️</span>
                            <input type="number" 
                                   name="bedrooms" 
                                   value="{{ old('bedrooms') }}"
                                   min="0"
                                   placeholder="3"
                                   class="w-full border-2 border-gray-300 rounded-lg pl-12 pr-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition">
                        </div>
                        @error('bedrooms')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bathrooms</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-xl">🚿</span>
                            <input type="number" 
                                   name="bathrooms" 
                                   value="{{ old('bathrooms') }}"
                                   min="0"
                                   placeholder="2"
                                   class="w-full border-2 border-gray-300 rounded-lg pl-12 pr-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition">
                        </div>
                        @error('bathrooms')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Surface (m²)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-xl">📏</span>
                            <input type="number" 
                                   name="surface" 
                                   value="{{ old('surface') }}"
                                   min="0"
                                   placeholder="150"
                                   class="w-full border-2 border-gray-300 rounded-lg pl-12 pr-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition">
                        </div>
                        @error('surface')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-sky-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Location
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <input type="text" 
                               id="address-input"
                               name="address" 
                               value="{{ old('address') }}"
                               placeholder="Enter full address or click on the map"
                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition">
                        @error('address')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Coordinates -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Latitude</label>
                            <input type="number" 
                                   id="latitude"
                                   name="latitude" 
                                   value="{{ old('latitude') }}"
                                   step="0.000001"
                                   placeholder="36.8121"
                                   class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                                   readonly>
                            @error('latitude')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Longitude</label>
                            <input type="number" 
                                   id="longitude"
                                   name="longitude" 
                                   value="{{ old('longitude') }}"
                                   step="0.000001"
                                   placeholder="34.6415"
                                   class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition"
                                   readonly>
                            @error('longitude')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Interactive Map -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Pin Location on Map
                            <span class="text-gray-500 font-normal text-xs ml-2">(Click on the map to set property location)</span>
                        </label>
                        <div id="map" class="w-full h-96 rounded-xl border-4 border-gray-200 shadow-inner"></div>
                        <p class="text-sm text-gray-600 mt-2">
                            💡 <strong>Tip:</strong> Search for an address above or click directly on the map to pinpoint the exact location
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('properties.index') }}" 
                   class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg shadow-md transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-lg shadow-md transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Create Property
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize map centered on Northern Cyprus
    const map = L.map('map').setView([35.3417, 33.3197], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Custom marker icon (sky theme, consistent with the rest of the site)
    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background-color: #0ea5e9; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);"><div style="width: 10px; height: 10px; background: white; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(45deg);"></div></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30]
    });

    let marker = null;

    const initialLat = document.getElementById('latitude').value;
    const initialLng = document.getElementById('longitude').value;
    
    if (initialLat && initialLng) {
        const lat = parseFloat(initialLat);
        const lng = parseFloat(initialLng);
        marker = L.marker([lat, lng], { icon: customIcon, draggable: true }).addTo(map);
        map.setView([lat, lng], 15);
        
        marker.on('dragend', function(e) {
            updateCoordinates(e.target.getLatLng());
        });
    }

    map.on('click', function(e) {
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng, { icon: customIcon, draggable: true }).addTo(map);
            
            marker.on('dragend', function(e) {
                updateCoordinates(e.target.getLatLng());
            });
        }
        updateCoordinates(e.latlng);
        reverseGeocode(e.latlng);
    });

    function updateCoordinates(latlng) {
        document.getElementById('latitude').value = latlng.lat.toFixed(6);
        document.getElementById('longitude').value = latlng.lng.toFixed(6);
    }

    function reverseGeocode(latlng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('address-input').value = data.display_name;
                }
            })
            .catch(error => console.log('Geocoding error:', error));
    }

    const addressInput = document.getElementById('address-input');
    let searchTimeout;
    
    addressInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const address = this.value;
        
        if (address.length > 3) {
            searchTimeout = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const lat = parseFloat(data[0].lat);
                            const lon = parseFloat(data[0].lon);
                            
                            if (marker) {
                                marker.setLatLng([lat, lon]);
                            } else {
                                marker = L.marker([lat, lon], { icon: customIcon, draggable: true }).addTo(map);
                                
                                marker.on('dragend', function(e) {
                                    updateCoordinates(e.target.getLatLng());
                                });
                            }
                            
                            map.setView([lat, lon], 15);
                            updateCoordinates({ lat, lng: lon });
                        }
                    })
                    .catch(error => console.log('Search error:', error));
            }, 1000);
        }
    });
</script>
@endsection
