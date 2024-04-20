<?php

namespace App\Providers;

use Faker\Core\File;
use Illuminate\Support\ServiceProvider;
use App\Services\Synthesizers\FilterSynth;
use Livewire\Livewire;

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
        Livewire::propertySynthesizer(FilterSynth::class);
    }
}
