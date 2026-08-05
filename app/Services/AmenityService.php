<?php

namespace App\Services;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Collection;

class AmenityService
{
    public function getAmenities(?int $typeId = null): Collection
    {
        $query = Amenity::whereHas('units', function ($q) use ($typeId) {
            $q->where('status', 'active')->whereHas('property', function ($pq) use ($typeId) {
                $pq->where('status', 'active');
                if ($typeId) {
                    $pq->where('type_id', $typeId);
                }
            });
        });

        return $query->get();
    }
}
