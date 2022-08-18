<?php

namespace App\Providers;

use App\Models\Settings;
use App\Sync\ExternalSyncClient;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Settings::class, function (): Builder|Model {
            return Settings::query()->firstOrFail();
        });

        $this->app->singleton(ExternalSyncClient::class, function (): ExternalSyncClient
        {
            return new ExternalSyncClient(app(Settings::class)->external_sync_url);
        });
    }
}
