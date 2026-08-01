<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DashboardUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'dashboard_users';

    protected $fillable = [
        'org_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'permissions',
        'status',
        'monolith_vendor_id',
        'monolith_employee_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'permissions' => 'array',
        'last_login_at' => 'datetime',
    ];

    public const ALL_PERMISSIONS = [
        'view_bookings' => 'عرض الحجوزات',
        'manage_bookings' => 'إدارة وتعديل الحجوزات',
        'confirm_checkin' => 'تأكيد دخول النزيل (Check-in)',
        'confirm_checkout' => 'تأكيد مغادرة النزيل (Check-out)',
        'view_financials' => 'عرض الإحصائيات والمالية',
        'manage_units' => 'إدارة الوحدات والأسعار',
        'manage_properties' => 'إدارة العقارات والمنشآت',
        'manage_coupons' => 'إدارة خصومات الكوبونات',
        'view_reports' => 'عرض التقارير والسجلات',
        'manage_staff' => 'إدارة طاقم الموظفين',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $perms = $this->permissions ?? [];

        return (bool) ($perms[$slug] ?? false);
    }

    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(DashboardActivityLog::class, 'dashboard_user_id');
    }
}
