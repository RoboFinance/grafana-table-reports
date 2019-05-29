# About
The package uses Grafana API to export table data as raw array or csv.
Optimized to Laravel 5.5+ and framework-independent.


# Installation
``composer require robo-finance/grafana-table-reports``

# Configuration Laravel 5.5+
```bash
php artisan vendor:publish --provider="RoboFinance\GrafanaTableReporter\Laravel\GrafanaTableReporterServiceProvider"
```
See and edit the following in `grafana_table_reporter.php` or kindly use .env variables:

```php
[
      'base_url' => env('GRAFANA_TABLE_REPORTER_BASE_URL', 'https://test.com'), // Grafana base url
      'api_token' => env('GRAFANA_TABLE_REPORTER_API_TOKEN', '') //Grafana Api  token
]
```
# Using with Laravel
```php
class IndexController extends Controller
{
    public function index(GrafanaTableReporter $reporter)
    {
        $reporter->getData(now()->subDays(30), now(), 'tiTI4O2ic', 21);
        ...
```

# How to use code as framework-independent
```php
$reporter = new GrafanaTableReporter($baseUrl, $apiToken);
$from = \Carbon\Carbon::now()->subDays(10);
$to = \Carbon\Carbon::now();
$panelId = 1;
$format = 'csv-resource';
$dashboardId = 'tiTI4O2ic';
$reporter->getData($from, $to, $dashboardId, $panelId, $format);
```

# Required parameters
* `$base_url` - Grafana url. 
* `$api_token` Grafana API token https://grafana.com/docs/v3.1/http_api/auth/
* `$from`, `$to` - Dates for results you are looking for
* `$dashboardId`
* `$dashboardId`, `$panelId` you can get from panel url, for example, https://test.com/d/**tiTI4O2iz**/applications?refresh=30s&orgId=1&fullscreen&panelId=**10**
* `$format` - csv-resource | array

# License
Source code is release under MIT license. Read LICENSE file for more information.

