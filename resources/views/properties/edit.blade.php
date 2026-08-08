@extends('layouts.public')

@section('title', 'Edit Property - Asalya Investment')

@section('content')
@if(!Auth::check() || Auth::user()->role !== 'admin')
    @php abort(403, 'Access restricted to administrators.'); @endphp
@endif

<style>
    :root {
        --navy:       #0f172a;
        --navy-mid:   #1e293b;
        --navy-light: #334155;
        --border:     #e2e8f0;
        --surface:    #f8fafc;
        --sky:        #0ea5e9;
        --sky-dark:   #0284c7;
        --sky-glow:   rgba(14,165,233,0.15);
        --white:      #ffffff;
        --text-main:  #0f172a;
        --text-soft:  #64748b;
        --text-xsoft: #94a3b8;
        --danger:     #ef4444;
        --radius:     12px;
        --shadow-sm:  0 1px 4px rgba(15,23,42,0.08);
        --shadow-md:  0 4px 16px rgba(15,23,42,0.1);
        --shadow-lg:  0 8px 32px rgba(15,23,42,0.14);
    }

    .ep * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

    .ep {
        max-width: 820px;
        margin: 0 auto;
        padding: 150px 20px 72px;
    }

    /* Header */
    .ep-head { margin-bottom: 32px; }
    .ep-head h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--navy);
        letter-spacing: -0.03em;
        line-height: 1.2;
        margin: 0 0 6px;
    }
    .ep-head p {
        font-size: 0.875rem;
        color: var(--text-soft);
        margin: 0;
    }

    /* Section cards */
    .ep-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 26px 24px 22px;
        margin-bottom: 16px;
        box-shadow: var(--shadow-sm);
        transition: box-shadow 0.2s;
    }
    .ep-card:focus-within { box-shadow: var(--shadow-md); }

    .ep-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--sky-dark);
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--border);
    }
    .ep-card-title-icon {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, var(--sky), var(--sky-dark));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px var(--sky-glow);
    }
    .ep-card-title-icon i { color: #fff; font-size: 12px; }

    /* Fields */
    .ep-field { margin-bottom: 16px; }
    .ep-field:last-child { margin-bottom: 0; }

    .ep-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--navy-light);
        margin-bottom: 6px;
    }

    .ep-input {
        width: 100%;
        padding: 10px 13px;
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius);
        font-size: 0.875rem;
        color: var(--text-main);
        outline: none;
        transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        appearance: none;
    }
    .ep-input:focus {
        border-color: var(--sky);
        background: var(--white);
        box-shadow: 0 0 0 3px var(--sky-glow);
    }
    .ep-input::placeholder { color: var(--text-xsoft); }
    textarea.ep-input { resize: vertical; min-height: 96px; }

    .ep-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
    .ep-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    @media (max-width: 580px) {
        .ep-grid-3, .ep-grid-2 { grid-template-columns: 1fr; }
        .ep-card { padding: 18px 16px; }
    }

    /* ── Photos ── */
    .ep-dropzone {
        border: 2px dashed var(--border);
        border-radius: var(--radius);
        padding: 24px 16px;
        text-align: center;
        cursor: pointer;
        background: var(--surface);
        transition: border-color 0.2s, background 0.2s;
        position: relative;
    }
    .ep-dropzone.over {
        border-color: var(--sky);
        background: var(--sky-glow);
    }
    .ep-dropzone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .ep-dz-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, var(--sky), var(--sky-dark));
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 10px;
        box-shadow: 0 2px 10px var(--sky-glow);
    }
    .ep-dz-icon i { color: #fff; font-size: 16px; }
    .ep-dz-text  { font-size: 0.875rem; color: var(--text-soft); }
    .ep-dz-text strong { color: var(--sky); font-weight: 600; }
    .ep-dz-hint  { font-size: 0.75rem; color: var(--text-xsoft); margin-top: 3px; }

    .ep-img-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
        margin-top: 14px;
    }
    .ep-img-grid:empty { margin-top: 0; }

    .ep-thumb {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        aspect-ratio: 4/3;
        background: var(--surface);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .ep-thumb:hover { transform: scale(1.03); box-shadow: var(--shadow-md); }

    .ep-thumb img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
    }

    .ep-badge {
        position: absolute; top: 6px; left: 6px;
        font-size: 0.58rem; font-weight: 700;
        letter-spacing: 0.07em; text-transform: uppercase;
        padding: 3px 7px; border-radius: 20px;
        pointer-events: none;
    }
    .ep-badge-cover {
        background: var(--sky);
        color: #fff;
    }
    .ep-badge-new {
        background: #10b981;
        color: #fff;
    }

    .ep-remove {
        position: absolute; top: 5px; right: 5px;
        width: 24px; height: 24px;
        background: rgba(15,23,42,0.75);
        backdrop-filter: blur(4px);
        border: none; border-radius: 50%;
        color: #fff; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px;
        opacity: 0;
        transition: opacity 0.18s, background 0.18s;
        z-index: 2;
    }
    .ep-remove:hover { background: var(--danger); }
    .ep-thumb:hover .ep-remove { opacity: 1; }

    /* ── Map ── */
    #ep-map {
        height: 260px;
        border-radius: var(--radius);
        border: 1.5px solid var(--border);
        overflow: hidden;
    }
    .ep-map-tip {
        display: flex; align-items: center; gap: 7px;
        font-size: 0.78rem; color: var(--text-soft);
        margin-top: 10px;
    }
    .ep-map-tip i { color: var(--sky); }

    .ep-coords {
        display: flex; gap: 10px; margin-top: 12px;
    }
    .ep-coord {
        flex: 1;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.78rem;
        color: var(--text-soft);
        display: flex; align-items: center; gap: 6px;
    }
    .ep-coord i { color: var(--sky); font-size: 11px; }
    .ep-coord span { color: var(--navy); font-weight: 600; font-size: 0.82rem; }

    /* ── Actions ── */
    .ep-actions {
        display: flex; gap: 10px; justify-content: flex-end;
        margin-top: 8px;
    }

    .ep-btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 11px 24px; border-radius: var(--radius);
        font-size: 0.875rem; font-weight: 600;
        cursor: pointer; border: none; text-decoration: none;
        transition: all 0.18s;
    }
    .ep-btn-primary {
        background: linear-gradient(135deg, var(--sky), var(--sky-dark));
        color: #fff;
        box-shadow: 0 4px 14px var(--sky-glow);
    }
    .ep-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(14,165,233,0.35);
    }
    .ep-btn-ghost {
        background: var(--white);
        color: var(--text-soft);
        border: 1.5px solid var(--border);
    }
    .ep-btn-ghost:hover { background: var(--surface); color: var(--navy); }
</style>

<div class="ep">

    <div class="ep-head">
        <h1><i class="fas fa-pen-to-square" style="color:var(--sky);font-size:1.4rem;margin-right:10px;"></i>Edit Property</h1>
        <p>Update the fields below — all changes save on submit.</p>
    </div>

    @if ($errors->any())
        <div style="background:#fef2f2;border-left:4px solid #ef4444;color:#991b1b;padding:14px 18px;border-radius:8px;margin-bottom:16px;">
            <strong style="display:block;margin-bottom:4px;">Please fix the following:</strong>
            <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('properties.update', $property->id) }}" method="POST"
          enctype="multipart/form-data" id="ep-form">
        @csrf
        @method('PUT')

        {{-- ── 1. Basic Info ── --}}
        <div class="ep-card">
            <div class="ep-card-title">
                <div class="ep-card-title-icon"><i class="fas fa-info"></i></div>
                Basic Information
            </div>

            <div class="ep-field">
                <label class="ep-label">Title</label>
                <input type="text" name="title" class="ep-input"
                       value="{{ old('title', $property->title) }}" required
                       placeholder="e.g. Sea-view Villa in Lapta">
            </div>

            <div class="ep-grid-2">
                <div class="ep-field">
                    <label class="ep-label">Property Type</label>
                    <select name="type" class="ep-input" required>
                        @foreach(['flat' => 'Apartment', 'house' => 'House', 'villa' => 'Villa', 'land' => 'Land'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('type', $property->type) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ep-field">
                    <label class="ep-label">Status</label>
                    <select name="status" class="ep-input">
                        @foreach(['available' => 'Available', 'sold' => 'Sold', 'reserved' => 'Reserved'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', $property->status) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="ep-field">
                <label class="ep-label">Description</label>
                <textarea name="description" class="ep-input"
                          placeholder="Describe the property…">{{ old('description', $property->description) }}</textarea>
            </div>

            <div class="ep-field">
                <label class="ep-label">Address</label>
                <input type="text" name="address" class="ep-input"
                       value="{{ old('address', $property->address) }}"
                       placeholder="Street, city, country">
            </div>
        </div>

        {{-- ── 2. Details ── --}}
        <div class="ep-card">
            <div class="ep-card-title">
                <div class="ep-card-title-icon"><i class="fas fa-ruler-combined"></i></div>
                Property Details
            </div>

            <div class="ep-grid-3">
                <div class="ep-field">
                    <label class="ep-label">Price (€)</label>
                    <input type="number" step="0.01" name="price" class="ep-input"
                           value="{{ old('price', $property->price) }}" required placeholder="0">
                </div>
                <div class="ep-field">
                    <label class="ep-label">Bedrooms</label>
                    <input type="number" name="bedrooms" class="ep-input"
                           value="{{ old('bedrooms', $property->bedrooms) }}" placeholder="—">
                </div>
                <div class="ep-field">
                    <label class="ep-label">Bathrooms</label>
                    <input type="number" name="bathrooms" class="ep-input"
                           value="{{ old('bathrooms', $property->bathrooms) }}" placeholder="—">
                </div>
            </div>

            <div class="ep-field" style="max-width:260px;">
                <label class="ep-label">Surface (m²)</label>
                <input type="number" name="surface" class="ep-input"
                       value="{{ old('surface', $property->surface) }}" placeholder="—">
            </div>
        </div>

        {{-- ── 3. Photos ── --}}
        <div class="ep-card">
            <div class="ep-card-title">
                <div class="ep-card-title-icon"><i class="fas fa-images"></i></div>
                Photos
            </div>

            @php
                $existingImages = [];
                if ($property->images) {
                    $dec = is_string($property->images) ? json_decode($property->images, true) : $property->images;
                    if (is_array($dec)) $existingImages = array_values($dec);
                }
            @endphp

            {{-- Existing thumbnails --}}
            <div class="ep-img-grid" id="existing-grid">
                @foreach($existingImages as $i => $img)
                @php $url = is_array($img) ? ($img['url'] ?? '') : $img; @endphp
                <div class="ep-thumb" id="et-{{ $i }}">
                    <img src="{{ $url }}" alt="Photo {{ $i + 1 }}">
                    <span class="ep-badge ep-badge-cover" style="{{ $i > 0 ? 'display:none' : '' }}">Cover</span>
                    <button type="button" class="ep-remove" onclick="removeExisting({{ $i }})">
                        <i class="fas fa-times"></i>
                    </button>
                    {{-- Controller filters by index via keep_images[] --}}
                    <input type="hidden" name="keep_images[]" value="{{ $i }}" id="ei-{{ $i }}">
                </div>
                @endforeach
            </div>

            {{-- Drop zone --}}
            <div class="ep-dropzone" id="ep-dz" style="margin-top: {{ count($existingImages) ? '14px' : '0' }}">
                <input type="file" name="images[]" id="new-files-input"
                       accept="image/*" multiple>
                <div class="ep-dz-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="ep-dz-text"><strong>Click to upload</strong> or drag & drop</div>
                <div class="ep-dz-hint">PNG, JPG, WEBP — max 5 MB each</div>
            </div>

            {{-- New images preview --}}
            <div class="ep-img-grid" id="new-grid"></div>
        </div>

        {{-- ── 4. Location ── --}}
        <div class="ep-card">
            <div class="ep-card-title">
                <div class="ep-card-title-icon"><i class="fas fa-map-marker-alt"></i></div>
                Location
            </div>

            <div id="ep-map"></div>

            <div class="ep-map-tip">
                <i class="fas fa-mouse-pointer"></i>
                Click on the map or drag the pin to adjust the location.
            </div>

            <div class="ep-coords">
                <div class="ep-coord">
                    <i class="fas fa-arrows-alt-v"></i>
                    Lat: <span id="lat-display">{{ $property->latitude ?? '—' }}</span>
                </div>
                <div class="ep-coord">
                    <i class="fas fa-arrows-alt-h"></i>
                    Lng: <span id="lng-display">{{ $property->longitude ?? '—' }}</span>
                </div>
            </div>
        </div>

        <input type="hidden" name="latitude"  id="latitude"  value="{{ $property->latitude }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ $property->longitude }}">

        <div class="ep-actions">
            <a href="{{ url()->previous() }}" class="ep-btn ep-btn-ghost">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="ep-btn ep-btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
// ── Map ──────────────────────────────────────────────
const initLat = {{ $property->latitude ?? 36.8 }};
const initLng = {{ $property->longitude ?? 10.1 }};

const map = L.map('ep-map').setView([initLat, initLng], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
let marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

function setCoords(ll) {
    document.getElementById('latitude').value   = ll.lat.toFixed(6);
    document.getElementById('longitude').value  = ll.lng.toFixed(6);
    document.getElementById('lat-display').textContent = ll.lat.toFixed(6);
    document.getElementById('lng-display').textContent = ll.lng.toFixed(6);
}
marker.on('dragend', () => setCoords(marker.getLatLng()));
map.on('click', e => { marker.setLatLng(e.latlng); setCoords(e.latlng); });

// ── Dropzone feedback ────────────────────────────────
const dz = document.getElementById('ep-dz');
dz.addEventListener('dragover',  e => { e.preventDefault(); dz.classList.add('over'); });
dz.addEventListener('dragleave', () => dz.classList.remove('over'));
dz.addEventListener('drop',      () => dz.classList.remove('over'));

// ── Existing image removal ───────────────────────────
function removeExisting(idx) {
    const thumb = document.getElementById('et-' + idx);
    const input = document.getElementById('ei-' + idx);
    if (thumb) thumb.remove();
    if (input) input.remove();

    // Re-assign cover badge to first remaining existing thumb
    const remaining = document.querySelectorAll('#existing-grid .ep-thumb');
    remaining.forEach((el, i) => {
        const badge = el.querySelector('.ep-badge-cover');
        if (badge) badge.style.display = i === 0 ? '' : 'none';
    });
}

// ── New image upload & preview ───────────────────────
let newFiles = []; // array of File objects

document.getElementById('new-files-input').addEventListener('change', function () {
    addFiles(Array.from(this.files));
    // reset so same file can be re-added if removed
    this.value = '';
});

function addFiles(files) {
    files.forEach(file => {
        const id = 'nf-' + Date.now() + '-' + Math.random().toString(36).slice(2);
        newFiles.push({ id, file });

        const reader = new FileReader();
        reader.onload = e => {
            const thumb = document.createElement('div');
            thumb.className = 'ep-thumb';
            thumb.id = id;
            thumb.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}">
                <span class="ep-badge ep-badge-new">New</span>
                <button type="button" class="ep-remove" onclick="removeNew('${id}')">
                    <i class="fas fa-times"></i>
                </button>`;
            document.getElementById('new-grid').appendChild(thumb);
        };
        reader.readAsDataURL(file);
    });
    syncFileInput();
}

function removeNew(id) {
    newFiles = newFiles.filter(f => f.id !== id);
    const el = document.getElementById(id);
    if (el) el.remove();
    syncFileInput();
}

function syncFileInput() {
    const dt = new DataTransfer();
    newFiles.forEach(f => dt.items.add(f.file));
    document.getElementById('new-files-input').files = dt.files;
}
</script>
@endsection