<?php

namespace App\Providers;

use App\Services\FetchArticleService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
            Now binding the fetchers to the service container so that when we instantiate FetchArticleService the service container injects the necessary dependencies (3 Fetcher classes) into our service class (FetchArticleService)
        */
        $this->app->bind(FetchArticleService::class, function ($app) {
            return new FetchArticleService([
                $app->make(\App\Services\NewsFetchers\NewsAPIFetcher::class),
                $app->make(\App\Services\NewsFetchers\GuardianAPIFetcher::class),
                $app->make(\App\Services\NewsFetchers\NYTimesAPIFetcher::class),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
