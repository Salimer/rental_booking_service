<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use LocalizedModelTrait;

    protected $table = 'cities';

    protected $fillable = [
        'country_id',
        'name_ar',
        'name_en',
        'status',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'city_id');
    }

    public function neighborhoods()
    {
        return $this->hasMany(Neighborhood::class, 'city_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
