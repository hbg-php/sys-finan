<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
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
        $this->configureCommands();
        $this->configureModels();
        $this->configureUrl();
        $this->configureCreateAnother();
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }

    private function configureModels(): void
    {
        Model::shouldBeStrict();
        Model::unguard();
    }

    private function configureUrl(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }

    private function configureCreateAnother(): void
    {
        \Filament\Resources\Pages\CreateRecord::disableCreateAnother();
        CreateAction::configureUsing(fn (CreateAction $action) => $action->createAnother(false));
    }
}
