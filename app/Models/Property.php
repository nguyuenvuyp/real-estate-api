<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'property_type', 'status', 'price', 'area',
        'bedrooms', 'bathrooms', 'floors', 'address', 'city', 'district',
        'postal_code', 'latitude', 'longitude', 'year_built', 'features',
        'images', 'contact_name', 'contact_phone', 'contact_email',
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
    ];
    
    public function images()
    {
    return $this->hasMany(PropertyImage::class);
    }
}
