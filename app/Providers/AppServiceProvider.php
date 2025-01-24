<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MstrAppr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

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
        View::composer('*', function ($view) {
            $apprs = $this->count();
            $view->with('apprCount', $apprs);
        });
    }

    public function count(): int
    {
        $apprs = 0;

        // Pastikan auth()->user() tersedia sebelum mengaksesnya
        if (auth()->check()) {
            $roleId = auth()->user()->idRole;


            switch ($roleId) {
                case '2':
                    $apprs = MstrAppr::where('status', 1)->count();
                    break;
                case '3':
                    $apprs = MstrAppr::where('status', 2)->count();
                    break;
                case '4':
                    $apprs = MstrAppr::where('status', 3)->count();

                    break;
            }
        }

        return $apprs;
    }

}