<?php

use App\Models\Post;
use App\Jobs\FetchRandomUser;
use App\Jobs\ForceDeleteOldPosts;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose(description: 'Display an inspiring quote')->hourly();
Schedule::job(ForceDeleteOldPosts::class)->daily();
Schedule::job(FetchRandomUser::class)->everySixHours();