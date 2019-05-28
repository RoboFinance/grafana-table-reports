<?php

namespace PHPSTORM_META {
    expectedArguments(\RoboFinance\GrafanaTableReporter\GrafanaTableReporter::getData(), 4, 'csv-resource', 'array');
    expectedArguments(\RoboFinance\GrafanaTableReporter\GrafanaTableReporter::getData(), 0, now()->subDays(30));
    expectedArguments(\RoboFinance\GrafanaTableReporter\GrafanaTableReporter::getData(), 1, now());
}

