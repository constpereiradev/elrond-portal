<?php 

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public function logError(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }

    public function logInfo(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }
}