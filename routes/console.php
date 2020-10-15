<?php

use App\Activity;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('inbox:fetch-emails', function () {
    $this->info("fetching emails from gmail... This may take a few time to complete");
    $emailService = new \App\Services\SMTPService();
    $emailService->storeEmailsToDatabase();
    Activity::create(
        [
            'activity_type' => 'SYSTEM',
            'activity' => 'Inbox synced',
        ]
    );
    $this->info("Process has been completed");

})->describe('Display an inspiring quote');
