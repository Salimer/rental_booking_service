<?php

namespace App\Services;

use App\Models\Type;
use Illuminate\Database\Eloquent\Collection;

class TypeService
{
    public function getActiveTypes(): Collection
    {
        return Type::where('status', 1)
            ->whereHas('properties', function ($q) {
                $q->where('status', 'active')->whereHas('units', function ($u) {
                    $u->where('status', 'active');
                });
            })
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
