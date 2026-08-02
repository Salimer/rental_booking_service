<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use LocalizedModelTrait;

    protected $table = 'amenities';

    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'status',
    ];

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'unit_amenity');
    }
}
