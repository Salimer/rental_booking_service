<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    use LocalizedModelTrait;

    protected $table = 'neighborhoods';

    protected $fillable = [
        'city_id',
        'name_ar',
        'name_en',
        'status',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'neighborhood_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
