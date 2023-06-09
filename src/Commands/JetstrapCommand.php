<?php

namespace JoeyCoonce\Jetstrap\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class JetstrapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jetstrap:swap';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Install the Jetstrap components and resources';

    public function handle(): int
    {
        // Install ...
        $this->install();

        // $this->comment('All done');

        return self::SUCCESS;
    }

    /**
     * Install the Bootstrap scaffolding into the application.
     */
    protected function install(): void
    {
        // Update NPM packages...
      $this->updateNodePackages(function ($packages) {
            // Remove TrailwindCSS
            unset($packages['tailwindcss']);
            unset($packages['@tailwindcss/forms']);
            unset($packages['@tailwindcss/typography']);

            // Add Bootstrap
            return [
                '@popperjs/core' => '^2.11.6',
                'bootstrap' => '^5.2.3',
                'bootstrap-icons' => '^1.10.4',
                'sass' => '^1.58.3',
            ] + $packages;
        });

        // Remove TailwindCSS configuration...
        if ((new Filesystem)->exists(base_path('tailwind.config.js'))) {
            (new Filesystem)->delete(base_path('tailwind.config.js'));
        }

        // Remove CSS directory
        (new Filesystem)->deleteDirectory(resource_path('css'));

        // Build configurations..
        copy(__DIR__.'/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/vite.config.js', base_path('vite.config.js'));

        // Blade views...
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        copy(__DIR__.'/../../stubs/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        // Sass ...
        (new Filesystem)->ensureDirectoryExists(resource_path('sass'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/sass', resource_path('sass'));

        // Javascript ...
      /*(new Filesystem)->ensureDirectoryExists(resource_path('js'));
        copy(__DIR__.'/../../stubs/resources/js/bootstrap.js', resource_path('js/bootstrap.js'));
        copy(__DIR__.'/../../stubs/resources/js/app.js', resource_path('js/app.js'));*/

        // Components
        (new Filesystem)->ensureDirectoryExists(resource_path('views/components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/views/components', resource_path('views/components'));

        // Layouts
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Layouts', resource_path('views/layouts'));

        // Pages ...
      /*(new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));
        copy(__DIR__.'/../../stubs/resources/js/Pages/Dashboard.vue', resource_path('js/Pages/Dashboard.vue'));
        copy(__DIR__.'/../../stubs/resources/js/Pages/PrivacyPolicy.vue', resource_path('js/Pages/PrivacyPolicy.vue'));
        copy(__DIR__.'/../../stubs/resources/js/Pages/TermsOfService.vue', resource_path('js/Pages/TermsOfService.vue'));
        copy(__DIR__.'/../../stubs/resources/js/Pages/Welcome.vue', resource_path('js/Pages/Welcome.vue'));

        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/API'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages/API', resource_path('js/Pages/API'));

        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages/Auth', resource_path('js/Pages/Auth'));

        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/Profile'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages/Profile', resource_path('js/Pages/Profile'));

        // Install with Teams support ...
        if ($this->option('teams')) {
            (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/Teams'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources/js/Pages/Teams', resource_path('js/Pages/Teams'));
        }*/

        $this->line('');
        $this->info('Bootstrap 5.2 scaffolding for Livewire installed successfully.');
    }

    /**
     * Update the "package.json" file.
     *
     * @param  bool  $dev
     */
    protected static function updateNodePackages(callable $callback, $dev = true): void
    {
      if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }
}
