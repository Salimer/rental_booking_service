<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send push notification via monolith notification bridge.
     */
    public function sendPush(?string $toFcmToken, string $title, string $body, array $data = [], ?int $userId = null, ?int $vendorId = null): bool
    {
        if (empty($toFcmToken) && empty($userId) && empty($vendorId)) {
            return false;
        }

        $monolithUrl = rtrim(config('services.monolith.url', env('MONOLITH_URL', 'http://localhost:8000')), '/');
        $secret = config('services.monolith.internal_secret', env('INTERNAL_API_SECRET', 'jac_rental_internal_secret_key_2026'));

        try {
            $response = Http::withHeaders([
                'X-Internal-Secret' => $secret,
                'Accept' => 'application/json',
            ])->post("{$monolithUrl}/api/v1/internal/notify", [
                'to_fcm_token' => $toFcmToken,
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'user_id' => $userId,
                'vendor_id' => $vendorId,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Failed to send notification via bridge: '.$e->getMessage());

            return false;
        }
    }
}
