<?php

namespace App\Providers;

use App\Enums\Roles;
use App\Repositories\Agenda\AgendaRepository;
use App\Repositories\Agenda\AgendaRepositoryInterface;
use App\Repositories\Agm\AgmRepository;
use App\Repositories\Agm\AgmRepositoryInterface;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Shareholder\ShareholderRepository;
use App\Repositories\Shareholder\ShareholderRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(AgmRepositoryInterface::class, AgmRepository::class);
        $this->app->bind(ShareholderRepositoryInterface::class, ShareholderRepository::class);
        $this->app->bind(AgendaRepositoryInterface::class, AgendaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('admin', function () {
            return Auth::check() && Auth::user()->role === Roles::ADMIN;
        });
    }
}
