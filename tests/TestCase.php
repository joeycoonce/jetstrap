<?php

namespace JoeyCoonce\Jetstrap\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use JoeyCoonce\Jetstrap\JetstrapServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'JoeyCoonce\\Jetstrap\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            JetstrapServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_jetstrap_table.php.stub';
        $migration->up();
        */
    }
}
