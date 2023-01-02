<?php

namespace App\Providers;

use App\InterfaceProviders\ArticleRepositoryInterface;
use App\InterfaceProviders\BaseRepositoryInterface;
use App\InterfaceProviders\FilesInterFace;
use App\ServicesProviders\ArticleServices;
use App\ServicesProviders\BaseSevice;
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
        $this->app->bind(BaseRepositoryInterface::class,BaseSevice::class);
        $this->app->bind(ArticleRepositoryInterface::class,ArticleServices::class);
        $this->app->bind(FilesInterFace::class,FilesService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
