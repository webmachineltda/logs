<?php
namespace Webmachine\Logs;

use Illuminate\Support\ServiceProvider;

class LogsServiceProvider extends ServiceProvider {
    
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {        
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('logs.php'),
        ], 'config');
        
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'logs'
        );

        if (! class_exists('CreateLogsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__ . '/database/migrations/create_logs_table.php.stub' => database_path("migrations/{$timestamp}_create_logs_table.php"),
            ], 'migrations');
        }        
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        return \App::bind('logs', function(){
            return new Logs();
        });
    }
}