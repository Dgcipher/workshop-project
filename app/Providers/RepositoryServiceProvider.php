<?php

namespace App\Providers;

use App\InterfaceProviders\ArticleServicesInterface;
use App\InterfaceProviders\BaseServicesInterface;
use App\InterfaceProviders\FilesInterface;
use App\ServicesProviders\ArticleServices;
use App\ServicesProviders\BaseService;
use App\ServicesProviders\FilesService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ArticleServicesInterface::class,ArticleServices::class);
        $this->app->bind(FilesInterface::class,FilesService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
