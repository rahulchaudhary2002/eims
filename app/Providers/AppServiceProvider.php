<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // @storageExists($path) … @endStorageExists
        Blade::if('storageExists', fn ($path) => storage_exists($path));

        // Short relative time: 1m, 2h, 5d, 3w, or "M d" for older dates
        Carbon::macro('shortDiff', function () {
            $diff = (int) now()->diffInSeconds($this, true);
            if ($diff < 60)      return 'now';
            if ($diff < 3600)    return floor($diff / 60) . 'm';
            if ($diff < 86400)   return floor($diff / 3600) . 'h';
            if ($diff < 604800)  return floor($diff / 86400) . 'd';
            if ($diff < 2592000)  return floor($diff / 604800) . 'w';
            if ($diff < 31536000) return floor($diff / 2592000) . 'mo';
            return floor($diff / 31536000) . 'y';
        });
    }
}
