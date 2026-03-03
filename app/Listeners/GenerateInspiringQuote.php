<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use IlluminateAuthEventsLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Inspiring;
use Illuminate\Queue\InteractsWithQueue;

class GenerateInspiringQuote
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Generate quote
        $rawQuote = Inspiring::quote();

        // Bersihkan tag console (<options>, <fg>)
        $cleanQuote = preg_replace('/<[^>]+>/', '', $rawQuote);

        // Simpan ke kolom DB
        $user->inspiring_quote = $cleanQuote;
        $user->save();

        // Simpan ke session untuk dashboard
        session(['inspiring_quote' => $cleanQuote]);
    }
}
