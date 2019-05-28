<?php

namespace RoboFinance\GrafanaTableReporter\Laravel;

use RoboFinance\GrafanaTableReporter\GrafanaTableReporter;
use Illuminate\Support\ServiceProvider;

class GrafanaTableReporterServiceProvider extends ServiceProvider
{
    /**
     * publish config.
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/config/grafana_table_reporter.php' => config_path('grafana_table_reporter.php'),
            ],
            'config'
        );
    }

    public function register()
    {
        $this->app->singleton(GrafanaTableReporter::class, function () {
            return new GrafanaTableReporter(config('grafana_table_reporter.base_url'), config('grafana_table_reporter.api_token'));
        });
    }
}