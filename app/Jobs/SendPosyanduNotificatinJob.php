<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPosyanduNotificatinJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $posyanduDetails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $posyanduDetails)
    {
        $this->user = $user;
        $this->posyanduDetails = $posyanduDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Pengiriman push notifikasi
        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => 'Basic ZGZlNDhlMzMtZDdkZC00NmI3LWE5YzQtODg3MGNkODg5M2I4'
        ])->post('https://api.onesignal.com/notifications', [
            'app_id' => '9a7d21da-61b0-422e-b238-eb8cdc24cead',
            'name' => ['en' => 'Posyandu Notification'],
            'contents' => ['en' => 'Jadwal posyandu hari ini: ' . $this->posyanduDetails],
            'headings' => ['en' => 'Reminder: Jadwal Posyandu'],
            'include_subscription_ids' => [$this->user->id_subs],
        ]);

        if ($response->failed()) {
            // Handle jika terjadi error pada pengiriman
            Log::error('Failed to send notification to user ' . $this->user->id);
        }
    }
}
