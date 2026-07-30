<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Group duplicates by trimmed, lowercase Arabic name
        // (Arabic name is required for all amenities in the system)
        $duplicates = DB::table('amenities')
            ->select(DB::raw('LOWER(TRIM(name_ar)) as clean_name_ar, MIN(id) as canonical_id, GROUP_CONCAT(id) as all_ids'))
            ->groupBy(DB::raw('LOWER(TRIM(name_ar))'))
            ->having(DB::raw('COUNT(id)'), '>', 1)
            ->get();

        foreach ($duplicates as $group) {
            $canonicalId = $group->canonical_id;
            $allIds = explode(',', $group->all_ids);
            $duplicateIds = array_diff($allIds, [$canonicalId]);

            foreach ($duplicateIds as $duplicateId) {
                // Find all units attached to the duplicate amenity record
                $unitIds = DB::table('unit_amenity')
                    ->where('amenity_id', $duplicateId)
                    ->pluck('unit_id');

                foreach ($unitIds as $unitId) {
                    // Check if unit is already attached to the canonical ID
                    $exists = DB::table('unit_amenity')
                        ->where('unit_id', $unitId)
                        ->where('amenity_id', $canonicalId)
                        ->exists();

                    if (! $exists) {
                        // Re-map relationship to canonical ID
                        DB::table('unit_amenity')
                            ->where('unit_id', $unitId)
                            ->where('amenity_id', $duplicateId)
                            ->update(['amenity_id' => $canonicalId]);
                    } else {
                        // Relieve redundancy by deleting the duplicate relation
                        DB::table('unit_amenity')
                            ->where('unit_id', $unitId)
                            ->where('amenity_id', $duplicateId)
                            ->delete();
                    }
                }

                // Delete the duplicate amenity record
                DB::table('amenities')->where('id', $duplicateId)->delete();
            }
        }
    }

    public function down()
    {
        // This migration is destructive (merges duplicates and deletes extra records).
        // Since merging duplicates is a one-way cleanup of dirty database entries,
        // it cannot be cleanly undone. We leave down() empty.
    }
};
