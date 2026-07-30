<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';

    use LocalizedModelTrait;

    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'pricing_mode_default',
        'icon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class, 'type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
