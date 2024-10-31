<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchRandomUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Make an HTTP request to the RandomUser API
        $response = Http::get('https://randomuser.me/api/');

        // Log only the results object from the response
        if ($response->successful()) {
            Log::info('Random User Response: ', ['results' => $response->json()['results']]);
        } else {
            Log::error('Failed to fetch random user data');
        }
    }
}