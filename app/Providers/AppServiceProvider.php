<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MstrAppr;
use App\Models\OrderSegment;
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
            $orderSegments = $this->countOrderSegments();
            $view->with('apprCount', $apprs);
            $view->with('orderSegmentCount', $orderSegments);
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

    public function countOrderSegments(): int
    {
        $orderSegmentCount = 0;

        // Pastikan auth()->user() tersedia sebelum mengaksesnya
        if (auth()->check()) {
            // Query untuk menghitung jumlah OrderSegment
            $orderSegmentCount = OrderSegment::with([
                'mstrApprs.sapFails' => function ($query) {
                    $query->where('Desc_message', '!=', 'success');
                },
                'mstrApprs.consumable.masterLineGroup',
                'user'
            ])
                ->whereHas('mstrApprs.sapFails', function ($query) {
                    $query->where('Desc_message', '!=', 'success');
                })
                ->whereHas('mstrApprs.consumable.masterLineGroup', function ($query) {
                    $query->where('NpkPjStock', auth()->user()->npk);
                })
                ->count();
        }

        return $orderSegmentCount;
    }

}