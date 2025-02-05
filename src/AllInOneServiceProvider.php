<?php

namespace AhmedArafat\AllInOne;

use AhmedArafat\AllInOne\Console\DatabaseInitialSeedersCommand;
use AhmedArafat\AllInOne\Console\GitCommand;
use AhmedArafat\AllInOne\Middleware\JwtMiddleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class AllInOneServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            GitCommand::class,
            DatabaseInitialSeedersCommand::class,
        ]);
        Route::aliasMiddleware(
            'jwt',
            JwtMiddleware::class
        );
        $this->publishes([
            __DIR__ . '/Traits' => app_path('Traits'),
            __DIR__ . '/Helpers' => app_path('Helpers'),
            __DIR__ . '/Console' => app_path('Console/Commands/'),
            __DIR__ . '/Exceptions' => app_path('Exceptions'),
            __DIR__ . '/Middleware' => app_path('Http/Middleware'),
        ], 'ahmed-arafat/all-in-one');
    }
    public function register()
    {
        parent::register();
    }
}
