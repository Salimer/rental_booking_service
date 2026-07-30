<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availabilities';

    protected $fillable = [
        'unit_id',
        'date',
        'available_quantity',
        'override_price',
        'is_blocked',
        'block_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'available_quantity' => 'integer',
        'override_price' => 'decimal:2',
        'is_blocked' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
