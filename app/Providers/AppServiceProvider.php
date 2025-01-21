<?php

namespace App\Providers;

use App\Http\Controllers\AggController;
use App\Http\Controllers\ComeController;
use App\Http\Controllers\EggController;
use App\Http\Controllers\HomeController;
use App\Services\RedEnvelopeService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RedEnvelopeService::class, function ($app) {
            return new RedEnvelopeService();
        });
    }

  
    public function boot()
    {
         Paginator::useBootstrap();

         
    }
}
