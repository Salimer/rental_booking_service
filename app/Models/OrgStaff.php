<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgStaff extends Model
{
    protected $table = 'org_staff';

    protected $fillable = [
        'org_id',
        'vendor_id',
        'vendor_employee_id',
        'rental_role',
        'permissions',
        'status',
        'invited_by',
    ];

    protected $casts = [
        'status' => 'boolean',
        'permissions' => 'array',
    ];

    const ALL_PERMISSIONS = [
        'view_bookings',
        'manage_bookings',
        'confirm_checkin',
        'confirm_checkout',
        'view_financials',
        'manage_units',
        'manage_properties',
        'manage_coupons',
        'view_reports',
        'manage_staff',
    ];

    public function hasPermission(string $slug): bool
    {
        $perms = $this->permissions ?? [];

        return (bool) ($perms[$slug] ?? false);
    }

    public static function defaultPermissionsForRole(string $role): array
    {
        return match ($role) {
            'owner' => array_fill_keys(self::ALL_PERMISSIONS, true),
            'manager' => array_merge(
                array_fill_keys(self::ALL_PERMISSIONS, true),
                ['view_reports' => false]
            ),
            'receptionist' => array_merge(
                array_fill_keys(self::ALL_PERMISSIONS, false),
                [
                    'view_bookings' => true,
                    'confirm_checkin' => true,
                    'confirm_checkout' => true,
                ]
            ),
            default => array_fill_keys(self::ALL_PERMISSIONS, false),
        };
    }

    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function employee()
    {
        return $this->belongsTo(VendorEmployee::class, 'vendor_employee_id');
    }
}
