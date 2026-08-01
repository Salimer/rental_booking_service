<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardActivityLog extends Model
{
    protected $table = 'dashboard_activity_logs';

    protected $fillable = [
        'dashboard_user_id',
        'user_name',
        'user_role',
        'action',
        'subject_type',
        'subject_id',
        'payload',
        'ip_address',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(DashboardUser::class, 'dashboard_user_id');
    }

    public static function log(string $action, ?Model $subject = null, ?array $payload = null): self
    {
        $user = session('dashboard_user');
        $userId = $user ? ($user->id ?? null) : null;
        $userName = $user ? ($user->name ?? 'زائر') : 'نظام';
        $userRole = $user ? ($user->role ?? 'system') : 'system';

        return self::create([
            'dashboard_user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
            'action' => $action,
            'subject_type' => $subject ? class_basename($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'payload' => $payload,
            'ip_address' => request()->ip(),
        ]);
    }
}
