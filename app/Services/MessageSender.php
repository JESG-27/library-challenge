<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MessageSender
{
    public function send(string $to, string $message): void
    {
        Log::info("===============================");
        Log::info("Enviando mensaje a: $to");
        Log::info("Mensaje: $message");
        Log::info("===============================");
    }
}