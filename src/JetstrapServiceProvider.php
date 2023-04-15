<?php

namespace JoeyCoonce\Jetstrap;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use JoeyCoonce\Jetstrap\Commands\JetstrapCommand;

class JetstrapServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('jetstrap')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_jetstrap_table')
            ->hasCommand(JetstrapCommand::class);
    }
}
