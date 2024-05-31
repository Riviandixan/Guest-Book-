<?php

namespace App\Providers;

use App\Models\Guest;
use Carbon\Carbon;
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

        view()->composer('*', function ($e) {
            $guest = Guest::orderBy('id', 'desc')->get();

            $e->with([
                'tanggal_sekarang' => Carbon::now()->isoFormat('dddd, DD MMMM YYYY'),
                'username' => $guest[0]->name,
            ]);
        });
    }
}
