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

    public static function log(string $action, mixed $subject = null, ?array $payload = null): self
    {
        if (is_array($subject) && $payload === null) {
            $payload = $subject;
            $subject = null;
        }

        $user = session('dashboard_user');
        if (is_array($user)) {
            $userId = $user['id'] ?? null;
            $userName = $user['name'] ?? 'زائر';
            $userRole = $user['role'] ?? 'system';
        } elseif (is_object($user)) {
            $userId = $user->id ?? null;
            $userName = $user->name ?? 'زائر';
            $userRole = $user->role ?? 'system';
        } else {
            $userId = null;
            $userName = 'زائر';
            $userRole = 'system';
        }

        $subjectType = ($subject instanceof Model) ? class_basename($subject) : (is_string($subject) ? $subject : null);
        $subjectId = ($subject instanceof Model) ? $subject->getKey() : null;

        return self::create([
            'dashboard_user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'payload' => $payload,
            'ip_address' => request()->ip(),
        ]);
    }
}
