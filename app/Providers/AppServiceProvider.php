<?php

namespace App\Providers;

use App\Models\Question;
use App\Models\Reply;
use App\Observers\QuestionObserver;
use App\Observers\ReplyObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Question::observe(QuestionObserver::class);
        Reply::observe(ReplyObserver::class);
    }
}
