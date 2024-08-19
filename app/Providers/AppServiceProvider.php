<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::component('form', \App\View\Components\Form::class);
        Blade::component('alert', \App\View\Components\Alert::class);
    }
}
