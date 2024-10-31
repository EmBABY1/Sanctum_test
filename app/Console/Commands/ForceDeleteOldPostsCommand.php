<?php

namespace App\Console\Commands;
use App\Models\Post;
use Illuminate\Console\Command;
use App\Jobs\ForceDeleteOldPosts;

class ForceDeleteOldPostsCommand extends Command
{
    protected $signature = 'posts:force-delete-old';
    // php artisan posts:force-delete-old
    protected $description = 'Force delete old trashed posts';

    public function handle()
    {
        $trashedPosts = Post::onlyTrashed()->get();
        ForceDeleteOldPosts::dispatch($trashedPosts);
        $this->info('Old trashed posts have been deleted.');
    }
}