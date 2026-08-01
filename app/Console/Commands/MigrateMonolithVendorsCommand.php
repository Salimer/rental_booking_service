<?php

namespace App\Console\Commands;

use App\Models\DashboardUser;
use App\Models\Org;
use App\Models\OrgStaff;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class MigrateMonolithVendorsCommand extends Command
{
    protected $signature = 'rental:migrate-vendors';

    protected $description = 'Migrate vendors and staff from monolith to rental microservice dashboard_users';

    public function handle()
    {
        $this->info('Starting migration of monolith vendors to rental microservice...');

        $monolithUrl = rtrim(config('services.monolith.url', env('MONOLITH_URL', 'http://localhost:8000')), '/');
        $secret = config('services.monolith.secret', env('INTERNAL_API_SECRET', 'jac_rental_internal_secret_key_2026'));

        $this->info("Fetching vendor data from {$monolithUrl}/api/v1/internal/rental/export-vendors...");

        try {
            $response = Http::withHeaders(['X-Internal-Secret' => $secret])
                ->timeout(15)
                ->get("{$monolithUrl}/api/v1/internal/rental/export-vendors");

            if (!$response->successful()) {
                $this->error("Failed to fetch vendors from monolith. Status: {$response->status()}");
                return 1;
            }

            $data = $response->json();
            $vendors = $data['vendors'] ?? [];
            $staffList = $data['staff'] ?? [];

            $this->info("Received " . count($vendors) . " vendors and " . count($staffList) . " staff records.");

            $migratedVendors = 0;
            $vendorMap = [];

            foreach ($vendors as $v) {
                $vendorId = $v['monolith_vendor_id'];
                $email = strtolower(trim($v['email'] ?? "vendor_{$vendorId}@jac-ye.com"));
                $name = trim(($v['f_name'] ?? '') . ' ' . ($v['l_name'] ?? ''));

                if (empty($name)) {
                    $name = "المالك {$vendorId}";
                }

                $user = DashboardUser::where('monolith_vendor_id', $vendorId)
                    ->orWhere('email', $email)
                    ->first();

                if (!$user) {
                    $user = DashboardUser::create([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $v['phone'] ?? null,
                        'password' => Hash::make("changeme_{$vendorId}"),
                        'role' => 'owner',
                        'permissions' => array_fill_keys(array_keys(DashboardUser::ALL_PERMISSIONS), true),
                        'status' => (bool) ($v['status'] ?? true),
                        'monolith_vendor_id' => $vendorId,
                    ]);
                    $migratedVendors++;
                }

                $vendorMap[$vendorId] = $user;
            }

            $this->info("Migrated/verified {$migratedVendors} vendor accounts.");

            // Link Orgs to DashboardUsers
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

            $orgs = Org::all();
            $linkedOrgs = 0;
            $placeholderCount = 0;

            foreach ($orgs as $org) {
                if ($org->dashboard_user_id) {
                    $linkedOrgs++;
                    continue;
                }

                if ($org->vendor_id && isset($vendorMap[$org->vendor_id])) {
                    $user = $vendorMap[$org->vendor_id];
                    $org->update(['dashboard_user_id' => $user->id]);
                    if (!$user->org_id) {
                        $user->update(['org_id' => $org->id]);
                    }
                    $linkedOrgs++;
                } else {
                    // Create placeholder vendor user for orphaned org
                    $placeholderEmail = "owner_org_{$org->id}@jac-ye.com";
                    $placeholderUser = DashboardUser::firstOrCreate(
                        ['email' => $placeholderEmail],
                        [
                            'org_id' => $org->id,
                            'name' => "مالك " . ($org->name_ar ?? "المنظمة {$org->id}"),
                            'phone' => $org->contact_phone ?? null,
                            'password' => Hash::make("changeme_org_{$org->id}"),
                            'role' => 'owner',
                            'permissions' => array_fill_keys(array_keys(DashboardUser::ALL_PERMISSIONS), true),
                            'status' => true,
                        ]
                    );

                    $org->update(['dashboard_user_id' => $placeholderUser->id]);
                    $placeholderCount++;
                }
            }

            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

            $this->info("Linked {$linkedOrgs} orgs to dashboard users ({$placeholderCount} placeholders created).");

            // Migrate staff records
            $migratedStaff = 0;
            foreach ($staffList as $s) {
                $staffEmail = strtolower(trim($s['email'] ?? ''));
                if (empty($staffEmail)) {
                    continue;
                }

                $staffUser = DashboardUser::where('email', $staffEmail)->first();

                if (!$staffUser) {
                    $staffUser = DashboardUser::create([
                        'org_id' => $s['rental_org_id'] ?? null,
                        'name' => trim(($s['f_name'] ?? '') . ' ' . ($s['l_name'] ?? '')),
                        'email' => $staffEmail,
                        'phone' => $s['phone'] ?? null,
                        'password' => Hash::make("changeme_staff_" . ($s['monolith_employee_id'] ?? rand(1000, 9999))),
                        'role' => 'receptionist',
                        'permissions' => [
                            'view_bookings' => true,
                            'confirm_checkin' => true,
                            'confirm_checkout' => true,
                        ],
                        'status' => (bool) ($s['status'] ?? true),
                        'monolith_vendor_id' => $s['monolith_vendor_id'] ?? null,
                        'monolith_employee_id' => $s['monolith_employee_id'] ?? null,
                    ]);
                    $migratedStaff++;
                }
            }

            $this->info("Migrated {$migratedStaff} staff accounts.");
            $this->info("Migration completed successfully!");

            return 0;

        } catch (\Exception $e) {
            $this->error("Error during migration: " . $e->getMessage());
            return 1;
        }
    }
}
