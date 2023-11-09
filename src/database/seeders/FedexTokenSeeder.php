<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FedexTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = \Http::asForm()
                            ->retry(3, 100, throw: false)
                            ->post(env('FEDEX_BASE_URL') . "/oauth/token", [
                                'grant_type'        => 'client_credentials',
                                'client_id'         => env('FEDEX_CLIENT_ID'),
                                'client_secret'     => env('FEDEX_CLIENT_SECRET')
                    ]);

        $responseBody = $response->json();

        \App\Models\CourierToken::create([
            'courier'           => 'fedex',
            'token'             => \Crypt::encryptString($responseBody['access_token']),
            'expiresAt'         => now()->addSeconds($responseBody['expires_in'])
        ]);
    }
}
