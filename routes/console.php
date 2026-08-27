<?php

use App\Jobs\JobCekKedaluwarsa;
use Illuminate\Support\Facades\Schedule;

/**
 * UJIKOM — Alert kedaluwarsa 30/60/90 hari.
 * Jalankan `php artisan schedule:run` saat demo, atau biarkan scheduler harian di produksi.
 */
Schedule::job(new JobCekKedaluwarsa)->dailyAt('06:00');
