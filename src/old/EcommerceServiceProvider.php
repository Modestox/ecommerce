<?php
/**
 * Modestox Copyright (c) 2024.
 */

namespace Modestox\Ecommerce;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class EcommerceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /**
         * Update Config Filesystems
         */
        $this->updateConfigFilesystems();

        /**
         * Init Config
         */
        $path = __DIR__ . '/etc/modules/config.json';
        if (file_exists($path)) {
            $settings = json_decode(file_get_contents($path), true);
            config(['modestox' => $settings]);
        }

        $modules = config('modestox.modules');

        /**
         * Init Modules
         */
        foreach ($modules as $key_pools => $pools) {
            foreach ($pools as $key => $module) {
                if (is_string($key)) {
                    $module = $key;
                }

                /**
                 * Routers
                 */
                $path = sprintf('%s/%s/%s/%s/%s', __DIR__, $key_pools, $module, 'Routers', 'web.php');
                if (file_exists($path)) {
                    $this->loadRoutesFrom($path);
                }

                /**
                 * Views
                 */
                $path = sprintf('%s/%s/%s/%s', __DIR__, $key_pools, $module, 'Views');
                if (is_dir($path)) {
                    $this->loadViewsFrom($path, $module);
                }

                /**
                 * Migrations
                 */
                $path = sprintf('%s/%s/%s/%s', __DIR__, $key_pools, $module, 'Migrations');
                if (is_dir($path)) {
                    $this->loadMigrationsFrom($path, $module);
                }

                /**
                 * Lang
                 */
                $path = sprintf('%s/%s/%s/%s', __DIR__, $key_pools, $module, 'Lang');
                if (is_dir($path)) {
                    $this->loadTranslationsFrom($path, $module);
                    $this->loadJsonTranslationsFrom($path);
                }

                /**
                 * Init Config
                 */
                $path = sprintf('%s/%s/%s/%s', __DIR__, $key_pools, $module, 'etc/config.json');
                if (file_exists($path)) {
                    $settings = json_decode(file_get_contents($path), true);
                    config([Str::lower($module) => $settings]);
                }

                /**
                 * Init Console Commands
                 */
                if ($this->app->runningInConsole()) {
                    $path = sprintf('%s/%s/%s/%s', __DIR__, $key_pools, $module, 'Console/Commands');

                    if (is_dir($path)) {

                        $files = File::Files($path);

                        foreach ($files as $file) {
                            if ($file->getExtension() == 'php') {
                                echo $file->getBasename();

                            }
                        }
                        //var_dump($files);
                        dd(1);

                    }
//                    $this->commands([
//                        InstallCommand::class,
//                    ]);
                }

//                $rawSettings = json_decode($request->get('settings'), true);
//                $text = json_encode($rawSettings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
//                file_put_contents(storage_path('settings.json'), $text);
            }
        }
    }

    /**
     * Update Config Filesystems
     */
    protected function updateConfigFilesystems(): void
    {
        $config = config('filesystems');

        $config['disks']['modestox_path'] = [
            'driver' => 'local',
            'root'   => app()->path(),
            'throw'  => false,
        ];

        config(['filesystems' => $config]);
    }
}
