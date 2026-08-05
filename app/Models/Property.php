<?php
// app/Models/Property.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
    'title',
    'type',
    'description',
    'price',
    'address',
    'bedrooms',
    'bathrooms',
    'surface',
    'status',
    'images',
    'latitude',   
    'longitude',  
];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'surface' => 'integer',
    ];
    
    // Accessor pour obtenir la première image
    public function getMainImageAttribute()
    {
        $images = is_string($this->images) ? json_decode($this->images, true) : $this->images;
        return $images[0] ?? null;
    }

    // Accessor pour obtenir toutes les images
    public function getImagesArrayAttribute()
    {
        return is_string($this->images) ? json_decode($this->images, true) : ($this->images ?? []);
    }
}