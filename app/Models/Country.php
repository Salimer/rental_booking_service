<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use LocalizedModelTrait;

    protected $table = 'countries';

    protected $fillable = [
        'name_ar',
        'name_en',
        'status',
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
