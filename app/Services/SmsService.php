<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(?string $to, string $message): bool
    {
        if (empty($to)) {
            return false;
        }

        // Configure TWILIO_SID, TWILIO_TOKEN, TWILIO_FROM in .env for production SMS
        if (config('services.twilio.sid') && config('services.twilio.token')) {
            try {
                // Placeholder for Twilio SDK integration
                Log::info('SMS queued (Twilio not fully wired)', ['to' => $to, 'message' => $message]);

                return true;
            } catch (\Throwable $e) {
                Log::error('SMS failed', ['error' => $e->getMessage()]);

                return false;
            }
        }

        Log::channel('single')->info("[SMS to {$to}] {$message}");

        return true;
    }
}
