<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualLock extends Model
{
    protected $table = 'manual_locks';

    protected $fillable = [
        'unit_id',
        'start_date',
        'end_date',
        'reason',
        'created_by_vendor_id',
        'created_by_admin',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_by_admin' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
