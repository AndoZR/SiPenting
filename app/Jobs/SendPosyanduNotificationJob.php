<?php

namespace App\Jobs;

use Exception;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPosyanduNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public $user;
    public $idsubsArray;
    protected $schedule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($idsubsArray, $schedule)
    {
        // $this->user = $user;
        $this->idsubsArray = $idsubsArray;
        $this->schedule = $schedule;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $response = Http::withHeaders([
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => 'Basic ZGZlNDhlMzMtZDdkZC00NmI3LWE5YzQtODg3MGNkODg5M2I4'
            ])->post('https://api.onesignal.com/notifications', [
                'app_id' => '9a7d21da-61b0-422e-b238-eb8cdc24cead',
                'name' => ['en' => 'My notification Name'],
                'contents' => ['en' => 'Halo Ibu! Ada jadwal hari ini : ' . $this->schedule->tanggal . ', ' . $this->schedule->waktu . '. ' . $this->schedule->deskripsi],
                'headings' => ['en' => 'NOTIFIKASI SIPENTING'],
                'include_subscription_ids' => $this->idsubsArray,
                // 'include_subscription_ids' => [
                //     '5a2419c2-03c3-4dac-9060-132986ab3818aaaa',
                //     // 'SUBSCRIPTION_ID_2',
                //     // 'SUBSCRIPTION_ID_3',
                // ],
            ]);

            if ($response->failed()) {
                // Handle jika terjadi error pada pengiriman
                Log::error('Failed to send notification to user');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
