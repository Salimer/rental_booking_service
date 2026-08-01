<?php

namespace App\Services;

use App\Models\Type;
use Illuminate\Database\Eloquent\Collection;

class TypeService
{
    public function getActiveTypes(): Collection
    {
        return Type::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
