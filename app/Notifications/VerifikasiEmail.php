<?php

namespace App\Notifications;

use App\Models\LogTautanVerifikasi;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * UJIKOM — Email verification + simpan tautan ke DB untuk demo admin.
 */
class VerifikasiEmail extends VerifyEmail
{
    protected function verificationUrl($notifiable): string
    {
        $menit = (int) Config::get('auth.verification.expire', 60);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes($menit),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        LogTautanVerifikasi::query()->create([
            'user_id' => $notifiable->getKey(),
            'email' => $notifiable->getEmailForVerification(),
            'tautan' => $url,
            'kadaluarsa_pada' => Carbon::now()->addMinutes($menit),
        ]);

        return $url;
    }
}
