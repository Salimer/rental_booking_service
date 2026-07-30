<?php

namespace Tests\Unit\Services;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    public function test_send_push_posts_to_monolith_internal_notify_bridge()
    {
        Http::fake([
            '*/api/v1/internal/notify' => Http::response(['success' => true], 200),
        ]);

        $notificationService = new NotificationService;

        $sent = $notificationService->sendPush(
            null,
            'عنوان الإشعار',
            'محتوى الإشعار',
            ['type' => 'booking_status', 'booking_id' => 123],
            99
        );

        $this->assertTrue($sent);

        Http::assertSent(function ($request) {
            return $request->hasHeader('X-Internal-Secret')
                && $request['user_id'] === 99
                && $request['title'] === 'عنوان الإشعار'
                && $request['body'] === 'محتوى الإشعار';
        });
    }
}
