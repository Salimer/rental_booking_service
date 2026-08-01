<?php

namespace App\Services;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Collection;

class AmenityService
{
    public function getAmenities(?int $typeId = null): Collection
    {
        $query = Amenity::query();

        if ($typeId) {
            $query->whereHas('units', function ($q) use ($typeId) {
                $q->whereHas('property', function ($pq) use ($typeId) {
                    $pq->where('type_id', $typeId);
                });
            });
        }

        return $query->get();
    }
}
